<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Helpers;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use Auth;
use App\Models\Articles;
use App\Models\Opened;
use App\Models\PassingProvider;
use App\Models\TrackingProvider;

class BlogController extends Controller
{
    protected $Post;
    protected $helpers;

    public function __construct(Post $Post, Helpers $helpers)
    {
        $this->Post    = $Post;
        $this->helpers          = $helpers;
    }

    /**
     * Return posts
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){

        /*$isAuthenticated = Auth::check();

        $apiUser = Auth::getUser();*/

        $isAuthenticated = Auth::check();

        $apiUser = Auth::user();

        $countOnPage = 15;//get('count', 15);

        $type = Articles::ARTICLE;//get('type', Articles::ARTICLE);

        $relations = ['user'/*, 'featured_images','content_images'*/, 'cover_image','recommended.post', 'is_opened' => function($q) use ($apiUser) { $q->where('user_id', '=', $apiUser->id); } ];
        if (!isset($apiUser->id)) unset($relations['is_opened']);

        if ( $type == Articles::ARTICLE) {
            $data = Articles::with($relations)
                ->where( 'published_at', '<=', Carbon::now()
                    ->toDateTimeString())
                ->isArticle()
                ->orderBy('id', 'desc')
                ->limit($countOnPage)
                ->offset(0)
                ->get();
                //->paginate($countOnPage);
        } else {
            $data = Articles::with($relations)
                ->where( 'published_at', '<=', Carbon::now()
                    ->toDateTimeString())
                ->isInformation()
                ->orderBy('id', 'desc')
                ->limit($countOnPage)
                ->offset(0)
                ->get();
                //->paginate($countOnPage);
        }

        $data->makeHidden(['content']);


        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

        //if ($apiUser && $apiUser->hasAccess('news.access_news'))
    }

    /**
     * Counting of actions
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function callback($id){

        Post::find($id)->increment('callbacks');

        $post = Post::find($id);
        $images = $post->featured_images;

        return $this->helpers->apiArrayResponseBuilder(200, 'success', [$post->toArray()] + [$images]);

    }

    /**
     * Counting of readed blocks
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function read($articleId, $blockId) {

        //$apiUser = Auth::getUser();
        $apiUser = Auth::user();

        $tracking = new TrackingProvider($apiUser);
        $tracking->setBlockReaded($articleId, $blockId);

        $article = Post::findOrFail($articleId);

        $learningBonus = $article->learning_bonus;

        if ( is_array($content = $article->content)) {

            $lastBlock = key(array_slice($content, -1, 1, true));


            $passed = new PassingProvider($apiUser);
            $passedIds = $passed->getIds(Post::class);

            $userModel = User::find($apiUser->id);

            if (!in_array($articleId, $passedIds) && ($blockId == $lastBlock) && $tracking->isReadClosely($articleId)) {
                $userModel->balance = $userModel->balance + $learningBonus;
                $userModel->save();

                $passed->setId($article, $articleId);
            }

            $data = $userModel->toArray();

            foreach (['permissions', 'deleted_at', 'updated_at', 'activated_at'] as $v) {
                unset($data[$v]);
            }

            Post::find($articleId)->increment('callbacks');

            $response = ['user' => $data];

            if ( !in_array($articleId, $passedIds) && ( $blockId == $lastBlock) && $tracking->isReadClosely($articleId) )
                $response['reading_status'] = [
                    'points_earned' => $learningBonus
                ];


            return $this->helpers->apiArrayResponseBuilder(200, 'success', $response);
        }

        return $this->helpers->apiArrayResponseBuilder(500, 'invalid article', ['error' => 'invalid article']);



    }

    /**
     * Shows post by id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){

        Post::find($id)->increment('views');

        $data = Articles::with( [/*'featured_images','content_images',*/ 'cover_image','recommended.post','recommended.post.cover_image'] )->where( 'id', '=', $id)->get();


        if (!empty( $data)) {

            //$apiUser = Auth::getUser();
            $apiUser = Auth::user();

            $opened = new Opened();
            $opened->user_id = $apiUser->id;
            $opened->news_id = $id;
            $opened->save();

            $tracking = new TrackingProvider($apiUser);
            $tracking->setBlockReaded($id);

            return $this->helpers->apiArrayResponseBuilder(200, 'success', $data );
        }

        return $this->helpers->apiArrayResponseBuilder(404, 'not found', ['error' => 'invalid id']);

    }


    public static function getAfterFilters() {return [];}
    public static function getBeforeFilters() {return [];}
    //public static function getMiddleware() {return [];}
    /*public function callAction($method, $parameters=false) {
        return call_user_func_array(array($this, $method), $parameters);
    }*/

}
