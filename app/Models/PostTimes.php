<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTimes extends Model
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
    public $table = 'rainlab_blog_posts_times';
}
