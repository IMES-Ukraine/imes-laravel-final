<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Models\File;
use App\Models\ImageHelper;
use App\Models\ProjectItems;
use App\Models\ProjectResearches;
use App\Models\Tags;
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

    protected $helpers;
    protected $project;
    protected $projectItems;
    protected $projectRepository;

    /**
     * ProjectsController constructor.
     * @param Helpers $helpers
     */
    public function __construct(Helpers $helpers, Projects $project, ProjectItems $projectItems, ProjectRepository $projectRepository)
    {
        $this->helpers = $helpers;
        $this->project = $project;
        $this->projectItems = $projectItems;
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
//        foreach ($model->items as $item) {
//            $item->destroyData();
//            $item->delete();
//        }

        $model->update(['status' => Projects::STATUS_INACTIVE]);
        $model->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success');
    }

    /**
     * @return JsonResponse
     */
    public function tags(): JsonResponse
    {
        $data = Tags::all();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
    }

    public function getTests($id = null)
    {
        $data = [];
        $query = ProjectResearches::query();
        if ($id) {
            $query->where(['project_id' => $id]);
        }
        if (!request()->input('all_items', 0)) {
            $query->where('schedule', '<=', date('Y-m-d H:i:s'));
        }

        $projectItems = $query->get();
        foreach ($projectItems as $key => $item) {
            $data[$key] = (array)$item->test;
            $data[$key]['id'] = $item->id;
            $data[$key]['project_id'] = $item->project_id;
            $data[$key]['schedule'] = $item->schedule;

        }
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
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

        if (isset($project->data)) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $project->data);
        }

        return $this->helpers->apiArrayResponseBuilder(500, 'error');
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


        $project = $this->projectRepository->create($request);

        if (!$project->saved) {
            $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'saving_error']);
        }


        return $this->helpers->apiArrayResponseBuilder(200, 'success', [
            'data' => 'ok',
            'type' => 'test_submit',
            'project' => $project->data
        ]);
    }

    /**
     * Set cover articles and license image
     */
    public function setImage($field, $type = 'articles')
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
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request)
    {
        $model = Projects::findOrFail($request->id);
        $model->status = Projects::STATUS_ACTIVE;
        $model->save();

        return $this->helpers->apiArrayResponseBuilder(200, ['status' => Projects::STATUS_ACTIVE]);
    }

    /**
     * Stop the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function stop($id)
    {
        $model = Projects::findOrFail($id);
        $model->status = Projects::STATUS_INACTIVE;
        $model->save();

        return $this->helpers->apiArrayResponseBuilder(200, ['status' => Projects::STATUS_INACTIVE]);
    }

}
