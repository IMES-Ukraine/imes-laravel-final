<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Articles;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    const COUNT_PER_PAGE = 15;
    protected $Post;
    protected $helpers;

    public function __construct(Post $Post, Helpers $helpers)
    {
        $this->Post = $Post;
        $this->helpers = $helpers;
        parent::__construct($helpers);
    }

    /**
     * Return posts
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $countOnPage = $request->get('count', self::COUNT_PER_PAGE);

        $relations = ['cover_image'];

        $data = Articles::with($relations)
            ->select('rainlab_blog_posts.*')
            ->whereNull('research_id')
            //->where( 'published_at', '<=', Carbon::now()
            //->toDateTimeString())
            ->orderBy('rainlab_blog_posts.id', 'desc')
            ->paginate($countOnPage);


//        $data->makeHidden(['content']);
        $data = json_decode($data->toJSON());
//        dd($data);
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function list(Request $request): JsonResponse
    {

        $data = Post::select('id', 'title')->paginate($request->get('count'))->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }
}
