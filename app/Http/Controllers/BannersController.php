<?php

namespace App\Http\Controllers;

use App\Models\Banners;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
        $item = Banners::query()->findOrFail(1);
        $image = '';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners');
            $image = '/' . $path;
        }

        $status = $item->update([
            'image' => $image,
            'url' => $request->url
        ]);

        $item = Banners::query()->where(['type' => $item->type])->get();

        return response()->json(compact('item', 'status') );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(string $type): JsonResponse
    {
        $item = Banners::query()->where(['type' => $type])->get();
        return response()->json(compact('item'));
    }
}
