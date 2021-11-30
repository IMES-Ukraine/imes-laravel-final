<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Articles;
use App\Models\TestQuestions as Test;

/**
 * Model
 */
class Projects extends Model
{
    use SoftDeletes;

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
    ];

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
        return $this->hasMany(ProjectResearches::class, 'project_id', 'id')->with(['tests','articles']);
        //return $this->hasMany('App\Models\ProjectItems', 'project_id', 'id');
    }
}
