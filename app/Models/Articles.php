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
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class Articles extends Post
{
    const ARTICLE = 1;
    const INFORMATION = 2;

    const IS_INSTANT = 0;
    const IS_SCHEDULED = 1;

    protected $appends = ['isAgreementAccepted', 'isOpened', 'isCommercial',
        'projectId', 'test_id', 'summary', 'has_summary', 'tags', 'recommended'
    ];

    public function getIsAgreementAcceptedAttribute()
    {
        $userModel = auth()->user();
        if ($userModel) {
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
                    'id' => $recItem->post->id,
                    'title' => $recItem->post->title
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
            !!strlen(trim($this->excerpt)) ||
            strpos($this->content_html, $more) !== false ||
            strlen(strip_tags($this->content_html)/*Html::strip($this->content_html)*/) > $length
        );
    }


    public function scopeIsArticle($query)
    {
        return $query->where('type', self::ARTICLE);
    }

    public function scopeIsInformation($query)
    {
        return $query->where('type', self::INFORMATION);
    }

    public function scopeIsNotPassed($query, $userId)
    {
        return $query->leftJoin('ulogic_projects_passing', 'ulogic_projects_passing.entity_id', '=', 'rainlab_blog_posts.id')
            ->whereNotExists(function ($q) use ($userId) {
                $q->select('ulogic_projects_passing.answer')
                    ->where('ulogic_projects_passing.entity_type', Post::class)
                    ->where('ulogic_projects_passing.user_id', $userId);
            });
    }

    public function scopeIsProjectActive($query)
    {
        return $query->leftJoin('project_researches', 'project_researches.id', '=', 'rainlab_blog_posts.research_id')
            ->leftJoin('ulogic_projects_settings', 'ulogic_projects_settings.id', '=', 'project_researches.project_id')
            ->where('ulogic_projects_settings.status', 'LIKE', Projects::STATUS_ACTIVE);
    }

    public function scopeNotTimes($query)
    {
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



    /**
     * Recomended articles
     * @return mixed
     */
//    public function recommended()
//    {
//        return $this->hasMany(Recommended::class, 'parent_id', 'id')->with('post');
//    }

}
