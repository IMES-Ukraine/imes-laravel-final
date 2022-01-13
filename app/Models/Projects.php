<?php

namespace App\Models;

use App\Traits\JsonFieldTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Articles;
use App\Models\TestQuestions as Test;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Model
 */
class Projects extends Model
{
    use SoftDeletes;
    use JsonFieldTrait;

    const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETE = 'complete';

    protected $dates = ['deleted_at'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_projects_settings';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $fillable = ['options', 'status'];

    protected $casts = [
        'options'  => 'array',
        'audience' => 'array',
    ];

    public function scopeTag($query, $tag)
    {
        return $query->leftJoin('ulogic_projects_tags', 'ulogic_projects_tags.project_id', '=', 'ulogic_projects_settings.id')
            ->where('ulogic_projects_tags.tag_id', $tag);
    }

    public function getCreatedAtAttribute($value)
    {
        return date_format(date_create($value), 'd.m.Y');
    }


    /**
     * Tags
     * @return mixed
     */
    public function tags()
    {
        return $this->hasOne(ProjectTags::class, 'project_id', 'id')->with('project_tags');
    }

    /**
     * Items
     * @return mixed
     */
    public function items()
    {
        return $this->hasMany(ProjectResearches::class, 'project_id', 'id')->with(['testObject', 'articleObject']);

    }

    public function fillAudience()
    {
        $audience = [];
        if (!$audFile = $this->options['files']['audience']) {
            $audience = User::select('id')->isActive()->isVerified()->pluck('id');
        }
        else {
            $path = str_replace('/storage', 'app/public', $audFile['path']);
            $file = fopen(storage_path($path), 'r');

            while ($raw = fgetcsv($file)) {
                //    'name', 'phone', 'email'
                if ($raw[0] && $raw[1] && $raw[2]) {
                    $user = User::findByPhone($raw[1]);
                    if (!$user) {
                        $user = User::createNewUser($raw[1], '', $raw[0], $raw[2]);
                    }
                    $audience[] = $user->id;
                }
            }
        }
        $this->audience = $audience;
        return $audience;
    }

    public function notParticipateUserIds($articles_ids, $test_ids)
    {
        $result = User::whereIn('id', $this->audience);

        if ($articles_ids) {
            $in_articles = Passing::select('user_id')
                ->where('ulogic_projects_passing.entity_type', Post::class)
                ->whereIn('ulogic_projects_passing.entity_id', $articles_ids)
                ->get();

            $result->whereNotIn('id', $in_articles);
        }

        if ($test_ids) {
            $in_tests = Passing::select('user_id')
                ->where('ulogic_projects_passing.entity_type', TestQuestions::class)
                ->whereIn('ulogic_projects_passing.entity_id', $test_ids)
                ->get();

            $result->whereNotIn('id', $in_tests);
        }

        return $result->pluck('id');
    }



}
