<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Models\File;
use App\Models\ImageHelper;
use App\Models\ProjectItems;
use App\Models\ProjectResearches;
use App\Models\ProjectsAgreement;
use App\Models\Tags;
use App\Models\TestQuestions;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Repository\ProjectRepository;
use App\Models\Projects;
use Illuminate\Support\Facades\Storage;

class ProjectsController extends Controller
{
    const ATTACHMENT_TYPE_ARTICLES = 'App\Models\Articles';
    const ATTACHMENT_TYPE_TEST_QUESTIONS = 'App\Models\TestQuestions';
    const ATTACHMENT_TYPE_PROJECT = 'App\Models\Projects';
    const ATTACHMENT_TYPE_BANNERS = 'App\Models\Banners';
    const ATTACHMENT_TYPE_CSV = 'CSV';


    protected $projectRepository;

    /**
     * ProjectsController constructor.
     * @param Helpers $helpers
     * @param ProjectRepository $projectRepository
     */
    public function __construct(Helpers $helpers, ProjectRepository $projectRepository)
    {
        parent::__construct($helpers);
        $this->projectRepository = $projectRepository;
    }

    /**
     * @return JsonResponse
     */
    /**
     * @return JsonResponse
     */
    public function index()
    {
        //Controllers/ProjectController::index

        $countOnPage = 15;
        $data = Projects::with('tags')->with('items')->whereNull('deleted_at')->with('items')->orderBy('created_at', 'DESC')->paginate($countOnPage);
        $data = json_decode($data->toJSON());
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $model = Projects::findOrFail($id);

        $model->update(['status' => Projects::STATUS_INACTIVE]);


        //инактивируем статьи проекта
        $this->setArticlesPublishedStatus($model, 0);

//        удалим тесты проекта
        foreach ($model->items as $item){
            foreach ($item->tests as $test){
                $test->delete();
            }
        }

        //удалим и сам проект
        $model->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success');
    }

    /**
     * @return JsonResponse
     */
    public function tags(): JsonResponse
    {
        /* START Delete empty tags*/
        //TODO: add this code to cron
        $tagsProjects = DB::table('ulogic_projects_tags')->select('project_id', 'tag_id')->get();
        $tP = $tagsProjects->toArray();
        if(!empty($tP)){
            $temp = [];
            $tempTags = [];
            foreach ($tP as $item) {
                $temp[$item->project_id]=$item->project_id;
                $tempTags[$item->project_id]=$item->tag_id;
            }
            if(!empty($temp)){
                $proj = Projects::whereNull('deleted_at')->select('id')->whereIn('id',$temp)->get();
                if(!empty($proj)){
                    $tempProj = [];
                    foreach ($proj as $item) {
                        $tempProj[$item->id]=$item->id;
                    }
                }
                if(!empty($tempProj)){
                    foreach ($tempProj as $notDell) {
                        unset($temp[$notDell]);
                        unset($tempTags[$notDell]);
                    }
                    //Delete id in relation tag proj
                    if(!empty($temp)){
                        DB::table('ulogic_projects_tags')->whereIn('project_id', $temp)->delete();
                        foreach ($tempTags as $tT) {
                            //check if another proj with this tag
                            $res = DB::table('ulogic_projects_tags')->where('tag_id', $tT)->first();
                            if(!$res){
                                //delete tag if not
                                DB::table('tags')->where('id', $tT)->delete();
                            }
                        }
                    }
                }
            }
        }
        /* END Delete empty tags*/

        $data = Tags::all();

        $r = $data->toArray();
        $cnt = count($r);
        $r[$cnt]['name']="Все проекты";
        $r[$cnt]['slug']="";
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $r);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {

        $rules = [
            'options' => 'required',
            'options.title' => 'required',
            'articles' => 'required',
            'articles.*.images.cover.id' => 'required',
            'tests' => 'required',
        ];
        $validation = Validator::make($request->all(), $rules);

        if (!$validation->passes()) {
            return $this->helpers->apiArrayResponseBuilder(400, 'validation_failed', $validation->errors());
        }

        $project = $this->projectRepository->update($request);


        return $this->helpers->apiArrayResponseBuilder(200, 'success', $project->data);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {

        $project = $this->projectRepository->find($id);

        if (!empty($project->data)) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $project->data);
        }

        return $this->helpers->apiArrayResponseBuilder(404, 'Нет такого проекта', ['id' => $id]);
    }

    /**
     * Storing project from admin panel
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $rules = [
            'project' => 'required',
        ];
        $validation = Validator::make($request->all(), $rules);

        if (!$validation->passes()) {
            return $this->helpers->apiArrayResponseBuilder(400, 'validation_failed', $validation->errors());
        }


        $neProject = $this->projectRepository->create($request);
        if (!$neProject->saved) {
            $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'saving_error']);
        }

        return $this->helpers->apiArrayResponseBuilder(200, 'success', [
            'data' => 'ok',
            'type' => 'test_submit',
            'project' => $neProject->data
        ]);
    }

    /**
     * Set cover articles and license image
     */
    public function setImage($field, $type = 'articles'): JsonResponse
    {
        $attachment_type = self::ATTACHMENT_TYPE_ARTICLES;

        if ($type == 'test') {
            $attachment_type = self::ATTACHMENT_TYPE_TEST_QUESTIONS;
        } elseif
        ($type == 'project') {
            $attachment_type = self::ATTACHMENT_TYPE_PROJECT;
        } elseif
        ($type == 'banners') {
            $attachment_type = self::ATTACHMENT_TYPE_BANNERS;
        }
        elseif
        ($type == 'audience') {
            $attachment_type = self::ATTACHMENT_TYPE_CSV;
        }

        $file = new File;
        $file->data = \Illuminate\Support\Facades\Request::file('file');
        $file->is_public = true;
        $file->field = $field;
        $file->attachment_type = $attachment_type;
        $file->beforeSave();


        $arr = [
            'status_code' => 200,
            'message' => 'success',
            'data' => $file
        ];
        return response()->json($arr, 200);
        //return $this->helpers->apiArrayResponseBuilder(200, 'success', [$file]);
    }

    /**
     * Start the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function start(Request $request): JsonResponse
    {
        $model = Projects::findOrFail($request->id);
        $model->status = Projects::STATUS_ACTIVE;
        $model->save();

        //активируем все статьи проекта
        $this->setArticlesPublishedStatus($model, 1);

        return $this->helpers->apiArrayResponseBuilder(200, ['status' => Projects::STATUS_ACTIVE]);
    }

    private function setArticlesPublishedStatus($model, $status){
        foreach ($model->items as $item){
            foreach ($item->articles as $article){
                $article->published = $status;
                $article->save();
            }
        }
    }


    /**
     * Stop the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function stop($id): JsonResponse
    {
        $model = Projects::findOrFail($id);
        $model->status = Projects::STATUS_INACTIVE;
        $model->save();

        //инактивируем все статьи проекта
        $this->setArticlesPublishedStatus($model, 0);


        return $this->helpers->apiArrayResponseBuilder(200, ['status' => Projects::STATUS_INACTIVE]);
    }

    /**
     * Show agreement box
     * @param $id ProjectResearches::ID
     * @return JsonResponse
     */
    public function showAgreement($id)
    {
        $research = ProjectResearches::find($id);
        if (!$research) {
            $this->helpers->apiArrayResponseBuilder(404, 'no research', ['research_id' => $id]);
        }
        $agreement = $research->project->options['agreement'] ?? '';

        $data = [
            'agreement' => $agreement,
            'research_id' => (int)$id,
            'project_id' => (int)$research->project_id
        ];
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    /**
     * Accepts the agreement
     * @param $id ProjectResearches::ID
     * @return JsonResponse
     */
    public function acceptAgreement($id): JsonResponse
    {
        $research = ProjectResearches::find($id);
        if (!$research) {
            $this->helpers->apiArrayResponseBuilder(404, 'no research', ['research_id' => $id]);
        }

        $userModel = auth()->user();
        $agreement = new ProjectsAgreement();
        $agreement->user_id = $userModel->id;
        $agreement->project_id = $research->project_id;
        if (!$agreement->save() ){
            $this->helpers->apiArrayResponseBuilder(400, 'error', $agreement->toArray());
        }
        return $this->helpers->apiArrayResponseBuilder(200, 'success');
    }
    public function userStats(Request $request): JsonResponse
    {

        $project = $request->post('data');
        $total = 0;
        $users = 0;

        if ($audFile = $project['options']['files']['audience']) {

            $path = str_replace('/storage', 'app/public', $audFile['path']);
            $file = fopen(storage_path($path), 'r');

            while ($raw = fgetcsv($file)) {
                //    'name', 'phone', 'email'
                if ($raw[0] && $raw[1] && $raw[2]) {
                    $total++;
                    $user = User::findByPhone($raw[1]);
                    if ($user) {
                        $users++;
                    }
                }
            }
        }
        else {
            $total = $users = User::count();
        }
        return $this->helpers->apiArrayResponseBuilder(200, 'success', compact('total', 'users'));

    }
}
