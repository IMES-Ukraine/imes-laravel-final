<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'bedard_blogtags_post_tag';

    /**
     * Tag
     * @return mixed
     */
    public function tag()
    {
        return $this->hasOne(Tag::class, 'id', 'tag_id');
    }
}
