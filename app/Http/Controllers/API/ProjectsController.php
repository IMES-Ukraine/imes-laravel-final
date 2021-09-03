<?php
namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Models\ProjectItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Repository\ProjectRepository;
use App\Models\Projects;


class ProjectsController extends Controller
{

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $data = Projects::all();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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

}
