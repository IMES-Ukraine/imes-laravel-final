<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\TestQuestions;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;
use App\Models\ProjectItems;
use App\Models\Projects;
use App\Models\Question;
use App\Models\QuestionModeration;
use App\Models\Test;
use App\Models\Recommended;
use App\Models\PassingProvider;
use App\Models\TestOpened;


class TestsController extends Controller
{
    protected $Test;

    protected $helpers;
    protected $repository;
    protected $project;
    protected $projectItems;


    public function __construct(Helpers $helpers, TestQuestions $Test,  Projects $project, ProjectItems  $projectItems/*, TestRepository $repository*/)
    {
        $this->Test    = $Test;
        $this->helpers          = $helpers;
        $this->project = $project;
        $this->projectItems = $projectItems;
        //$this->repository       = $repository;
    }

    /**
     * Make profile verified
     */
    public function verify(){



        return $this->helpers->apiArrayResponseBuilder(200, 'success', ['user' => 'ok']);
    }

    public function index() {

        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $passed = new PassingProvider($apiUser);
        $passedIds = $passed->getIds(TestQuestions::class);

        $userModel = User::find($apiUser->id);

        if( !$userModel->is_verified) {
            return $this->helpers->apiArrayResponseBuilder(400, 'user_not_verified', []);
        }

        $countOnPage = 15;//get('count', 15);

        $data = TestQuestions::with( ['cover_image', 'featured_images', 'agreementAccepted' => function($q) use ($apiUser) { $q->where('user_id', '=', $apiUser->id); }])->where( 'test_type', '!=', 'child')->orderBy('id', 'desc')->whereNotIn('id', $passedIds)->paginate($countOnPage);
        $data->makeHidden(['agreement']);


        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    /**
     * Show agreement box
     * @param $id
     */
    public function showAgreement($id) {

        $data = TestQuestions::where('id', '=', $id)->get();

        if (!empty($data)){
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);
    }

    /**
     * Accepts the agreement
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptAgreement($id) {

        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $model = new TestOpened();
        $model->user_id = $apiUser->id;
        $model->test_id = $id;
        $model->save();

        if (!empty($model)){
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $model->toArray());
        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);
    }



    /**
     * Select test by ID
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){

        $data = TestQuestions::with( ['cover_image', 'complex', 'featured_images', 'items'] )->where( 'id', '=', $id)->get();
        $data->makeHidden(['agreement']);

        if (!empty( $data)){
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
        }

        $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid key']);

    }


    private function genSlug($str){
        return preg_replace("/[^A-Za-z0-9]/", '', $str);
    }
    /**
     * Storing project from admin panel
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitProject() {

        $saveStatus = false;

        $tests = is_array( post('tests')) ? post('tests') : [] ;

        $isComplex = count($tests) === 1 ? false : true;
        $testType = $isComplex ? 'complex' : 'simple';
        $parentSavedId = null;

        foreach ( $tests as $test) {

            $title = $test['question']['title'];
            $question = $test['question']['text'];
            $description = $test['question']['description'];
            $correctAnswer = $test['answer']['correct'];
            $agreement = $test['question']['agreement'];


            $buttonsType = 'text';
            if ($test['answer']['type'] == 'card') $buttonsType = 'card';

            $answerType = 'variants';
            if ($test['answer']['type'] == 'text') $answerType = 'text';

            $isTextAnswerType = (boolean) ($answerType == 'text');
            $questionMedia = $test['question']['media'];
            $points = !empty($test['question']['points']) ? $test['question']['points'] : 0;
            $variantsPictures = is_array(post('attachments')) ? post('attachments') : [];
            $informationLink = $test['question']['link'];

            if ( !is_int( $parentSavedId)) {

                if ( $isComplex) {

                    $complexTestParentModel = new Test;
                    $complexTestParentModel->title = $title;
                    $complexTestParentModel->test_type = 'complex';
                    $complexTestParentModel->question = $question . ' это вопрос сложного теста';
                    $complexTestParentModel->answer_type = 'variants';
                    $complexTestParentModel->duration_seconds = 360;
                    $complexTestParentModel->passing_bonus = $points;
                    $complexTestParentModel->is_popular = rand(0,1);
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

                    if ( isset( $variantsPictures[$questionMedia])){

                        $coverImage = File::find( $variantsPictures[$questionMedia]);

                        $complexTestParentModel->cover_image = $coverImage;

                    } else {
                        $complexTestParentModel->cover_image = plugins_path() . '/ulogic/tests/updates/test1.png';
                        $complexTestParentModel->featured_images = plugins_path() . '/ulogic/tests/updates/test1.png';
                    }
                    $complexTestParentModel->save();
                }


                $model = new Test;
                $model->title = $title;

                if ( $isComplex) {
                    $parentSavedId = $complexTestParentModel->id;
                    $model->parent_id = $parentSavedId;
                    $model->test_type = 'child';
                } else {
                    $model->test_type = $testType;
                    if ( isset( $variantsPictures[$questionMedia])){

                        $coverImage = File::find( $variantsPictures[$questionMedia]);

                        $model->cover_image = $coverImage;
                    }
                }


                $model->question = $question;
                $model->answer_type = $answerType;
                $model->duration_seconds = 360;
                $model->passing_bonus = $points;
                $model->is_popular = rand(0,1);
                $model->agreement = $agreement;

                $buttons = [];

                foreach ( $test['variants'] as $variant){
                    $fields = [
                        'variant' => $variant['title'],
                        'title' => $variant['variant'],
                    ];
                    $mediaFields = [];

                    if ( isset( $variantsPictures[$variant['itemId']])){
                        $mediaFields = [
                            'description' => $variant['variant'],
                            'file'        => File::find( $variantsPictures[$variant['itemId']])
                        ];
                    }


                    if ( $buttonsType == 'card') $fields = array_merge($fields, $mediaFields);
                    $buttons[] = $fields;
                }

                if ( $isTextAnswerType )
                    $correctAnswer = [$test['variants'][0]['variant']];

                $model->variants = array(
                    'correct_answer' => $correctAnswer,
                    'type'  => $buttonsType,
                    'buttons' => $buttons
                );

                $options = [
                    [
                        'type' => 'to_learn',
                        'data' => $test['question']['link'],
                    ],
                ];

                if ( isset( $variantsPictures[$questionMedia])) {

                    $options[] =
                        [
                            'type' => 'video',
                            'data' => File::find( $variantsPictures[$questionMedia])->path,
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
                foreach ( $test['variants'] as $variant) {
                    $fields = [
                        'variant' => $variant['title'],
                        'title' => $variant['variant'],
                    ];
                    $mediaFields = [];

                    if ( isset( $variantsPictures[$variant['itemId']])){
                        $mediaFields = [
                            'description' => $variant['variant'],
                            'file'        => File::find( $variantsPictures[$variant['itemId']])
                        ];
                    }


                    if ( $buttonsType == 'card') $fields = array_merge($fields, $mediaFields);
                    $buttons[] = $fields;
                }


                if ( $answerType == 'text')
                    $correctAnswer = [$test['variants'][0]['variant']];

                $model->variants = array(
                    'correct_answer' => $correctAnswer,
                    'type'  => $buttonsType,
                    'buttons' => $buttons
                );

                if ( isset( $variantsPictures[$questionMedia])) {

                    $model->options = array(
                        [
                            'type' => 'video',
                            'data' => File::find( $variantsPictures[$questionMedia])->path,
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

        $articles = is_array( post('articles')) ? post('articles') : [];

        foreach ( $articles as $article) {

            $recommended = $article['chosenRecommended'];
            $action = !empty($article['button']) ? $article['button'] : '';

            $model = new \ULogic\News\Models\Articles;
            $model->title = $article['title'];
            $model->slug = uniqid();
            $model->published = true;
            $model->content_html = '';
            $model->type = $article['articleType'];
            $model->learning_bonus = $article['points'];
            $model->is_popular = rand(0,1);
            $model->action = $action;
            $model->user_id = 0;//$article['user_id']['id'];

            $content = [
                [
                    'type' => 'text',
                    'title' => null,
                    'content' => $article['text']
                ],
            ];
            foreach ( $article['insert'] as $insert) {
                if ( !empty( $insert['content'])) {
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

            if ( $saveStatus ){
                foreach ( $recommended as $rec){
                    Recommended::create([
                        'parent_id' => $model->id,
                        'recommended_id' => $rec['id']
                    ]);
                }
            }
        }

        if ( !$saveStatus)
            $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'saving_error']);

        $data = $model;

        $project = new Projects;
        $project->options = '{}';//$request->input('options');
        $projectStatus = $project->save();


        $items = new ProjectItems;
        $items->item_type = get_class($model);
        $items->project_id = $project->id;
        $items->item_id    = $model->id;
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
    private function getFullVariantsCount( $variants) {

        $fullCount = 0;

        foreach ( $variants as $v) {

            foreach ( $v['variant'] as $a )  $fullCount++;

        }
        return $fullCount;
    }
    /**
     * Storing test results
     * @return \Illuminate\Http\JsonResponse
     */
    public function submit() {

        $apiUser = Auth::getUser();
        $passed = new PassingProvider( $apiUser);

        $variants = post('data');

        $variant = reset($variants);

        $submitedTest = TestQuestions::find($variant['test_id']);

        if ( $submitedTest->test_type == Test::TYPE_CHILD ) {

            $parentId = $submitedTest->parent_id;
            $rootTest = Test::find($parentId);
            $fullPassingBonus = $rootTest->passing_bonus;
            $passed->setId( $rootTest, $parentId);
        } else {
            $passed->setId($submitedTest, $variant['test_id']);
            $fullPassingBonus = $submitedTest->passing_bonus;
        }

        //$userVariants = [];



        if ( $submitedTest->answer_type !== Question::ANSWER_TEXT ) {

            $fullCount = $this->getFullVariantsCount( $variants);
            $dummyAnswersCount = 0; //опросы

            $accountingAnswersCount = 0;
            $correctAnswersCount = 0;

            foreach ( $variants as $var) {

                $userVariants = $var['variant'];

                $test = TestQuestions::find( $var['test_id']);
                $correctAnswer = $test->variants['correct_answer'];


                $passed->setId( $test, $var['test_id']);


                if ( empty( $correctAnswer ) ) {

                    $dummyAnswersCount++;


                } else {

                    foreach ( $correctAnswer as $answ ) {
                        if ( in_array($answ, $userVariants) ) $correctAnswersCount++;
                    }

                }

            }

            $accountingAnswersCount = $fullCount - $dummyAnswersCount;


            $testStatus = TestQuestions::STATUS_FAILED;
            $userPassingBonus = 0;

            if ( $accountingAnswersCount > 0 ) {

                $correctAnswersPercent = ( $correctAnswersCount / $accountingAnswersCount ) * 100;
                switch ( true ) {

                    case ( $correctAnswersPercent < 20):
                        break;
                    case ( $correctAnswersPercent >= 50 && $correctAnswersPercent < 70):
                        $userPassingBonus = $fullPassingBonus / 2;
                        $testStatus = TestQuestions::STATUS_PASSED;
                        break;
                    case ( $correctAnswersPercent >= 70):
                        $userPassingBonus = $fullPassingBonus;
                        $testStatus = TestQuestions::STATUS_PASSED;
                        break;
                }
            } elseif ( $accountingAnswersCount == 0 ) {

                $testStatus = TestQuestions::STATUS_SUBMITTED;
            }

            $userModel = User::find($apiUser->id);
            $userModel->balance = $userModel->balance + $userPassingBonus;
            $userModel->save();

            $data = $userModel->toArray();

            foreach (['permissions', 'deleted_at', 'updated_at', 'activated_at'] as $v) {
                unset($data[$v]);
            }

            return $this->helpers->apiArrayResponseBuilder(200, 'success', [
                'data' => 'ok',
                'type' => 'test_submit',
                'points' => $userPassingBonus,
                'status' => $testStatus,
                'user' => $data,
            ]);
        } else {

            if (isset($variant['variant']))  $userVariants = $variant['variant'];
            if (isset($variant['variants'])) $userVariants = $variant['variants'];

            $moderationModel = new QuestionModeration();
            $moderationModel->user_id = $apiUser->id;
            $moderationModel->question_id = $variant['test_id'];
            $moderationModel->answer = reset($userVariants);
            $moderationModel->save();

            $userModel = User::find($apiUser->id);

            $data = $userModel->toArray();

            return $this->helpers->apiArrayResponseBuilder(200, 'success', [
                'data' => 'ok',
                'type' => 'test_submit',
                'points' => 0,
                'status' => TestQuestions::STATUS_PASSED,
                'user' => $data,
            ]);

        }
    }

    public function store(Request $request){

        $arr = $request->all();

        while ( $data = current($arr)) {
            $this->Test->{key($arr)} = $data;
            next($arr);
        }

        $validation = Validator::make($request->all(), $this->Test->rules);

        if( $validation->passes() ){
            $this->Test->save();
            return $this->helpers->apiArrayResponseBuilder(201, 'created', ['id' => $this->Test->id]);
        }else{
            return $this->helpers->apiArrayResponseBuilder(400, 'fail', $validation->errors() );
        }

    }

    public function update($id, Request $request){

        $status = $this->Test->where('id',$id)->update($data);

        if( $status ){

            return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been updated successfully.');

        }else{

            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', 'Error, data failed to update.');

        }
    }

    public function delete($id){

        $this->Test->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

    public function destroy($id){

        $this->Test->where('id',$id)->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', 'Data has been deleted successfully.');
    }

}
