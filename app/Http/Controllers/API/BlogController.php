<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\File;
use App\Models\PostGallery;
use App\Models\PostTag;
use App\Models\PostTimes;
use App\Models\Recommended;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
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



        //$apiUser = Auth::user();

        $countOnPage = 15;//get('count', 15);

        $type = Articles::ARTICLE;//get('type', Articles::ARTICLE);

        $relations = ['user'/*, 'featured_images','content_images'*/, 'cover_image','recommended.post'/*, 'is_opened' => function($q) use ($apiUser) { $q->where('user_id', '=', $apiUser->id); }*/ ];
        //if (!isset($apiUser->id)) unset($relations['is_opened']);

        if ( $type == Articles::ARTICLE) {
            $data = Articles::with($relations)
                //->where( 'published_at', '<=', Carbon::now()
                    //->toDateTimeString())
                ->isArticle()
                ->orderBy('id', 'desc')
                ->paginate();
        } else {
            $data = Articles::with($relations)
                //->where( 'published_at', '<=', Carbon::now()
                    //->toDateTimeString())
                ->isInformation()
                ->orderBy('id', 'desc')
                ->paginate();
        }

        //$data->makeHidden(['content']);
$data = json_decode($data->toJSON() );
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

        //if ($apiUser && $apiUser->hasAccess('news.access_news'))
    }

    public function list(): JsonResponse
    {
        $data = Articles::select('id', 'title')->get()->toArray();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    /**
     * @return JsonResponse
     */
    public function tags() {
        $data = Tag::all();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
    }

    /**
     * @return JsonResponse
     */
    public function times() {
        $data = Articles::select(
            'rainlab_blog_posts.id',
            'rainlab_blog_posts.user_id',
            'rainlab_blog_posts.title',
            'rainlab_blog_posts.content',
            'rainlab_blog_posts.content_html',
            'rainlab_blog_posts.cover_image',
            'rainlab_blog_posts.action',
            'rainlab_blog_posts.button',
            'rainlab_blog_posts.type',
            'rainlab_blog_posts_times.date',
            'rainlab_blog_posts_times.time',
        )
            ->with('gallery')
            ->with('tags')
            ->with('user')
            ->with('recommended')
            ->leftJoin('rainlab_blog_posts_times', 'rainlab_blog_posts_times.post_id', '=', 'rainlab_blog_posts.id')
            ->whereNotNull('rainlab_blog_posts_times.date')
            ->get();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $model = new Post();
        $model->title = $request->title;
        $model->slug = uniqid();
        $model->published = true;
        $model->content_html = $request->text;
        $model->type = $request->articleType;
        $model->learning_bonus = 0;
        $model->is_popular = rand(0,1);

        $model->action = !empty($request->action) ? $request->action : '';
        $model->button = !empty($request->button) ? $request->button : '';

        if ($request->user) {
            $model->user_id = $request->user['id'];
        }

        $content = [];
        $content[] = [
            'type' => 'text',
            'title' => $request->content_title,
            'content' => $request->content_text
        ];

        $model->content = json_encode($content);
        $model->cover_image = $request->cover_image;

        $model->published_at = time();
        $saveStatus = $model->save();

        if ( !$saveStatus)
            $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => $model->errors]);

        if ( $saveStatus ){
            foreach ( $request->gallery as $value){
                $model_gallery = new PostGallery();
                $model_gallery->post_id = $model->id;
                $model_gallery->cover_image = $value['path'];
                $model_gallery->save();
            }

            foreach ( $request->tags as $tag){
                $model_tags = new PostTag();
                $model_tags->post_id = $model->id;
                $model_tags->tag_id = $tag['id'];
                $model_tags->save();
            }

            foreach ( $request->recommended as $rec){
                Recommended::create([
                    'parent_id' => $model->id,
                    'recommended_id' => $rec['id']
                ]);
            }

            if (isset($request->date) && isset($request->time)) {
                $model_times = new PostTimes();
                $model_times->post_id = $model->id;
                $model_times->date = date("Y-m-d", strtotime($request->date));
                $model_times->time = date("H:m:s", strtotime($request->time));
                $model_times->save();
            }
        }

        return response()->json(compact('saveStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $model = Post::findOrFail($request->post_id);
        $model->title = $request->title;
        $model->published = true;
        $model->content_html = $request->text;
        $model->type = $request->articleType;

        $model->action = !empty($request->action) ? $request->action : '';
        $model->button = !empty($request->button) ? $request->button : '';

        if ($request->user) {
            $user_id = null;
            if (isset($request->user) && $request->user) {
                $user_id = $request->user;
            } else {
                $user_id = $request->active_user_id;
            }
            $model->user_id = $user_id;
        }

        $content = [];
        $content[] = [
            'type' => 'text',
            'title' => $request->content_title,
            'content' => $request->content_text
        ];

        $model->content = json_encode($content);
        $model->cover_image = $request->cover_image;

        $saveStatus = $model->save();

        if ( !$saveStatus)
            $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => $model->errors]);

        if ( $saveStatus ){
            PostGallery::where('post_id', $model->id)->delete();
            foreach ( $request->gallery as $value){
                $model_gallery = new PostGallery();
                $model_gallery->post_id = $model->id;
                $model_gallery->cover_image = $value['path'];
                $model_gallery->save();
            }

            PostTag::where('post_id', $model->id)->delete();
            foreach ( $request->tags as $tag){
                $model_tags = new PostTag();
                $model_tags->post_id = $model->id;
                $model_tags->tag_id = $tag['id'];
                $model_tags->save();
            }

            Recommended::where('parent_id', $model->id)->delete();
            foreach ( $request->recommended as $rec){
                Recommended::create([
                    'parent_id' => $model->id,
                    'recommended_id' => $rec['id']
                ]);
            }

            if (isset($request->date) && isset($request->time)) {
                $model_times = PostTimes::where('post_id', $model->id);
                $date = date("Y-m-d", strtotime($request->date));
                $model_times->update(['date' => $date, 'time' => $request->time]);
            }
        }

        return response()->json(compact('saveStatus'));
    }

    /**
     * Shows post by id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id){
        $data = Articles::select(
            'rainlab_blog_posts.id',
            'rainlab_blog_posts.user_id',
            'rainlab_blog_posts.title',
            'rainlab_blog_posts.content',
            'rainlab_blog_posts.content_html',
            'rainlab_blog_posts.cover_image',
            'rainlab_blog_posts.action',
            'rainlab_blog_posts.button',
            'rainlab_blog_posts.type',
            )
            ->with('gallery')
            ->with('tags')
            ->with('user')
            ->with('recommended')
            ->where(['id' => $id])
            ->get();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Post::findOrFail($id);
        $model->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success');
    }

}
