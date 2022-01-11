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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class Articles extends Post
{
    const ARTICLE = 1;
    const INFORMATION = 2;

    const IS_INSTANT = 0;
    const IS_SCHEDULED = 1;

    protected $appends = ['isAgreementAccepted', 'isOpened', 'isCommercial',
        'projectId', 'test_id', 'summary', 'has_summary', 'tags', 'recommended',
        'author'
    ];

    public function getAuthorAttribute()
    {
        return $this->author2;
    }

    public function getIsAgreementAcceptedAttribute()
    {
        $userModel = auth()->user();
        if ($userModel && $this->research) {
            return (bool)ProjectsAgreement::where(['user_id' => $userModel->id])->where(['project_id' => $this->research->project_id])->count();
        }
        return false;
    }

    public function getIsOpenedAttribute()
    {
        $apiUser = auth()->user();
        if ($apiUser) {
            return (bool)Opened::where(['user_id' => $apiUser->id])->where(['news_id' => $this->id])->count();
        }
        return false;
    }

    public function getTestIdAttribute()
    {
        if ($this->research_id && $test = TestQuestions::where(['research_id' => $this->research_id])->first()) {
            return (int)$test->id;
        }
        return null;
    }

    public function getIsCommercialAttribute(): bool
    {
        return !empty($this->research);
    }

    public function getProjectIdAttribute()
    {
        return $this->research ? $this->research->project_id : null;
    }


    public $preview = null;

    public function getTagsAttribute()
    {
        $tagList = PostTag::where(['post_id' => $this->id])->get();
        $tags = [];
        foreach ($tagList as $tagItem) {
            $tags[] = $tagItem->tag;
        }
        return $tags;
    }

    public function getRecommendedAttribute()
    {
        $recList = Recommended::where(['parent_id' => $this->id])->get();
        $recommended = [];
        foreach ($recList as $recItem) {
            if ($recItem->post) {
                $recommended[] = [
                    'parent_id' => $this->id,
                    'recommended_id' => $recItem->post->id,
                    'title' => $recItem->post->title,
                    'post' => $recItem->article,
                ];
            }
        }
        return $recommended;
    }

    /**
     * Used by "has_summary", returns true if this post uses a summary (more tag).
     * @return boolean
     */
    public function getHasSummaryAttribute()
    {
        $more = Config::get('rainlab.blog::summary_separator', '<!-- more -->');
        $length = Config::get('rainlab.blog::summary_default_length', 600);

        return (
        (bool) strlen(trim($this->excerpt)) ||
            strpos($this->content_html, $more) !== false ||
            strlen(strip_tags($this->content_html)/*Html::strip($this->content_html)*/) > $length
        );
    }


    public function scopeAudience($query, $user)
    {
      return $query->where(function ($q) use ($user) {
            return $q->whereJsonContains('ulogic_projects_settings.audience', $user->id)
                ->orWhereNull('rainlab_blog_posts.research_id');
        });
    }

    public function scopeQuota($query)
    {
        return $query->where(function($q) {
           $q->whereColumn('rainlab_blog_posts.passed', '<', 'rainlab_blog_posts.amount')
           ->orWhere('rainlab_blog_posts.amount', '<', 1);
        });
    }

    public function scopeIsArticle($query)
    {
        return $query->where('type', self::ARTICLE);
    }

    public function scopeIsInformation($query)
    {
        return $query->where('type', self::INFORMATION);
    }


    public function scopeNotTimes($query)
    {
        return $query->leftJoin('rainlab_blog_posts_times', 'rainlab_blog_posts_times.post_id', '=', 'rainlab_blog_posts.id')
            ->whereNull('rainlab_blog_posts_times.date');
    }

    public function scopeCheckCommercial($query, $user)
    {
        if(!$user->is_verified) {
            $query->whereNull('rainlab_blog_posts.research_id');
        }
        return $query;
    }

    public function scopeIsProjectActive( $query)
    {
        return $query->where(function ($q) {
            $q->where('ulogic_projects_settings.status', 'LIKE', Projects::STATUS_ACTIVE)
            ->orWhereNull('rainlab_blog_posts.research_id');
        });
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
     * Tags
     * @return mixed
     */
    public function tags()
    {
        return $this->hasOne(PostTag::class, 'post_id', 'id')->with('tags');
    }

    /**
     * File file
     * @return mixed
     */
    public function featured_images()
    {
        return $this->hasMany(File::class, 'attachment_id', 'id')
            ->where('field', 'LIKE', File::FIELD_FEATURED)->orWhere('field', 'LIKE', File::FIELD_ARTICLE);
    }

    /**
     * Project
     * @return mixed
     */
    public function research()
    {
        return $this->hasOne(ProjectResearches::class, 'id', 'research_id');
    }


    public function getProject()
    {
        $research = $this->research;
        return $research ? Projects::find($research->project_id) : null;
    }

    public static function setArticleAttachment($images, $article_id)
    {
        foreach ($images as $featured) {
            $img = File::find($featured['id']);
            if ($img) {
                $img->attachment_id = $article_id;
                $img->field = File::FIELD_FEATURED;
                $img->save();
            }
        }
    }



    /**
     * Recomended articles
     * @return mixed
     */
//    public function recommended()
//    {
//        return $this->hasMany(Recommended::class, 'parent_id', 'id')->with('post');
//    }

}
