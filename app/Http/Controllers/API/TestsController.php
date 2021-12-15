<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Passing;
use App\Models\ProjectsAgreement;
use App\Models\TestQuestions;
use Exception;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\ProjectItems;
use App\Models\Projects;
use App\Models\Question;
use App\Models\QuestionModeration;
use App\Models\Test;
use App\Models\Recommended;
use App\Models\PassingProvider;
use App\Models\TestOpened;
use function MongoDB\BSON\toJSON;


class TestsController extends Controller
{
    protected $Test;

    protected $helpers;
    protected $repository;
    protected $project;
    protected $projectItems;


    public function __construct(Helpers $helpers, TestQuestions $Test, Projects $project /*ProjectItems  $projectItems, TestRepository $repository*/)
    {
        parent::__construct($helpers);
        $this->Test = $Test;
        $this->helpers = $helpers;
        $this->project = $project;
    }

    // Принудительная типизация уже сохраненных данных.
    private function setParamTypes($data) {
        $data['research_id'] = $data['research_id'] ? (int)$data['research_id'] : null;
        foreach ($data['options'] as $key => $item) {
            if (isset($data['options'][$key]['type']) && $data['options'][$key]['type'] == Question::TYPE_TO_LEARN) {
                $data['options'][$key]['data'] = $data['options'][$key]['data'] ? (int)$data['options'][$key]['data'] : null;
            }
        }
        return $data;
}

    public function index()
    {
        $userModel = Auth::user();

        if (!$userModel->is_verified) {
            return $this->helpers->apiArrayResponseBuilder(403, 'Пользователь не верифицирован', []);
        }

        $countOnPage = request()->get('count', 15);

//        DB::enableQueryLog();

        $query = TestQuestions::select('ulogic_tests_questions.*')
            ->with(['cover_image', 'featured_images'])->where('ulogic_tests_questions.test_type', '!=', 'child')
            ->isProjectActive()
            ->isNotPassed($userModel->id)
            ->orderBy('ulogic_tests_questions.id', 'desc');
        if (!\request()->input('all_items')) {
            $query->where('ulogic_tests_questions.schedule', '<=', date('Y-m-d H:i:s'));
        }

//        $query->get();
//        dd(DB::getQueryLog());

        $data = $query->paginate($countOnPage);
        $data->setCollection($data->makeHidden('research'));

        $data = json_decode($data->toJSON(), true);

        foreach ($data['data'] as $key => $item) {
            $data['data'][$key] = $this->setParamTypes($item);
        }
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

    }


    public function callback($id)
    {
        $apiUser = auth()->user();

        $model = new TestOpened();
        $model->user_id = $apiUser->id;
        $model->test_id = $id;


        if ($model->save()) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $model->toArray());
        }

        return $this->helpers->apiArrayResponseBuilder(400, 'error', $model->toArray());
    }


    /**
     * Select test by ID
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $apiUser = auth()->user();
        $test = TestQuestions::with(['cover_image', 'image', 'video', 'complex', 'featured_images'])
            ->where(['id' => $id])
            ->first()
            ->makeHidden('research');

        if (!empty($test)) {
            $data = json_decode($test->toJson(), true);
            $data = $this->setParamTypes($data);
            if (!$test->isOpened) {
                $model = new TestOpened();
                $model->user_id = $apiUser->id;
                $model->test_id = $id;
                $model->save();
            }
            $passed = new PassingProvider($apiUser);
            $passed->setId($test, $test->id, Passing::PASSING_NOT_ACTIVE);

            return $this->helpers->apiArrayResponseBuilder(200, 'success', [$data]);
        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }


    private function genSlug($str)
    {
        return preg_replace("/[^A-Za-z0-9]/", '', $str);
    }


    /**
     * @param $variants
     * @return int
     */
    private function getFullVariantsCount($variants)
    {

        $fullCount = 0;

        foreach ($variants as $v) {

            foreach ($v['variant'] as $a) $fullCount++;

        }
        return $fullCount;
    }

    /**
     * Storing test results
     * @return JsonResponse
     */
    public function submit(Request $request)
    {
        /** @var User $apiUser */
        $apiUser = auth()->user();
        $passed = new PassingProvider($apiUser);

        $variants = $request->post('data');
        Log::info('Submitted test', [$variants]);
        $variant = reset($variants);
        $submittedTest = TestQuestions::find($variant['test_id']);

        if (!$submittedTest->can_retake && in_array($variant['test_id'], $passed->getIds(TestQuestions::class))) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', [
                'data' => 'test already done',
                'type' => 'test_submit',
                'points' => 0,
                'status' => TestQuestions::STATUS_PASSED,
                'user' => $apiUser->makeHidden(['permissions', 'deleted_at', 'updated_at', 'activated_at'])->toArray(),
            ]);
        }
        $passedModel = $passed->setId($submittedTest, $submittedTest->id, Passing::PASSING_ACTIVE, $variants);

        if ($submittedTest->test_type == TestQuestions::TYPE_CHILD) {

            $parentId = $submittedTest->parent_id;
            //$rootTest = Test::find($parentId);
            $rootTest = TestQuestions::find($parentId);
            $fullPassingBonus = $rootTest->passing_bonus;
            $testClass = $rootTest;
            $testID = $parentId;
//            $passed->setId($rootTest, $parentId);
        } else {
            $testClass = $submittedTest;
            $testID = $variant['test_id'];
//            $passed->setId($submittedTest, $variant['test_id']);
            $fullPassingBonus = $submittedTest->passing_bonus;
        }

        $userVariants = [];


        if ($submittedTest->answer_type !== Question::ANSWER_TEXT) {

            $fullCount = $this->getFullVariantsCount($variants);
            $dummyAnswersCount = 0; //опросы
            $correctAnswersCount = 0; //тесты

            foreach ($variants as $var) {
                $userVariants = $var['variant'];
                $test = TestQuestions::find($var['test_id']);
                $correctAnswer = $test->variants['correct_answer'];

                $isRightAnswer = false;
                if (empty($correctAnswer)) {
                    $dummyAnswersCount++;
                } else {
                    //TODO разобраться, может ли быть несколько правильных ответов к одному вопросу
                    foreach ($correctAnswer as $answ) {
                        if (in_array($answ, $userVariants)) {
                            $correctAnswersCount++;
                        }
                    }
                }
            }


            $accountingAnswersCount = $fullCount - $dummyAnswersCount;

            $testStatus = TestQuestions::STATUS_FAILED;
            $userPassingBonus = 0;

            if ($accountingAnswersCount > 0) {
                // Если есть вопросы с выбором ответов. Считаем сумму полученных бонусов
                $correctAnswersPercent = ($correctAnswersCount / $accountingAnswersCount) * 100;
                switch (true) {
                    case ($correctAnswersPercent < 20):
                        break;
                    case ($correctAnswersPercent >= 50 && $correctAnswersPercent < 70):
                        $userPassingBonus = $fullPassingBonus / 2;
                        $testStatus = TestQuestions::STATUS_PASSED;
                        break;
                    case ($correctAnswersPercent >= 70):
                        $userPassingBonus = $fullPassingBonus;
                        $testStatus = TestQuestions::STATUS_PASSED;
                        break;
                    default :
                }
            } else {
                //иначе - мы в опроснике и отдаём сразу всю сумму бонусов
                $testStatus = TestQuestions::STATUS_PASSED;
                $userPassingBonus = $fullPassingBonus;
            }

            $passedModel->result = $userPassingBonus > 0;
            $passedModel->save();

            $apiUser->balance = $apiUser->balance + $userPassingBonus;
            $apiUser->save();
        } else {
            if (isset($variant['variant'])) {
                $userVariants = $variant['variant'];
            }

            $moderationModel = new QuestionModeration();
            $moderationModel->user_id = $apiUser->id;
            $moderationModel->question_id = $variant['test_id'];
            $moderationModel->answer = reset($userVariants);
            $moderationModel->save();

            $testStatus = TestQuestions::STATUS_PASSED;
            $userPassingBonus = 0;

        }

        $data = $apiUser->makeHidden(['permissions', 'deleted_at', 'updated_at', 'activated_at'])->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', [
            'data' => 'ok',
            'type' => 'test_submit',
            'points' => $userPassingBonus,
            'status' => $testStatus,
            'user' => $data,
        ]);
    }

    public function store(Request $request)
    {

        $arr = $request->all();

        while ($data = current($arr)) {
            $this->Test->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->Test->rules);

        if ($validation->passes()) {
            $this->Test->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Test->id]);
        } else {
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors());
        }

    }

    public function update($id, Request $request)
    {

        $status = $this->Test->where('id', $id)->update($request->input('data'));

        if ($status) {

            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        } else {

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id)
    {

        $this->Test->where('id', $id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id)
    {

        $this->Test->where('id', $id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

}
