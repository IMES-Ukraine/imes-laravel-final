<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Passing;
use App\Models\PostTag;
use App\Models\PostTimes;
use App\Models\Recommended;
use App\Models\Tag;
use App\Models\User;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Helpers;
use App\Models\Post;
use App\Models\Articles;
use App\Models\Opened;
use App\Models\PassingProvider;
use App\Models\TrackingProvider;
use Illuminate\Support\Facades\Log;


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

        $type = $request->get('type', Articles::ARTICLE);

        $relations = ['cover_image'];


        $passed = Passing::where('entity_type', Post::class)->where('user_id', $apiUser->id)->where('result', 1)->pluck('entity_id')->toArray();

        $data = Articles::with($relations)
            ->select('rainlab_blog_posts.*')
            ->quota()
            ->whereNotIn('rainlab_blog_posts.id', $passed)
            ->where('published', 1)
            ->where('rainlab_blog_posts.scheduled', '<=', date('Y-m-d H:i:s'))
            ->leftJoin('project_researches', 'project_researches.id', '=', 'rainlab_blog_posts.research_id')
            ->leftJoin('ulogic_projects_settings', 'ulogic_projects_settings.id', '=', 'project_researches.project_id')
            ->isProjectActive()
            ->checkCommercial($apiUser)
            ->audience($apiUser)
            ->orderBy('rainlab_blog_posts.id', 'desc');

        //  Выдаём статьи всех типов
        if ($type == Articles::ARTICLE) {
            $data->isArticle();
        } else {
            $data->isInformation()
                ->notTimes();
        }
        $data = $data->paginate($countOnPage);

        $data->setCollection($data->makeHidden(['research', 'content']));

        $data = json_decode($data->toJSON());

        return $this->helpers->apiArrayResponseBuilder(200, 'success', $data);

        //if ($apiUser && $apiUser->hasAccess('news.access_news'))
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
        $passed = new PassingProvider(auth()->user());
        $passed->setId($post );

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

        /** @var User $userModel */
        $userModel = auth()->user();

        $tracking = new TrackingProvider($userModel);
        $tracking->setBlockReaded($articleId, $blockId);

        //TODO везде перейти на класс Articles
        $article = Post::findOrFail($articleId);

        $learningBonus = $article->learning_bonus;
        $finalBonus = 0;

        if (is_array($content = $article->content)) {

            $lastBlock = key(array_slice($content, -1, 1, true));

            $passed = new PassingProvider($userModel);
            $passedIds = $passed->getResults(Post::class);


            if (!in_array($articleId, $passedIds) && ($blockId == $lastBlock) && $tracking->isReadClosely($article)) {
                $userModel->addBalance($learningBonus, $article);
                $article->increment('passed');
                $finalBonus = $learningBonus;
                $passed->setResult($article);
            }

            $data = $userModel->makeHidden(User::NOT_SHOW)->toArray();

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
        $model = ArticleService::fillPost($model, $request);
        $saveStatus = $model->save();


        if (!$saveStatus) {
            $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => $model->errors]);
        }
        if ($request->featured_images) {
            Articles::setArticleAttachment($request->featured_images, $model->id);
        }

        $file = File::find($model->cover_image_id);
        if ($file) {
            $file->attachment_id = $model->id;
            $file->save();
        }
        if ($request->tags) {
            foreach ($request->tags as $tag) {
                $model_tags = new PostTag();
                $model_tags->post_id = $model->id;
                $model_tags->tag_id = $tag['id'];
                $model_tags->save();
            }
        }
        if ($request->recommended) {
            foreach ($request->recommended as $rec) {
                Recommended::create([
                    'parent_id' => $model->id,
                    'recommended_id' => $rec['id']
                ]);
            }
        }

        if (isset($request->date) && isset($request->time)) {
            $model_times = new PostTimes();
            $model_times->post_id = $model->id;
            $model_times->date = date("Y-m-d", strtotime($request->date));
            $model_times->time = $request->time;
            $model_times->save();
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

        $model = ArticleService::fillPost($model, $request);
        $saveStatus = $model->save();

        if (!$saveStatus) {
            return $this->helpers->apiArrayResponseBuilder(400, 'bad request', ['error' => $model->errors]);
        }

        if ($saveStatus) {
            Articles::setArticleAttachment($request->featured_images, $model->id);

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
        $authUser = auth()->user();
        //$authUser = User::where('id',413)->first();

        $article = Articles::where('id', $id)
            ->with('cover_image')
            ->with('featured_images')
            ->first();

        if (!$article) {
            return $this->helpers->apiArrayResponseBuilder(404, 'No article', ['id' => $id]);
        }


        if (!$article->isOpened) {
            $model = new Opened();
            $model->user_id = $authUser->id;
            $model->news_id = $id;
            $model->save();
        }

        $research = $article->research;
        $article->makeHidden('research');
        $data = $article->toArray();

        if ($research) {
            $test = $research->testObject;
            if ($test) {
                $passing = new PassingProvider($authUser);
                $passedTests = $passing->getResults($test);
                if (in_array($test->id, $passedTests)) {
                    $data['test_id'] = null;
                }
            }
        }


//        $user = User::where(['id' => $article->user_id])->select('id', 'name')->first();
        $data['user'] = $authUser;
        return $this->helpers->apiArrayResponseBuilder(200, 'success', [$data]);
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
