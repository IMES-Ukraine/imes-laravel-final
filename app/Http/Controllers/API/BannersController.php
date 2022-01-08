<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Helpers;
use App\Models\Banners;

class BannersController extends Controller
{
    protected $helpers;

    public function __construct(Helpers $helpers)
    {
        $this->helpers = $helpers;
    }

    public function show($type)
    {
        $result = Banners::find(1)->first();
//        $result = Banners::where('type', $type)->with('image')->first()->toArray();

        $data = [
            'image' => $result->image->path ?? '',
            'url' => $result->url
        ];

        if (count($data) > 0) {
            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
        }

        return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => 'invalid type']);

    }
}
