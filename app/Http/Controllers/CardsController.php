<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Http\Requests\StoreCardsRequest;
use App\Models\UserCards;
use App\Services\UsersService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardsController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param null $id
     * @return JsonResponse
     */

    public function index(Request $request, $id = null): JsonResponse
    {
        $perPage = 15;
        if (!$id) {
            if($sortby = $request->input('sortby') )
            {
                $order = $request->input('order', 'asc');
                $page = Cards::orderBy($sortby, $order)->paginate($perPage);
                $page->appends(['sortby' => $sortby, 'order' => $order])->links();
            }else {
                $page = Cards::query()->paginate($perPage);
            }
            $page = json_decode($page->toJSON());
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $page);

        }


        $card = Cards::findOrFail($id)->toArray();
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $card);

    }

    public function buy($id)
    {

        $user = Auth::user();

        $card = Cards::find($id)->first();
        if (!$card) {
            return $this->helpers->apiArrayResponseBuilder(404, 'Немає такої карти', $id);
        }


        if ($user->balance < $card->cost) {
            return $this->helpers->apiArrayResponseBuilder(402, 'Не вистачає балів', $id);
        }

        $balance = $user->balance - $card->cost;

        $model = new UserCards();
        $model->user_id = $user->id;
        $model->card_id = $id;
        $model->is_active = UserCards::USER_CARDS_IS_ACTIVE;
        $model->cost = $card->cost;
        $model->save();

        UsersService::reduceBalance($user->id, $balance);

        return UserCards::where('user_id', $user->id)->paginate();

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

        return response()->json(compact('item', 'status'));
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

        return response()->json(compact('item', 'status'));
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

        return response()->json(compact('item', 'status'));
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
