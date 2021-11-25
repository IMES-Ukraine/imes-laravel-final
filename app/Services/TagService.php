<?php
namespace App\Services;

use App\Models\ProjectTags;
use App\Models\Tags;

class TagService
{
    public static function addProjectTag($project_id, $tag) {

        if ($first_tag = Tags::where('name', $tag)->first()) {
            $tag_id = $first_tag->id;
        } else {
            $model_tag = new Tags();
            $model_tag->name = $tag;
            $model_tag->slug = str_slug($tag);
            $model_tag->save();
            $tag_id = $model_tag->id;
        }

        $model = new ProjectTags();
        $model->tag_id = $tag_id;
        $model->project_id = $project_id;
        $model->save();
    }
}
