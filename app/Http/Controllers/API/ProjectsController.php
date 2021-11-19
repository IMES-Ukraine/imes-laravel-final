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

    protected $helpers;
    protected $project;
    protected $projectItems;
    protected $projectRepository;

    /**
     * ProjectsController constructor.
     * @param Helpers $helpers
     */
    public function __construct(Helpers $helpers, Projects $project, ProjectItems $projectItems, ProjectRepository $projectRepository) {
        $this->helpers = $helpers;
        $this->project = $project;
        $this->projectItems = $projectItems;
        $this->projectRepository = $projectRepository;
    }

    /**
     * @return JsonResponse
     */
    public function index() {
        $data = Projects::with('tags')->whereNull('deleted_at')->with('items')->orderBy('created_at', 'DESC')->get();
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
    }

    public function getTests($id = null)
    {
        $data = [];
        $query = ProjectResearches::query();
        if ($id) {
            $query->where(['project_id' => $id]);
        }
        if (! request()->input('all_items', 0)){
            $query->where('schedule', '<=', date('Y-m-d H:i:s') );
        }

        $projectItems = $query->get();
        foreach ($projectItems as $key => $item){
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
    public function update( Request $request ) {

        $rules = [
            'options' => 'required',
            'options.title' => 'required',
            'articles' => 'required',
            'articles.*.images.cover.id' => 'required',
            'tests'    => 'required',
        ];
        $validation = Validator::make($request->all(), $rules);

        if ( !$validation->passes() ) {
            return $this->helpers->apiArrayResponseBuilder(400, 'validation_failed', $validation->errors() );
        }

        $project = $this->projectRepository->update( $request);


        return $this->helpers->apiArrayResponseBuilder(200, 'success', $project->data);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id) {

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

        if ( !$validation->passes() ) {
            return $this->helpers->apiArrayResponseBuilder(400, 'validation_failed', $validation->errors() );
        }


        $project = $this->projectRepository->create( $request);

        if ( !$project->saved ) {
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
    public function setImage($type){

        if ($type == 'articles') {
            $attachment_type = self::ATTACHMENT_TYPE_ARTICLES;
        }
        elseif ($type == 'test') {
            $attachment_type = self::ATTACHMENT_TYPE_TEST_QUESTIONS;
        }
        elseif ($type == 'project') {
            $attachment_type = self::ATTACHMENT_TYPE_PROJECT;
        }

        $file = new File;
        $file->data = \Illuminate\Support\Facades\Request::file('file');
        $file->is_public = true;
        $file->field = 'cover_image';
        $file->attachment_type = $attachment_type;
        $data = $file->beforeSave();

        //Storage::disk('public')->put('/project/', 'Contents');
        //$model_file = new File();
        //$model_file->fromPost($file->data);

        //$apiUser = Auth::user();

        /*$helper = new ImageHelper( $apiUser);
        $response = $helper->uploadImage( $type, $data);

        if( !$response){
            return $this->helpers->apiArrayResponseBuilder(401, 'error', []);
        }*/

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
        $model->options->status = Projects::STATUS_ACTIVE;
        $model->status = Projects::STATUS_ACTIVE;
        $model->save();

        /*$model->options->status = Projects::STATUS_ACTIVE;*/
        //$model->options->status = Projects::STATUS_ACTIVE;
        /*$model->forceFill([
            'options->status' => 'dd'
        ])->save();*/
        //print_r($model->options->status);
        //$result = $model->save();

        /*DB::table('ulogic_projects_settings')
            ->where('id', $request->id)
            ->update([
                'options->status' => Projects::STATUS_ACTIVE
            ]);*/

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
        $model->update(['status' => Projects::STATUS_INACTIVE]);

        return $this->helpers->apiArrayResponseBuilder(200, 'success');
    }

}
