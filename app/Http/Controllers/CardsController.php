<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Http\Requests\StoreCardsRequest;
use Illuminate\Http\JsonResponse;

class CardsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $page = Cards::query()->paginate();
        return response()->json(compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCardsRequest $request
     * @return JsonResponse
     */
    public function store(StoreCardsRequest $request): JsonResponse
    {
        $item = new Cards;
        $item->fill($request->validated());
        $status = $item->save();
        return response()->json(compact('item', 'status'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $item = Cards::query()->findOrFail($id);
        return response()->json(compact('item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param StoreCardsRequest $request
     * @return JsonResponse
     */
    public function update(int $id, StoreCardsRequest $request): JsonResponse
    {
        $item = Cards::query()->findOrFail($id);
        $status = $item->update($request->validated());

        return response()->json(compact('item', 'status') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param StoreCardsRequest $request
     * @return JsonResponse
     */
    public function enable(int $id, StoreCardsRequest $request): JsonResponse
    {
        $item = Cards::query()->findOrFail($id);
        $status = $item->update(['is_active' => true]);

        return response()->json(compact('item', 'status') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @param StoreCardsRequest $request
     * @return JsonResponse
     */
    public function disable(int $id, StoreCardsRequest $request): JsonResponse
    {
        $item = Cards::query()->findOrFail($id);
        $status = $item->update(['is_active' => false]);

        return response()->json(compact('item', 'status') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $count = Cards::destroy($id);
        if ($count) {
            return response()->json('OK');
        }
        return response()->json('Resource already deleted!', 410);
    }

}
