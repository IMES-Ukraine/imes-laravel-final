<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\TestQuestions;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Auth;
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


    public function index()
    {
        $userModel = Auth::user();
        if (!$userModel) {
            return $this->helpers->apiArrayResponseBuilder(400, 'no_user', []);
        }

        if (!$userModel->is_verified) {
            return $this->helpers->apiArrayResponseBuilder(400, 'user_not_verified', []);
        }

        $passed = new PassingProvider($userModel);
        $passedIds = $passed->getIds(TestQuestions::class);


        $countOnPage = request()->get('count', 15);

        $query = TestQuestions::with(['cover_image', 'featured_images',
            'agreementAccepted' => function ($q) use ($userModel) {
                $q->where('user_id', '=', $userModel->id);
            }
        ])->where('test_type', '!=', 'child')
            ->orderBy('id', 'desc')
            ->whereNotIn('id', $passedIds);
        if (!\request()->input('all_items')) {
            $query->where('schedule', '<=', date('Y-m-d H:i:s'));
        }

        $data = $query->paginate($countOnPage);
        $data = json_decode($data->toJSON());


        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

    }

    /**
     * Show agreement box
     * @param $id
     * @return JsonResponse|void
     */
    public function showAgreement($id)
    {

        $data = TestQuestions::where('id', '=', $id)->get()->makeVisible('agreement');

        if (!$data) {
            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);
        }
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());

    }

    /**
     * Accepts the agreement
     * @param $id
     * @return JsonResponse
     */
    public function acceptAgreement($id)
    {

        //$apiUser = Auth::getUser();
        $apiUser = auth()->user();

        $model = new TestOpened();
        $model->user_id = $apiUser->id;
        $model->test_id = $id;
        $model->save();

        if (!empty($model)) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $model->toArray());
        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);
    }


    /**
     * Select test by ID
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {

        $data = TestQuestions::with(['cover_image', 'image', 'video', 'complex', 'featured_images'])->where(['id' => $id])->get();
        //     $data->makeHidden(['agreement']);

        if (!empty($data)) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }


    private function genSlug($str)
    {
        return preg_replace("/[^A-Za-z0-9]/", '', $str);
    }

    /**
     * Storing project from admin panel
     * @return JsonResponse
     */
    public function submitProject()
    {

        $saveStatus = false;

        $tests = is_array(post('tests')) ? post('tests') : [];

        $isComplex = count($tests) === 1 ? false : true;
        $testType = $isComplex ? 'complex' : 'simple';
        $parentSavedId = null;

        foreach ($tests as $test) {

            $title = $test['question']['title'];
            $question = $test['question']['text'];
            $description = $test['question']['description'];
            $correctAnswer = $test['answer']['correct'];
            $agreement = $test['question']['agreement'];


            $buttonsType = 'text';
            if ($test['answer']['type'] == 'card') $buttonsType = 'card';

            $answerType = 'variants';
            if ($test['answer']['type'] == 'text') $answerType = 'text';

            $isTextAnswerType = (boolean)($answerType == 'text');
            $questionMedia = $test['question']['media'];
            $points = !empty($test['question']['points']) ? $test['question']['points'] : 0;
            $variantsPictures = is_array(post('attachments')) ? post('attachments') : [];
            $informationLink = $test['question']['link'];

            if (!is_int($parentSavedId)) {

                if ($isComplex) {

                    $complexTestParentModel = new Test;
                    $complexTestParentModel->title = $title;
                    $complexTestParentModel->test_type = 'complex';
                    $complexTestParentModel->question = $question . ' это вопрос сложного теста';
                    $complexTestParentModel->answer_type = 'variants';
                    $complexTestParentModel->duration_seconds = 360;
                    $complexTestParentModel->passing_bonus = $points;
                    $complexTestParentModel->is_popular = rand(0, 1);
                    $complexTestParentModel->variants = null;
                    $complexTestParentModel->agreement = $agreement;
                    $complexTestParentModel->options = [
                        [
                            'type' => 'to_learn',
                            'data' => $informationLink,
                        ],
                        [
                            'type' => 'description',
                            'data' => $description
                        ]
                    ];

                    if (isset($variantsPictures[$questionMedia])) {

                        $coverImage = File::find($variantsPictures[$questionMedia]);

                        $complexTestParentModel->cover_image = $coverImage;

                    } else {
                        $complexTestParentModel->cover_image = plugins_path() . '/ulogic/tests/updates/test1.png';
                        $complexTestParentModel->featured_images = plugins_path() . '/ulogic/tests/updates/test1.png';
                    }
                    $complexTestParentModel->save();
                }


                $model = new Test;
                $model->title = $title;

                if ($isComplex) {
                    $parentSavedId = $complexTestParentModel->id;
                    $model->parent_id = $parentSavedId;
                    $model->test_type = 'child';
                } else {
                    $model->test_type = $testType;
                    if (isset($variantsPictures[$questionMedia])) {

                        $coverImage = File::find($variantsPictures[$questionMedia]);

                        $model->cover_image = $coverImage;
                    }
                }


                $model->question = $question;
                $model->answer_type = $answerType;
                $model->duration_seconds = 360;
                $model->passing_bonus = $points;
                $model->is_popular = rand(0, 1);
                $model->agreement = $agreement;

                $buttons = [];

                foreach ($test['variants'] as $variant) {
                    $fields = [
                        'variant' => $variant['title'],
                        'title' => $variant['variant'],
                    ];
                    $mediaFields = [];

                    if (isset($variantsPictures[$variant['itemId']])) {
                        $mediaFields = [
                            'description' => $variant['variant'],
                            'file' => File::find($variantsPictures[$variant['itemId']])
                        ];
                    }


                    if ($buttonsType == 'card') $fields = array_merge($fields, $mediaFields);
                    $buttons[] = $fields;
                }

                if ($isTextAnswerType)
                    $correctAnswer = [$test['variants'][0]['variant']];

                $model->variants = array(
                    'correct_answer' => $correctAnswer,
                    'type' => $buttonsType,
                    'buttons' => $buttons
                );

                $options = [
                    [
                        'type' => 'to_learn',
                        'data' => $test['question']['link'],
                    ],
                ];

                if (isset($variantsPictures[$questionMedia])) {

                    $options[] =
                        [
                            'type' => 'video',
                            'data' => File::find($variantsPictures[$questionMedia])->path,
                        ];

                }

                $model->options = $options;

                $saveStatus = $model->save();

            } else {

                $model = new TestQuestions;
                $model->title = $title;
                $model->test_type = 'child';
                $model->parent_id = $parentSavedId;
                $model->question = $question;
                $model->answer_type = $answerType;
                $model->duration_seconds = 360;

                $buttons = [];
                foreach ($test['variants'] as $variant) {
                    $fields = [
                        'variant' => $variant['title'],
                        'title' => $variant['variant'],
                    ];
                    $mediaFields = [];

                    if (isset($variantsPictures[$variant['itemId']])) {
                        $mediaFields = [
                            'description' => $variant['variant'],
                            'file' => File::find($variantsPictures[$variant['itemId']])
                        ];
                    }


                    if ($buttonsType == 'card') $fields = array_merge($fields, $mediaFields);
                    $buttons[] = $fields;
                }


                if ($answerType == 'text')
                    $correctAnswer = [$test['variants'][0]['variant']];

                $model->variants = array(
                    'correct_answer' => $correctAnswer,
                    'type' => $buttonsType,
                    'buttons' => $buttons
                );

                if (isset($variantsPictures[$questionMedia])) {

                    $model->options = array(
                        [
                            'type' => 'video',
                            'data' => File::find($variantsPictures[$questionMedia])->path,
                        ],
                    );
                } else {
                    $model->options = [];

                    /*$fileVideo = new File;
                    $fileVideo->data = plugins_path() . '/ulogic/tests/updates/video-sample.mp4';
                    $fileVideo->is_public = true;
                    $fileVideo->save();

                    $model->options = array(
                        [
                            'type' => 'video',
                            'data' => $fileVideo->path,
                        ],
                    );*/
                }

                $model->save();
            }
        }

        $articles = is_array(post('articles')) ? post('articles') : [];

        foreach ($articles as $article) {

            $recommended = $article['chosenRecommended'];
            $action = !empty($article['button']) ? $article['button'] : '';

            $model = new \ULogic\News\Models\Articles;
            $model->title = $article['title'];
            $model->slug = uniqid();
            $model->published = true;
            $model->content_html = '';
            $model->type = $article['articleType'];
            $model->learning_bonus = $article['points'];
            $model->is_popular = rand(0, 1);
            $model->action = $action;
            $model->user_id = 0;//$article['user_id']['id'];

            $content = [
                [
                    'type' => 'text',
                    'title' => null,
                    'content' => $article['text']
                ],
            ];
            foreach ($article['insert'] as $insert) {
                if (!empty($insert['content'])) {
                    $content[] = [
                        'type' => 'text',
                        'title' => $insert['title'],
                        'content' => $insert['content']
                    ];
                }

            }
            $model->content = $content;

            $file = File::find($article['cover_image']['id']);

            $model->cover_image = $file;//plugins_path() . '/ulogic/news/updates/news-featured.png';
            $model->featured_images = $file;
            $model->published_at = time();
            $saveStatus = $model->save();

            if ($saveStatus) {
                foreach ($recommended as $rec) {
                    Recommended::create([
                        'parent_id' => $model->id,
                        'recommended_id' => $rec['id']
                    ]);
                }
            }
        }

        if (!$saveStatus)
            $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'saving_error']);

        $data = $model;

        $project = new Projects;
        $project->options = '{}';//$request->input('options');
        $projectStatus = $project->save();


        $items = new ProjectItems;
        $items->item_type = get_class($model);
        $items->project_id = $project->id;
        $items->item_id = $model->id;
        $projectItemsStatus = $items->save();


        return $this->helpers->apiArrayResponseBuilder(200, 'success', [
            'data' => 'ok',
            'type' => 'test_submit',
            'project' => $data
        ]);
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


        if ($submittedTest->test_type == TestQuestions::TYPE_CHILD) {

            $parentId = $submittedTest->parent_id;
            $rootTest = Test::find($parentId);
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

        //$userVariants = [];


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
                    //TODO разобраться, может ли быть несколько ответов к одному вопросу
                    foreach ($correctAnswer as $answ) {
                        if (in_array($answ, $userVariants)) {
                            $correctAnswersCount++;
                            $isRightAnswer = true;
                        }
                    }
                }
                $passed->setId($testClass, $testID, $isRightAnswer, $userVariants);
            }


            $accountingAnswersCount = $fullCount - $dummyAnswersCount;

            $testStatus = TestQuestions::STATUS_FAILED;
            $userPassingBonus = 0;

            if ($accountingAnswersCount > 0) {
                //если есть вопросы с выбором ответов. Считаем сумму полученных бонусов
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
                $testStatus = TestQuestions::STATUS_SUBMITTED;
                $userPassingBonus = $fullPassingBonus;
            }

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
