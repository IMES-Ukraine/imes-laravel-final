<?php

namespace App\Http\Controllers;

use App\Http\Helpers;
use App\Models\Articles;
use App\Models\Post;
use App\Models\User;
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
            ->notTimes()
            ->orderBy('rainlab_blog_posts.id', 'desc')
            ->paginate($countOnPage);


//        $data->makeHidden(['content']);
        $data = json_decode($data->toJSON());
//        dd($data);
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    public function list(Request $request): JsonResponse
    {


        $relations = ['cover_image'];
        $data = Articles::with($relations)
            ->select('rainlab_blog_posts.id', 'rainlab_blog_posts.title')
            ->whereNull('research_id')
            ->notTimes()
            ->orderBy('rainlab_blog_posts.id', 'desc')
            ->paginate($request->get('count'))->toArray();

        //$data = Post::select('id', 'title')->paginate($request->get('count'))->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    /**
     * Shows post by id
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        /** @var Articles $article */
        $article = Articles::select('rainlab_blog_posts.*')
            ->with('cover_image')
            ->with('featured_images')
            ->where(['id' => $id])
            ->first();

        if (!$article) {
            return $this->helpers->apiArrayResponseBuilder(404, 'No article', ['id' => $id]);
        }

        $research = $article->research;
        $project = $article->getProject();

        $data = $article->toArray();

        $user = User::where(['id' => $article->user_id])->select('id', 'name')->first();
        $data['user'] = $user;

        $data['agreement'] = $project ? $project->options['agreement'] : '';
        $data['isCommercial'] = !empty($research);
        $data['project_id'] = $research ? $research->project_id : null;

        return $this->helpers->apiArrayResponseBuilder(200, 'success', [$data]);
    }
}
