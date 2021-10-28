<?php
namespace App\Http\Repository;


use App\Http\Helpers;
use App\Models\Test;
use Illuminate\Http\Request;
use App\Models\ProjectItems;
use App\Models\Projects;
use App\Services\ArticleService;
use App\Services\TestService;



class ProjectRepository
{

    protected $testService;
    protected $articleService;

    public function __construct(TestService $testService, ArticleService $articleService, Helpers $helpers) {
        $this->testService    = $testService;
        $this->articleService = $articleService;
        $this->helpers        = $helpers;
    }
    /**
     * @param Request $request
     * @return object
     */
    public function create(Request $request) {

        $projectItems = [];

        $test = new Test( $request->post('tests', []));

        foreach ( $test as $question) {


            if ( $test->isComplex && !$test->hasParent()) {

                $question->setType($test->getTestType());
                $parentQuestionModel = $this->testService->addQuestion( $question);
                $test->parentId = $parentQuestionModel->id;

            }


            /*$questionArray = (array) $question;
            $model = new TestModel();
            $model->fill( $questionArray);*/

            if ( $test->parentId) {
                $question->setParentId($test->parentId);
            }

            $question->setType($test->getTestType());

            $questionModel = $this->testService->addQuestion( $question);
            $test->isSaved = $questionModel;
            $test->setEntityId($questionModel);
        }

        $projectItems[] = [
            'type' => get_class($questionModel),
            'id'    => $test->entityId
        ];

        foreach ( $request->post('articles') as $article) {

            $articleData = $request->input('content.article.points');
            //$request->post('content.article.points')
            $articleEntity = $article + [
                    'points' => $request->post('content.article.points'),
                    //''
                ];

            $articleModel = $this->articleService->addArticle( $articleEntity);
        }

        $projectItems[] = [
            'type' => get_class($articleModel),
            'id'    => $articleModel->id
        ];


        $project = new Projects;
        $project->options = $request->input('options');
        $isProjectSaved = $project->save();

        foreach ( $projectItems as $item) {
            $items = new ProjectItems;
            $items->item_type = $item['type'];
            $items->project_id = $project->id;
            $items->item_id    = $item['id'];
            $items->data    = json_encode($request->post('content', []));
            $isProjectItemsSaved = $items->save();
        }


        return (object) [
            'saved' => !$test->isSaved || !$articleModel || !$isProjectSaved || !$isProjectItemsSaved,
            'data' => $project
        ];

    }

    /**
     * @param $request
     * @return object
     */
    public function update( $request) {

        $test = new Test( $request->post('tests', []));

        foreach ( $test as $question) {


            if ( $test->isComplex && !$test->hasParent()) {

                $question->setType($test->getTestType());
                $parentQuestionModel = $this->testService->addQuestion( $question);
                $test->parentId = $parentQuestionModel->id;

            }

            if ( $test->parentId) {
                $question->setParentId($test->parentId);
            }

            $question->setType($test->getTestType());

            $questionModel = $this->testService->addQuestion( $question);
            $test->isSaved = $questionModel;
            $test->setEntityId($questionModel);
        }

        $projectItems[] = [
            'type' => get_class($questionModel),
            'id'    => $test->entityId
        ];

        foreach ( $request->post('articles') as $article) {

            $articleData = $request->input('content.article.points');
            //$request->post('content.article.points')
            $articleEntity = $article + [
                    'points' => $request->post('content.article.points'),
                    //''
                ];

            $articleModel = $this->articleService->addArticle( $articleEntity);
        }

        $projectItems[] = [
            'type' => get_class($articleModel),
            'id'    => $articleModel->id
        ];



        return (object) [
            'data' => []
        ];
    }

    /**
     * @param $id
     * @return object
     */
    public function find( $id) {

        $project = Projects::with(['tests','tests.cover_image', 'articles', 'articles.cover_image'])
            ->where('id',$id)->first();

        $content = ProjectItems::where('project_id', $id)->first();

        if ( empty($project) || empty($content) ) {
            return (object) [
                'data' => []
            ];
        }

        foreach ( $project->articles as $article) {
            //$contentArray = (array)$article['content']; print_r($article['content']); exit;
            //$text = array_shift($contentArray);

            $article['link'] = null;
            $article['text'] = $article['content'];
            $article['insert'] = $article['content'];
            $article['textInsert'] = true;
            $article['category'] = null;
            $article['images'] = [
                'cover' => $article->cover_image
            ];
            $article['headings'] = null;
            $article['author'] = null;
            $article['authors'] = [];
            $article['button'] = null;
            $article['recommended'] = [];
            $article['chosenRecommended'] = [];
        }


        $questionsState = [];

        foreach ( $project->tests as $question) {

            $options = $question->options;
            $variants = $question->variants;
            $cover = $question->cover_image;

            foreach ($options as $o) { $media[$o->type] = $o; }

            $q = [
                'question' => [
                    'title' => $question['title'],
                    'text' => $question['question'],
                    'description' => '',
                    'link' => '',
                    'button' => NULL,
                    'count' => NULL,
                    'points' => $question['passing_bonus'],
                    'media' => [
                        'cover' => $cover,
                        'video' => isset($media['video']) && isset($media['video']['file']) ? $media['video']['file'] : NULL,
                    ],
                    'isComplex' => 'this.isComplex',
                    'agreement' => $question['agreement'],
                ],
                'variants' => $variants->buttons,
                'answer' => [
                    'correct' => $variants->correct_answer,
                    'type' => $variants->type,
                ],
            ];
            $questionsState[] = $q;

        }
        $data = [

            'options' => $project->options,
            'content'  => $content->data,
            'articles' => [
                $article
            ],
            'tests'    => $questionsState,
            'current'  => [
                'projectId' => $project->id
            ],
            'item' => $project
        ];

        return (object) [
            'data' => $data
        ];
    }

}
