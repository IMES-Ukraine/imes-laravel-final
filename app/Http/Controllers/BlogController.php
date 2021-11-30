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
    }

    /**
     * Return posts
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $countOnPage = $request->get('count', self::COUNT_PER_PAGE);

        $type =  $request->get('type', Articles::ARTICLE);

        $relations = [ 'cover_image', /*'user', 'featured_images','content_images',*/
            /*, 'recommended.post',  'is_opened' => function($q) use ($apiUser) { $q->where('user_id', '=', $apiUser->id); }n */
        ];

        if ($type == Articles::ARTICLE) {
            $data = Articles::with($relations)
                ->select('rainlab_blog_posts.*')
                ->where('rainlab_blog_posts.scheduled', '<=', date('Y-m-d H:i:s'))
                ->whereNull('research_id')
                //->where( 'published_at', '<=', Carbon::now()
                //->toDateTimeString())
                ->isArticle()
                ->orderBy('rainlab_blog_posts.id', 'desc')
                ->paginate($countOnPage);
        } else {
            $data = Articles::with($relations)
                ->select('rainlab_blog_posts.*')
                ->whereNull('research_id')
                //->where( 'published_at', '<=', Carbon::now()
                //->toDateTimeString())
                ->isInformation()
                ->notTimes()
                ->orderBy('rainlab_blog_posts.id', 'desc')
                ->paginate($countOnPage);
        }

//        $data->makeHidden(['content']);
        $data = json_decode($data->toJSON());
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }
}
