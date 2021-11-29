<?php
namespace App\Models;
/**
 * Created by PhpStorm.
 * User: Amso Rent
 * Date: 14/07/2019
 * Time: 3:51 PM
 */

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Query\Builder;

class Articles extends Post {
    const ARTICLE = 1;
    const INFORMATION = 2;

    const IS_INSTANT = 0;
    const IS_SCHEDULED = 1;




    public function scopeIsArticle($query) {
        return $query->where('type', self::ARTICLE);
    }

    public function scopeIsInformation($query) {
        return $query->where('type', self::INFORMATION);
    }

    public function scopeIsNotPassed( $query, $userId)
    {
        return $query->leftJoin('ulogic_projects_passing', 'ulogic_projects_passing.entity_id',  '=', 'rainlab_blog_posts.id')
            ->whereNotExists(function ($q) use ($userId){
                $q->select('ulogic_projects_passing.answer')
                    ->where('ulogic_projects_passing.entity_type', Post::class)
                  ->where('ulogic_projects_passing.user_id', $userId);
                });
    }

    public function scopeNotTimes( $query) {
        return $query->leftJoin('rainlab_blog_posts_times', 'rainlab_blog_posts_times.post_id', '=', 'rainlab_blog_posts.id')
            ->whereNull('rainlab_blog_posts_times.date');
    }




    /**
     * Image in list
     * @return mixed
     */
    public function cover_image()
    {
        return $this->belongsTo(File::class, 'cover_image_id', 'id');
    }

    public function is_opened()
    {
        return $this->hasMany(Opened::class, 'news_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Times articles
     * @return mixed
     */
    public function times()
    {
        return $this->hasOne('App\Models\PostTimes', 'post_id', 'id');
    }

    /**
     * Gallery articles
     * @return mixed
     */
    public function gallery()
    {
        return $this->hasMany('App\Models\PostGallery', 'post_id', 'id');
    }

    /**
     * Tags
     * @return mixed
     */
    public function tags()
    {
        return $this->hasOne(PostTag::class, 'post_id', 'id')->with('tags');
    }

    /**
     * Recomended articles
     * @return mixed
     */
    public function recommended()
    {
        return $this->hasMany(Recommended::class, 'parent_id', 'id')->with('post');
    }

}
