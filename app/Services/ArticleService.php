<?php
namespace App\Services;


use App\Models\File;
use App\Models\Articles;
use App\Models\Recommended;

class ArticleService
{
    public function addArticle( $article) {

        $recommended = $article['chosenRecommended'];
        $action = !empty($article['button']) ? $article['button'] : '';

        $model = new Articles();
        $model->title = $article['title'];
        $model->slug = uniqid();
        $model->published = true;
        $model->content_html = '';
        $model->type = $article['type'];
        $model->learning_bonus = $article['points'];
        $model->is_popular = rand(0,1);
        $model->action = $action;
        $model->button = $action;
        $model->user_id = $article['user_id'][0] ?? 0;

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

        //$file = File::find($article['cover_image']['id']);

//        $file = File::find($article['images']['cover']['id']);

        // !!!

        $model->cover_image = $article['media']['cover']['id'];

        //plugins_path() . '/ulogic/news/updates/news-featured.png';
        //$model->featured_images = $file;
        // !!!
        $model->published_at = time();
        $result = $model->save();

        if ( $result ){
            $this->attachRecommended( $recommended, $model);
            $file = File::find($article['media']['cover']['id'])->first();
            $file->attachment_id = $model->id;
            $model->save();
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

}
