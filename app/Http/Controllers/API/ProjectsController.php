<?php
namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Models\File;
use App\Models\ImageHelper;
use App\Models\ProjectItems;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Repository\ProjectRepository;
use App\Models\Projects;
use Illuminate\Support\Facades\Storage;

class ProjectsController extends Controller
{
    const ATTACHMENT_TYPE_ARTICLES = 'App\Models\Articles';
    const ATTACHMENT_TYPE_TEST_QUESTIONS = 'App\Models\TestQuestions';

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
        $data = Projects::with('tags')->with('items')->get();
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
    }

    /**
     * @return JsonResponse
     */
    public function tags() {
        $data = Tag::all();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
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
    public function show( $id) {

        $project = $this->projectRepository->find( $id);

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
    public function create(Request $request) {

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
        } elseif ($type == 'test') {
            $attachment_type = self::ATTACHMENT_TYPE_TEST_QUESTIONS;
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

}
