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

    public function beforeSave()
    {
        //$this->content_html = self::formatHtml($this->content);
    }

    public function getContentAttribute($value)
    {
        return json_decode($value);
    }

    public function setContentAttribute($value)
    {
        return json_encode($value);
    }

    /**
     * Recomended articles
     * @return mixed
     */
    public function recommended()
    {
        return $this->hasMany('App\Models\Recommended', 'parent_id', 'id');
    }

    /**
     * Image in list
     * @return mixed
     */
    public function cover_image()
    {
        return $this->hasOne('App\Models\File', 'attachment_id', 'id');
    }

    public function is_opened()
    {
        return $this->hasMany('App\Models\Opened', 'news_id', 'id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    /*public $attachMany = [
        'featured_images' => ['System\Models\File', 'order' => 'sort_order'],
        'cover_image' => ['System\Models\File', 'order' => 'sort_order'],
        'content_images'  => ['System\Models\File']
    ];*/
}
