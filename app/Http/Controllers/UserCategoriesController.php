<?php

namespace App\Http\Controllers;

use App\Models\UserCategories;
use App\Http\Requests\StoreUserCategoriesRequest;

class UserCategoriesController extends Controller
{
    protected $routePath = 'user_categories';
    protected $viewPath = 'user_categories';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = UserCategories::query()->paginate();
        return view("$this->viewPath.index", compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new UserCategories();
        $item->_token = csrf_token();
        $item->_uri = "/$this->routePath";
        return view("$this->viewPath.edit", compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserCategoriesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserCategoriesRequest $request)
    {
        $item = new UserCategories;
        $item->fill($request->validated());
        $item->save();
        return redirect("/$this->routePath/$item->id");
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = UserCategories::query()->findOrFail($id);
        return view("$this->viewPath.show", compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = UserCategories::query()->findOrFail($id);
        $item->_token = csrf_token();
        $item->_method = 'PATCH';
        $item->_uri = "/$this->routePath/$item->id";
        return view("$this->viewPath.edit", compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param StoreUserCategoriesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, StoreUserCategoriesRequest $request)
    {
        $item = UserCategories::query()->findOrFail($id);
        $item->update($request->validated());
        return redirect("/$this->routePath/$item->id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UserCategories::destroy($id);
        return redirect("/$this->routePath");
    }
}
