<?php

namespace App\Http\Repository;


use App\Http\Helpers;
use App\Models\Article;
use App\Models\Articles;
use App\Models\Passing;
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
        $isProjectItemsSaved = true;

        $index = 1;
//------------- content block
        foreach ($projectTotal['content'] as $content) {
            $scheduled = ($content['scheduled_date'] ?? date('Y-m-d') ) . ' ' . ( $content['scheduled_time'] ?? '00:00');

//------------  test

            //если сложный вопрос - пишем все части отдельно
            if ($content['test']['type'] == 'complex') {
                $complex = $content['test']['complex_question'];
                foreach ($complex as $question) {
                    $items = new ProjectItems;
                    $items->item_key = $index;
                    $items->item_id = 0;
                    $items->project_id = $project->id;
                    $items->data = $question;
                    $items->schedule = $scheduled;
                    $isProjectItemsSaved &= $items->save();

                    $test = $content['test'];
                    $test['question'] = $question;

                    $questionModel = TestQuestions::create((array)new Question($test));
                    $questionModel->parent_id = $items->id;
                    $questionModel->save();

                    $items->item_type = get_class($questionModel);
                    $items->item_id = $questionModel->id;
                    $isProjectItemsSaved &= $items->save();
                }
            } //если простой - все проще
            else {
                $questionModel = TestQuestions::create((array)new Question($content['test']));
                $questionModel->save();

                $items = new ProjectItems;
                $items->item_key = $index;
                $items->project_id = $project->id;
                $items->data = $content['test'];
                $items->item_type = get_class($questionModel);
                $items->item_id = $questionModel->id;
                $items->schedule = $scheduled;
                $isProjectItemsSaved &= $items->save();
            }
//------------ end test


//------------  article
            $article = $content['article'];
            $articleModel = $this->articleService->addArticle($article);

            $items = new ProjectItems;
            $items->item_type = get_class($articleModel);
            $items->item_key = $index;
            $items->item_id = $articleModel->id;
            $items->project_id = $project->id;
            $items->data = $content['article'];
            $items->schedule = $scheduled;
            $isProjectItemsSaved &= $items->save();
            $index++;
        }
//------------- end content block

        return (object)[
            'saved' => !$questionModel || !$articleModel || !$isProjectSaved || !$isProjectItemsSaved,
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

        $project = Projects::with(['tests', 'tests.cover_image', 'articles', 'articles.cover_image'])
            ->where('id', $id)->first();

        $itemsQuery = ProjectItems::where('project_id', $id);
        if (!request()->input('all_items', 0)) {
            $itemsQuery->where('schedule', '<=', date('Y-m-d H:i:s'));
        }
        $content = $itemsQuery->get();

        if (empty($project) || empty($content)) {
            return (object)[
                'data' => []
            ];
        }

        $projects_items = [];
        $status_active = 0;
        $status_not_participate = 0;
        $status_not_active = 0;

        foreach ($content as $item) {
            $projects_items['schedule'] = $item->schedule;
            if ($item['item_type'] == 'App\Models\TestQuestions') {
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

        return (object)[
            'data' => $data
        ];
    }

}
