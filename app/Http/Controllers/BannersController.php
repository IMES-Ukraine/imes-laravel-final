<?php

namespace App\Http\Controllers;

use App\Models\Banners;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class BannersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $model = Banners::query()->findOrFail(1);
        $post = $request->post();

        $model->image_id = $post['image']['id'];
        $model->url = $post['url'];
        $model->save();

        $item = Banners::query()->with('image')->where(['type' => $model->type])->get();

        return response()->json(compact('item') );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(string $type): JsonResponse
    {
        $item = Banners::query()->with('image')->where(['type' => $type])->get();
        return response()->json(compact('item'));
    }
}
