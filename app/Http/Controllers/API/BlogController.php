<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\PostTag;
use App\Models\PostTimes;
use App\Models\Recommended;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\Post;
use Auth;
use App\Models\Articles;
use App\Models\Opened;
use App\Models\PassingProvider;
use App\Models\TrackingProvider;
use Illuminate\Http\Response;

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
        $apiUser = auth()->user();


        $countOnPage = $request->get('count', self::COUNT_PER_PAGE);

        $type =  $request->get('type', Articles::ARTICLE);

        $relations = [ 'cover_image', /*'user', 'featured_images','content_images',*/
            /*, 'recommended.post',  'is_opened' => function($q) use ($apiUser) { $q->where('user_id', '=', $apiUser->id); }n */
        ];
        //if (!isset($apiUser->id)) unset($relations['is_opened']);

        if ($type == Articles::ARTICLE) {
            $data = Articles::with($relations)
                ->select('rainlab_blog_posts.*')
                ->where('rainlab_blog_posts.scheduled', '<=', date('Y-m-d H:i:s'))
                //->where( 'published_at', '<=', Carbon::now()
                //->toDateTimeString())
                ->isArticle()
                ->isProjectActive()
                ->isNotPassed($apiUser->id)
                ->orderBy('rainlab_blog_posts.id', 'desc')
                ->paginate($countOnPage);
        } else {
            $data = Articles::with($relations)
                ->select('rainlab_blog_posts.*')
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
    public function tags(): JsonResponse
    {
        $data = Tag::all();

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data->toArray());
    }

    /**
     * @return JsonResponse
     */
    public function times(): JsonResponse
    {
        $data = Articles::select(
            'rainlab_blog_posts.id',
            'rainlab_blog_posts.title',
            'rainlab_blog_posts.slug',
            'rainlab_blog_posts.excerpt',
            'rainlab_blog_posts.content',
            'rainlab_blog_posts.published_at',
            'rainlab_blog_posts.published',
            'rainlab_blog_posts.created_at',
            'rainlab_blog_posts.updated_at',
            'rainlab_blog_posts.user_id',
            'rainlab_blog_posts.content_html',
            'rainlab_blog_posts.action',
            'rainlab_blog_posts.button',
            'rainlab_blog_posts.type',
            'rainlab_blog_posts.learning_bonus',
            'rainlab_blog_posts.cover_image_id',
            'rainlab_blog_posts_times.date',
            'rainlab_blog_posts_times.time',
        )
            ->with('cover_image')
            ->with('is_opened')
//            ->with('gallery')
            ->with('featured_images')
            ->with('user')
            ->leftJoin('rainlab_blog_posts_times', 'rainlab_blog_posts_times.post_id', '=', 'rainlab_blog_posts.id')
            ->whereNotNull('rainlab_blog_posts_times.date')
            ->get();

        $data = $data->toArray();
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    /**
     * Counting of actions
     * @param $id
     * @return JsonResponse
     */

    public function callback($id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->increment('callbacks');

        $tracking = new TrackingProvider(auth()->user());
        $tracking->startReading($id);



        $images = $post->featured_images;

        return $this->helpers->apiArrayResponseBuilder(200, 'success', [$post->toArray()] + [$images]);
    }

    /**
     * Counting of readed blocks
     * @param $articleId
     * @param $blockId
     * @return JsonResponse
     */

    public function read($articleId, $blockId): JsonResponse
    {

        $userModel = auth()->user();

        $tracking = new TrackingProvider($userModel);
        $tracking->setBlockReaded($articleId, $blockId);

        $article = Post::findOrFail($articleId);

        $learningBonus = $article->learning_bonus;
        $finalBonus = 0;

        if (is_array($content = $article->content)) {

            $lastBlock = key(array_slice($content, -1, 1, true));

            $passed = new PassingProvider($userModel);
            $passedIds = $passed->getIds(Post::class);

            if (!in_array($articleId, $passedIds) && ($blockId == $lastBlock) && $tracking->isReadClosely($articleId)) {
                $userModel->balance = $userModel->balance + $learningBonus;
                $userModel->save();
                $finalBonus = $learningBonus;
                $passed->setId($article, $articleId);
            }

            $data = $userModel->makeHidden(['permissions', 'deleted_at', 'updated_at', 'activated_at'])->toArray();

            Post::find($articleId)->increment('callbacks');

            $response = [
                'user' => $data,
                'reading_status' => [
                    'points_earned' => $finalBonus
                ]
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
        $model = new Articles();
        $model->fill($request->post());

        $model->content_html = $request->excerpt;
        $model->cover_image_id = $request->cover_image['id'];
        $model->published = true;
        $model->learning_bonus = 0;
        $model->is_popular = rand(0, 1);
        $model->slug = uniqid();
        $model->published_at = time();
        if (!$model->button) {
            $model->button = '';
        }
        if (!$model->action) {
            $model->action = '';
        }

        $saveStatus = $model->save();


        if (!$saveStatus)
            $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => $model->errors]);

        if ($saveStatus) {
            foreach ($request->featured_images as $image) {
                $fileImage = File::find($image['id']);
                $fileImage->attachment_id = $model->id;
                $fileImage->save();
//                $model_gallery = new PostGallery();
//                $model_gallery->post_id = $model->id;
//                $model_gallery->cover_image = $value['path'];
//                $model_gallery->save();
            }

            foreach ($request->tags as $tag) {
                $model_tags = new PostTag();
                $model_tags->post_id = $model->id;
                $model_tags->tag_id = $tag['id'];
                $model_tags->save();
            }

            foreach ($request->recommended as $rec) {
                Recommended::create([
                    'parent_id' => $model->id,
                    'recommended_id' => $rec['id']
                ]);
            }

            if (isset($request->date) && isset($request->time)) {
                $model_times = new PostTimes();
                $model_times->post_id = $model->id;
                $model_times->date = date("Y-m-d", strtotime($request->date));
                $model_times->time = $request->time;
                $model_times->save();
            }
        }

        return response()->json(compact('saveStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $model = Post::findOrFail($id);

        $model->fill($request->post());

        $model->content_html = $request->excerpt;
        $model->cover_image_id = $request->cover_image['id'];
        $model->published = true;
        $model->learning_bonus = 0;
        $model->is_popular = rand(0, 1);
        $model->slug = uniqid();
        $model->published_at = time();
        if (!$model->button) {
            $model->button = '';
        }
        if (!$model->action) {
            $model->action = '';
        }

        $saveStatus = $model->save();

        if (!$saveStatus) {
            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => $model->errors]);
        }

        if ($saveStatus) {
            //Пока у нас изображения в галерее не удаляются
//            File::where('attachment_id', $model->id)->delete();
            if ($request->featured_images) {
                foreach ($request->featured_images as $image) {
                    $fileImage = File::find($image['id']);
                    $fileImage->attachment_id = $model->id;
                    $fileImage->save();
//                $model_gallery = new PostGallery();
//                $model_gallery->post_id = $model->id;
//                $model_gallery->cover_image = $value['path'];
//                $model_gallery->save();
                }
            }

            PostTag::where('post_id', $model->id)->delete();
            if ($request->tags) {
                foreach ($request->tags as $tag) {
                    $model_tags = new PostTag();
                    $model_tags->post_id = $model->id;
                    $model_tags->tag_id = $tag['id'];
                    $model_tags->save();
                }
            }

            Recommended::where('parent_id', $model->id)->delete();
            if ($request->recommended) {
                foreach ($request->recommended as $rec) {
                    Recommended::create([
                        'parent_id' => $model->id,
                        'recommended_id' => $rec['id']
                    ]);
                }
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
     * @return JsonResponse
     */
    public function show($id)
    {
        $data = Articles::select(
            'rainlab_blog_posts.id',
            'rainlab_blog_posts.title',
            'rainlab_blog_posts.slug',
            'rainlab_blog_posts.excerpt',
            'rainlab_blog_posts.content',
            'rainlab_blog_posts.published_at',
            'rainlab_blog_posts.published',
            'rainlab_blog_posts.created_at',
            'rainlab_blog_posts.updated_at',
            'rainlab_blog_posts.user_id',
            'rainlab_blog_posts.content_html',
            'rainlab_blog_posts.action',
            'rainlab_blog_posts.button',
            'rainlab_blog_posts.type',
            'rainlab_blog_posts.learning_bonus',
            'rainlab_blog_posts.cover_image_id',
        )
            ->with('cover_image')
            ->with('is_opened')
//            ->with('gallery')
            ->with('featured_images')
            ->where(['id' => $id])
            ->get();
        $data = $data->toArray();
        if (empty($data)) {
            return $this->helpers->apiArrayResponseBuilder(404, 'No article', ['id' => $id]);
        }
        $user = User::where(['id' => $data[0]['user_id']])->select('id', 'name')->first();
        $data[0]['user'] = $user;
        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $model = Post::findOrFail($id);
        $model->delete();

        return $this->helpers->apiArrayResponseBuilder(200, 'success');
    }

}
