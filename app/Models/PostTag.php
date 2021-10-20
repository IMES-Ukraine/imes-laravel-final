<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'bedard_blogtags_post_tag';

    /**
     * Tags
     * @return mixed
     */
    public function project_tags()
    {
        return $this->hasOne('App\Models\Tag', 'id', 'tag_id');
    }
}
