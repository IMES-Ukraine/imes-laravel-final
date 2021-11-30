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
                $articleModel->research_id = $research->id;
                $articleModel->scheduled = $scheduled;
                $articleModel->save();
                TestService::setAttachment($content['article'], $articleModel->id);
            }


//------------  test
            if ($content['test']['title']) {
                //если сложный вопрос - пишем все части отдельно
                if ($content['test']['type'] == 'complex') {
                    //Сначала записываем весь сложный вопрос в качестве родителя
                    $questionModel = TestQuestions::create((array)new Question($content['test']));
                    $questionModel->research_id = $research->id;
                    $questionModel->save();
                    TestService::setAttachment($content['test'], $questionModel->id);

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
                        TestService::setAttachment($question, $questionModel->id);


                    }
                } //если простой - все проще
                else {
                    $questionModel = TestQuestions::create((array)new Question($content['test']));
                    $questionModel->research_id = $research->id;
                    $questionModel->save();
                    TestService::setAttachment($content['test']['question'], $questionModel->id);

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
            ->where('id', $id)->first()->toArray();

        $itemsQuery = ProjectResearches::with('tests')->with('articles')->where('project_id', $id);
        if (!request()->input('all_items', 0)) {
            $itemsQuery->where('schedule', '<=', date('Y-m-d H:i:s'));
        }
        $content = $itemsQuery->get();

        if (empty($project) || empty($content)) {
            return (object)[
                'data' => []
            ];
        }

        $total = 0;
        $passing_tests = [];
        $total_status_active = 0;
        $total_status_not_participate = 0;
        $total_status_not_active = 0;

        foreach ($content as $key => $item) {
            if (isset($item->tests[0])) {
                $test_id = $item->tests[0]['id'];
                $passing = Passing::where('entity_type', 'TestQuestions')->where('entity_id', $test_id)->get();

                foreach ($passing as $pass) {
                    if ($pass['answer']) {
                        foreach ($pass['answer'] as $answer) {
                            $passing_tests[$test_id][$answer][] = $pass->user_id;
                        }
                    }
                }

                //Status passing active
                $status_active = PassingService::getPassingTotalStatus($item->id, Passing::PASSING_ACTIVE);
                $total_status_active += $status_active;
                $content[$key]->offsetSet('test_status_active', PassingService::getPassingTotalStatusTest($item->id, Passing::PASSING_ACTIVE));
                $content[$key]->offsetSet('article_status_active', PassingService::getPassingTotalStatusArticle($item->id, Passing::PASSING_ACTIVE));

                //Status passing not active
                $status_not_active = PassingService::getPassingTotalStatus($item->id, Passing::PASSING_NOT_ACTIVE);
                $total_status_not_active += $status_not_active;
                $content[$key]->offsetSet('test_status_not_active', PassingService::getPassingTotalStatusTest($item->id, Passing::PASSING_NOT_ACTIVE));
                $content[$key]->offsetSet('article_status_not_active', PassingService::getPassingTotalStatusArticle($item->id, Passing::PASSING_NOT_ACTIVE));

                //Status passing not participate
                $status_not_participate = PassingService::getPassingTotalStatus($item->id, 3);
                $total_status_not_participate += $status_not_participate;
                $content[$key]->offsetSet('test_status_not_participate', PassingService::getPassingTotalStatusTest($item->id, 3));
                $content[$key]->offsetSet('article_status_not_participate', PassingService::getPassingTotalStatusArticle($item->id, 3));

                //total
                $content[$key]->offsetSet('test_total', PassingService::getPassingTotalTest($item->id));
                $content[$key]->offsetSet('article_total', PassingService::getPassingTotalArticle($item->id));
                $total += PassingService::getPassingTotal($item->id);
            }
        }

        return (object)['data' => [
            'project' => $project,
            'content' => $content,
            'passing_tests' => $passing_tests,
            'total' => $total,
            'status_active' => $total_status_active,
            'status_not_active' => $total_status_not_active,
            'status_not_participate' => $total_status_not_participate,
        ]];
    }

}
