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
    //protected $jsonable = ['options'];

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
        'options' => 'array',
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
        return $this->hasMany(ProjectResearches::class, 'project_id', 'id')->with(['tests', 'articles']);

    }

    public function fillAudience()
    {
        if (!$audFile = $this->options['files']['audience']) {
            return false;
        }
        $path = str_replace('/storage', 'app/public', $audFile['path']);
        $file = fopen(storage_path($path), 'r');
        $audience = [];

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
        $this->audience = $audience;
        return true;
    }

}
