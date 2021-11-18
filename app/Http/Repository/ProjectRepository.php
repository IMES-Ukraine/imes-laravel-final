<?php

namespace App\Http\Repository;


use App\Http\Helpers;
use App\Models\Article;
use App\Models\Articles;
use App\Models\Passing;
use App\Models\ProjectResearches;
use App\Models\Question;
use App\Models\QuestionModeration;
use App\Models\Test;
use App\Models\TestQuestions;
use App\Services\PassingService;
use Illuminate\Http\Request;
use App\Models\ProjectItems;
use App\Models\Projects;
use App\Services\ArticleService;
use App\Services\TestService;


class ProjectRepository
{

    protected $testService;
    protected $articleService;

    public function __construct(TestService $testService, ArticleService $articleService, Helpers $helpers)
    {
        $this->testService = $testService;
        $this->articleService = $articleService;
        $this->helpers = $helpers;
    }

    /**
     * @param Request $request
     * @return object
     */
    public function create(Request $request)
    {
        $projectTotal = $request->input('project');

        $project = new Projects;
        $project->options = $projectTotal['options'];
        $project->status = Projects::STATUS_ACTIVE;
        $isProjectSaved = $project->save();


//------------- content block
        foreach ($projectTotal['content'] as $content) {
            $scheduled = ($content['scheduled_date'] ?? date('Y-m-d')) . ' ' . ($content['scheduled_time'] ?? '00:00');
            $content['test']['schedule'] = $scheduled;

            $content['project_id'] = $project->id;
            $content['schedule'] = $scheduled;
            $research = ProjectResearches::create($content);


            //------------  article
            $article = $content['article'];
            $articleModel = $this->articleService->addArticle($article);
            $articleModel->research_id = $research->id;
            $articleModel->scheduled = $scheduled;
            $articleModel->save();


//------------  test

            //если сложный вопрос - пишем все части отдельно
            if ($content['test']['type'] == 'complex') {
                //Сначала записываем весь сложный вопрос в качестве родителя
                $questionModel = TestQuestions::create((array)new Question($content['test']));
                $questionModel->research_id = $research->id;
                $questionModel->save();

                $parentID = $questionModel->id;
                $complex = $content['test']['complex_question'];

                //потом пишем подвопросы как потомков
                foreach ($complex as $question) {

                    $test = $content['test'];
                    $test['question'] = $question;
                    $test['type'] = 'child';

                    $questionModel = TestQuestions::create((array)new Question($test));
                    $questionModel->parent_id = $parentID;
                    $questionModel->research_id = $research->id;
                    $questionModel->save();


                }
            } //если простой - все проще
            else {
                $questionModel = TestQuestions::create((array)new Question($content['test']));
                $questionModel->research_id = $research->id;
                $questionModel->save();

            }
//------------ end test

        }
//------------- end content block

        return (object)[
            'saved' => true ,
            'data' => $project
        ];



    }

    /**
     * @param $request
     * @return object
     */
    public
    function update($request)
    {

        $test = new Test($request->post('tests', []));

        foreach ($test as $question) {


            if ($test->isComplex && !$test->hasParent()) {

                $question->setType($test->getTestType());
                $parentQuestionModel = $this->testService->addQuestion($question);
                $test->parentId = $parentQuestionModel->id;

            }

            if ($test->parentId) {
                $question->setParentId($test->parentId);
            }

            $question->setType($test->getTestType());

            $questionModel = $this->testService->addQuestion($question);
            $test->isSaved = $questionModel;
            $test->setEntityId($questionModel);
        }

        $projectItems[] = [
            'type' => get_class($questionModel),
            'id' => $test->entityId
        ];

        foreach ($request->post('articles') as $article) {

            $articleData = $request->input('content.article.points');
            //$request->post('content.article.points')
            $articleEntity = $article + [
                    'points' => $request->post('content.article.points'),
                    //''
                ];

            $articleModel = $this->articleService->addArticle($articleEntity);
        }

        $projectItems[] = [
            'type' => get_class($articleModel),
            'id' => $articleModel->id
        ];


        return (object)[
            'data' => []
        ];
    }

    /**
     * @param $id
     * @return object
     */
    public function find($id)
    {

        $project = Projects::with(['items'])
            ->where('id', $id)->first()->toArray();

        $itemsQuery = ProjectResearches::where('project_id', $id);
        if (!request()->input('all_items', 0)) {
            $itemsQuery->where('schedule', '<=', date('Y-m-d H:i:s'));
        }
        $content = $itemsQuery->get();

        if (empty($project) || empty($content)) {
            return (object)[
                'data' => []
            ];
        }

        return (object) ['data' => ['project' => $project, 'content' => $content] ];

        $projects_items = [];
        $status_active = 0;
        $status_not_participate = 0;
        $status_not_active = 0;
/*
        foreach ($content as $item) {
                $test_not_participate = PassingService::getPassingTypeStatusAllUsers('TestQuestions', $item['item_id'], Passing::PASSING_NOT_PARTICIPATE);
                $test_active = PassingService::getPassingTypeStatusAllUsers('TestQuestions', $item['item_id'], Passing::PASSING_ACTIVE);
                $test_not_active = PassingService::getPassingTypeStatusAllUsers('TestQuestions', $item['item_id'], Passing::PASSING_NOT_ACTIVE);

                $status_active += count($test_active);
                $status_not_active += count($test_not_active);
                $status_not_participate += count($test_not_participate);

                $projects_items[$item['item_key']]['test'] = [
                    'data' => $item['data'],
                    'item_id' => $item['item_id'],
                    'not_participate' => $test_not_participate,
                    'active' => $test_active,
                    'not_active' => $test_not_active
                ];
            } else {
                $article_not_participate = PassingService::getPassingTypeStatusAllUsers('Post', $item['item_id'], Passing::PASSING_NOT_PARTICIPATE);
                $article_active = PassingService::getPassingTypeStatusAllUsers('Post', $item['item_id'], Passing::PASSING_ACTIVE);
                $article_not_active = PassingService::getPassingTypeStatusAllUsers('Post', $item['item_id'], Passing::PASSING_NOT_ACTIVE);

                $status_active += count($article_active);
                $status_not_active += count($article_not_active);
                $status_not_participate += count($article_not_participate);

                $projects_items[$item['item_key']]['article'] = [
                    'data' => $item['data'],
                    'item_id' => $item['item_id'],
                    'not_participate' => $article_not_participate,
                    'active' => $article_active,
                    'not_active' => $article_not_active
                ];
            }
            $projects_items[$item['item_key']]['schedule'] = $item->schedule;
        }

        $total = $status_active + $status_not_active + $status_not_participate;

        $article = [];
        foreach ($project->articles as $value) {
            //$contentArray = (array)$article['content']; print_r($article['content']); exit;
            //$text = array_shift($contentArray);

            $article['link'] = null;
            $article['text'] = $value['content'];
            $article['insert'] = $value['content'];
            $article['textInsert'] = true;
            $article['category'] = null;
            $article['images'] = [
                'cover' => $value->cover_image
            ];
            $article['headings'] = null;
            $article['author'] = null;
            $article['authors'] = [];
            $article['button'] = null;
            $article['recommended'] = [];
            $article['chosenRecommended'] = [];
        }


        $questionsState = [];

        foreach ($project->tests as $question) {

            $options = $question->options;
            $variants = $question->variants;
            $cover = $question->cover_image;

            foreach ($options as $o) {
                $media[$o->type] = $o;
            }

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
            'content' => $projects_items,//$content,
            'status_active' => $status_active,
            'status_not_active' => $status_not_active,
            'status_not_participate' => $status_not_participate,
            'total' => $total,
            'articles' => [
                $article
            ],
            'tests' => $questionsState,
            'current' => [
                'projectId' => $project->id
            ],
            'item' => $project
        ];
        $data['options']['status'] = $project->status;

        return (object)[
            'data' => $data
        ];
*/
    }

}
