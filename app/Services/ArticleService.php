<?php
namespace App\Services;


use App\Models\File;
use App\Models\Articles;
use App\Models\Recommended;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleService
{
    public function addArticle( $article) {

        $recommended = $article['recommended'];
        $action = !empty($article['button']) ? $article['button'] : '';

        $model = new Articles();
        $model->title = $article['title'];
        $model->author2 = $article['author2'];
        $model->slug = uniqid();
        $model->published = true;
        $model->content_html = '';
        $model->type = $article['type'];
        $model->learning_bonus = $article['points'];
        $model->is_popular = rand(0,1);
        $model->action = $action;
        $model->button = $action;
        $model->user_id = $article['author']['id'] ?? 0;
        $model->amount = $article['count'];

        $content = [
            [
                'type' => 'text',
                'title' => null,
                'content' => $article['text']
            ],
        ];

        foreach ( $article['insert'] as $insert) {
            if ( !empty( $insert['content'])) {
                $content[] = [
                    'type' => 'text',
                    'title' => $insert['title'],
                    'content' => $insert['content']
                ];
            }

        }
        $model->content = $content;
        $model->cover_image_id = $article['cover']['id'] ?? null;
        $model->published_at = time();
        $result = $model->save();

        if ( $result ){
            $this->attachRecommended( $recommended, $model);
            $file = File::find($article['cover']['id']);
            if ($file) {
                $file->attachment_id = $model->id;
                $file->save();
            }
        }

        return $model;
    }

    /**
     * Save recommended for post
     * @param $recommended
     * @param $destinationModel
     */
    public function attachRecommended( $recommended, $destinationModel) {
        foreach ( $recommended as $rec){
            Recommended::create([
                'parent_id' => $destinationModel->id,
                'recommended_id' => $rec['id']
            ]);
        }
    }

    /**
     * Return pluck ID for Article
     * @param $content_id
     * @return array
     */
    public static function pluckIDArticles($content_id) {
        if (! is_array($content_id)){
            $content_id = [$content_id];
        }
        return Articles::select('id')
            ->whereIn('research_id', $content_id)
            ->pluck('id')->toArray();
    }

    /**
     * @param $model
     * @param Request $request
     * @return mixed
     */
    public static function fillPost($model, Request $request)
    {
        $model->fill($request->post());

        $model->content_html = $request->excerpt;
        $model->cover_image_id = $request->cover_image['id'] ?? null;
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

        return $model;
    }
}
