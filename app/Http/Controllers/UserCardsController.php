<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\UserCards;
use App\Http\Requests\StoreUserCardsRequest;

class UserCardsController extends Controller
{
    protected $routePath = 'user_cards';
    protected $viewPath = 'user_cards';

    protected $helpers;

    public function __construct(Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdraws = UserCards::with('user')
            ->with('card')
            ->orderBy('created_at', 'desc')
            ->paginate();

        return $this->helpers->apiArrayResponseBuilder(
            200,
            'success',
            $withdraws);
        /*$page = UserCards::query()->paginate();
        return view("$this->viewPath.index", compact('page'));*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $item = new UserCards();
        $item->_token = csrf_token();
        $item->_uri = "/$this->routePath";
        return view("$this->viewPath.edit", compact('item'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserCardsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserCardsRequest $request)
    {
        $item = new UserCards;
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
        $item = UserCards::query()->findOrFail($id);
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
        $item = UserCards::query()->findOrFail($id);
        $item->_token = csrf_token();
        $item->_method = 'PATCH';
        $item->_uri = "/$this->routePath/$item->id";
        return view("$this->viewPath.edit", compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param StoreUserCardsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, StoreUserCardsRequest $request)
    {
        $item = UserCards::query()->findOrFail($id);
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
        UserCards::destroy($id);
        return redirect("/$this->routePath");
    }
}
