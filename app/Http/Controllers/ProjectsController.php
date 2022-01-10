<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Projects;
use App\Models\Tags;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request) {
        $countOnPage = 15;
        $query = Projects::with('tags')->with('items')->whereNull('deleted_at')->with('items');

        if ($request->post('tag')) {
            $pluck_tag = Tags::where('name', $request->post('tag'))->pluck('id')->toArray();
            $query->tag($pluck_tag);
        }

        $result = $query->orderBy('created_at', 'DESC')->paginate($countOnPage);
        $data = json_decode($result->toJSON());
        //Delete bad tags if empty collection
        if($result->total() == 0 && $request->post('tag') && $request->post('tag') != ''){
            DB::table('tags')->where('slug', $request->post('tag'))->delete();
        }
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Projects $model */
        $model = Projects::findOrFail($id);
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


}
