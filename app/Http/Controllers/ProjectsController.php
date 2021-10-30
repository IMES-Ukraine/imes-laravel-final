<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Projects;

class ProjectsController extends Controller
{
    protected $helpers;

    public function __construct(Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Projects::destroy($id);
        return $this->helpers->apiArrayResponseBuilder(200, 'success');
        //return redirect("/");
    }
}
