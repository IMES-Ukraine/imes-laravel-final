<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Projects;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    protected $helpers;

    public function __construct(Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * @return JsonResponse
     */
    public function index() {
        $data = Projects::with('tags')->with('items')->whereNull('deleted_at')->with('items')->get();
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Projects::findOrFail($id);
        $model->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success');
    }
}
