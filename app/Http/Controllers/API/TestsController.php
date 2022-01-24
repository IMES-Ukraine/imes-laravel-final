<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Notifications;
use App\Models\Passing;
use App\Models\ProjectsAgreement;
use App\Models\TestQuestions;
use App\Services\TestService;
use App\Traits\NotificationsHelper;
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
    private function setParamTypes($data)
    {
        $data['research_id'] = isset($data['research_id']) ? (int)$data['research_id'] : null;
        foreach ($data['options'] as $key => $item) {
            if (isset($data['options'][$key]['type']) && $data['options'][$key]['type'] == Question::TYPE_TO_LEARN) {
                $data['options'][$key]['data'] = $data['options'][$key]['data'] ? (int)$data['options'][$key]['data'] : null;
            }
        }
        if($data['agreement'] == '') {
            $data['isAgreementAccepted'] = true;
        }
        return $data;
    }

    public function index()
    {
        $userModel = Auth::user();

        if (!$userModel->is_verified) {
            return $this->helpers->apiArrayResponseBuilder(200, 'Пользователь не верифицирован', []);
        }

        $countOnPage = request()->get('count', 15);

//        DB::enableQueryLog();

        $query = TestQuestions::select('ulogic_tests_questions.*')
            ->quota()
            ->with(['cover_image', 'featured_images'])->where('ulogic_tests_questions.test_type', '!=', 'child')
            ->leftJoin('project_researches', 'project_researches.id', '=', 'ulogic_tests_questions.research_id')
            ->leftJoin('ulogic_projects_settings', 'ulogic_projects_settings.id', '=', 'project_researches.project_id')
            ->isProjectActive()
            ->audience($userModel->id)
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
        /** @var TestQuestions $test */
        $test = TestQuestions::with(['cover_image', 'image', 'video',  'featured_images'])
            ->where(['id' => $id])
            ->first();


        if (!empty($test)) {
            $test->makeHidden('research');

            // Это костыль. Непонятно, почему вешается при обычном преобразовании $test->complex()->get()->toArray() - всё падает
            $data = $test->toArray();

            $complex = $test->complex;
            $res = [];
            foreach ($complex as $item){
                $res[] = $item->attributesToArray();
            }
            $data['complex'] = $res;

            $data = $this->setParamTypes($data);
            if (!$test->isOpened) {
                $model = new TestOpened();
                $model->user_id = $apiUser->id;
                $model->test_id = $id;
                $model->save();
            }
            $passed = new PassingProvider($apiUser);
            $passed->setId($test);

            return $this->helpers->apiArrayResponseBuilder(200, 'success', [$data]);
        }

        return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

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
        if (!$variants) {
            return $this->helpers->apiArrayResponseBuilder(200);
        }

        $firstTest = TestQuestions::find($variants[0]['test_id']);

        $isTestsAll = $firstTest->test_type !== TestQuestions::TYPE_CHILD || TestService::getComplexAll(count($variants), $firstTest->research_id);

        // если не последний ответ - пропускаем
        if (!$isTestsAll) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', [
                'data' => 'not all tests',
                'type' => TestService::TEST_SUBMITTED,
                'points' => 0,
                'status' => TestQuestions::STATUS_FAILED,
                'user' => $apiUser->makeHidden(['permissions', 'deleted_at', 'updated_at', 'activated_at'])->toArray()
            ]);
        }

        // проверим, выполнен ли простой тест
        if ($firstTest->test_type === TestQuestions::TYPE_SIMPLE) {
            $testIDforCheck = $firstTest->id;
        } // Иначе тест сложный - проверяем родительский тест
        else if ($firstTest->parent_id) {
            $parent = TestQuestions::find($firstTest->parent_id);
            $testIDforCheck = $parent->id;
        }
        else {
            // Если у нас почему-то тут оказался родительский сложный тест - ничего не делаем.
            return $this->helpers->apiArrayResponseBuilder(200, 'success', [
                'data' => 'complex test',
                'type' => TestService::TEST_SUBMITTED,
                'points' => 0,
                'status' => TestQuestions::STATUS_FAILED,
                'user' => $apiUser->makeHidden(User::TO_HIDE)->toArray()
            ]);
        }

        // Проверяем собственно пройден ли тест
        if (in_array($testIDforCheck, $passed->getResults(TestQuestions::class))) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', [
                'data' => 'test already done',
                'type' => TestService::TEST_SUBMITTED,
                'points' => 0,
                'status' => TestQuestions::STATUS_PASSED,
                'user' => $apiUser->makeHidden(User::TO_HIDE)->toArray()
            ]);
        }

        // Выполняем проверку правильности теста
        $result = TestService::verifyTest($variants, $apiUser);
        Log::info('Verify', $result);

        return $this->helpers->apiArrayResponseBuilder(200, 'success', [
            'data' => 'ok',
            'type' => $result['resType'],
            'points' => $result['userPassingBonus'],
            'status' => $result['testOutStatus'],
            'user' => $result['data'],
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

    public
    function update($id, Request $request)
    {

        $status = $this->Test->where('id', $id)->update($request->input('data'));

        if ($status) {

            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        } else {

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public
    function delete($id)
    {

        $this->Test->where('id', $id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public
    function destroy($id)
    {

        $this->Test->where('id', $id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

}
