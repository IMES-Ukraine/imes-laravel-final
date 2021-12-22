<?php

namespace App\Http\Repository;


use App\Http\Helpers;
use App\Models\Article;
use App\Models\Articles;
use App\Models\File;
use App\Models\Passing;
use App\Models\ProjectResearches;
use App\Models\Question;
use App\Models\QuestionModeration;
use App\Models\Test;
use App\Models\TestQuestions;
use App\Services\PassingService;
use App\Services\TagService;
use App\Services\UsersService;
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
        TestService::setAttachment($projectTotal['options']['files'], $project->id);

        if ($projectTotal['tag']) {
            TagService::addProjectTag($project->id, $projectTotal['tag']);
        }


//------------- content block
        foreach ($projectTotal['content'] as $content) {
            $scheduled = ($content['scheduled_date'] ?? date('Y-m-d')) . ' ' . ($content['scheduled_time'] ?? '00:00');
            $content['test']['schedule'] = $scheduled;

            $content['project_id'] = $project->id;
            $content['schedule'] = $scheduled;
            $research = ProjectResearches::create($content);


            //------------  article
            $article = $content['article'];
            if ($article['title']) {
                $articleModel = $this->articleService->addArticle($article);
                $articleModel->research_id = (int)$research->id;
                $articleModel->scheduled = $scheduled;
                $articleModel->save();
                TestService::setAttachment($content['article'], $articleModel->id);
            }


//------------  test
            if ($content['test']['title']) {
                //если сложный вопрос - пишем все части отдельно
                if ($content['test']['type'] == 'complex') {
                    //Сначала записываем весь сложный вопрос в качестве родителя

                    $content['test']['article_id'] = $articleModel->id ?? null;

                    $questionModel = TestQuestions::create((array)new Question($content['test']));
                    $questionModel->research_id = (int) $research->id;
                    $questionModel->save();
                    TestService::setAttachment($content['test'], $questionModel->id);

                    $parentID = $questionModel->id;
                    $complex = $content['test']['complex_question'];

                    //потом пишем подвопросы как потомков
                    foreach ($complex as $question) {

                        $test = $content['test'];
                        $test['question'] = $question;
                        $test['type'] = 'child';
                        $test['title'] = $question['title'];
                        $test['text'] = $question['text'];
                        $test['article_id'] = $articleModel->id ?? null;
                        $questionModel = TestQuestions::create((array)new Question($test));
                        $questionModel->parent_id = $parentID;
                        $questionModel->research_id = (int)$research->id;
                        $questionModel->save();
                        TestService::setAttachment($question, $questionModel->id);


                    }
                } //если простой - все проще
                else {
                    $content['test']['article_id'] = $articleModel->id ?? null;
                    $questionModel = TestQuestions::create((array)new Question($content['test']));
                    $questionModel->research_id = (int)$research->id;
                    $questionModel->save();
                    TestService::setAttachment($content['test'], $questionModel->id);

                }
            }
//------------ end test

        }
//------------- end content block

        return (object)[
            'saved' => true,
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
            ->where('id', $id)->first();
        if (empty($project) ) {
            return (object)[
                'data' => []
            ];
        }
        $project = $project->toArray();

        $itemsQuery = ProjectResearches::with('tests')->with('articles')->where('project_id', $id);
        if (!request()->input('all_items', 0)) {
            $itemsQuery->where('schedule', '<=', date('Y-m-d H:i:s'));
        }
        $content = $itemsQuery->get();

        if (empty($content)) {
            return (object)[
                'data' => []
            ];
        }

        $total = 0;
        $passing_tests = [];
        $total_status_active = 0;
        $total_status_not_participate = 0;
        $total_status_not_active = 0;
        $users_total = UsersService::getTotal();

        foreach ($content as $key => $item) {
            if ($item->test->type == 'complex') {
                //
            } else if ($item->test->type == 'easy') {
                $test_id = $item->tests[0]['id'];
                $article_id = isset($item->articles[0]) ? $item->articles[0]['id'] : 0;
                $passing = Passing::where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_TEST)->where('entity_id', $test_id)->get();

                foreach ($passing as $pass) {
                    if ($pass->answer) {
                        foreach ($pass->answer as $answer) {
                            $passing_tests[$test_id][$answer][] = $pass->user_id;
                        }
                    }
                }

                //Status passing active
                $status_active = PassingService::getPassingTotalStatus($item->id, Passing::PASSING_ACTIVE);
                $total_status_active += $status_active;
                $test_status_active = PassingService::getPassingTotalStatusTest($item->id, Passing::PASSING_ACTIVE, Passing::PASSING_RESULT_ACTIVE);
                $content[$key]->offsetSet('test_status_active', $test_status_active);

                $article_status_active = 0;
                if ($article_id) {
                    $article_status_active = PassingService::getPassingTotalStatusArticle($item->id, Passing::PASSING_ACTIVE);
                    $content[$key]->offsetSet('article_status_active', PassingService::getPassingTotalStatusArticle($item->id, Passing::PASSING_ACTIVE));
                }

                //Status passing not active
                $status_not_active = PassingService::getPassingTotalStatus($item->id, Passing::PASSING_NOT_ACTIVE);
                $total_status_not_active += $status_not_active;
                $test_status_not_active = PassingService::getPassingTotalStatusTest($item->id, Passing::NO_STATUS, Passing::PASSING_RESULT_NOT_ACTIVE);
                $content[$key]->offsetSet('test_status_not_active', $test_status_not_active);

                $article_status_not_active = 0;
                if ($article_id) {
                    $article_status_not_active = PassingService::getPassingTotalStatusArticle($item->id, Passing::PASSING_NOT_ACTIVE);
                    $content[$key]->offsetSet('article_status_not_active', $article_status_not_active);
                }

                //Status passing not participate
                $content[$key]->offsetSet('test_status_not_participate', $users_total - ($test_status_not_active + $test_status_active));

                if ($article_id) {
                    $content[$key]->offsetSet('article_status_not_participate', $users_total - ($article_status_active + $article_status_not_active));
                }

                //total
                $content[$key]->offsetSet('test_total', PassingService::getPassingTotalTest($item->id));

                if ($article_id) {
                    $content[$key]->offsetSet('article_total', PassingService::getPassingTotalArticle($item->id));
                    $content[$key]->offsetSet('article_id', $article_id);
                }

                $content[$key]->offsetSet('article_id', $article_id);

                $total += PassingService::getPassingTotal($item->id);
            }
        }

        $total_status_not_participate = $users_total - ($total_status_active + $total_status_not_active);

        return (object)['data' => [
            'project' => $project,
            'content' => $content,
            'passing_tests' => $passing_tests,
            'total' => $total,
            'status_active' => $total_status_active,
            'status_not_active' => $total_status_not_active,
            'status_not_participate' => $total_status_not_participate,
            'user_total' => $users_total
        ]];
    }

}
