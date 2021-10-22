<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Articles;
use App\Models\TestQuestions as Test;

/**
 * Model
 */
class Projects extends Model
{

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

    protected $casts = [
        'options' => 'array',
    ];

    public function getOptionsAttribute($value)
    {
        return json_decode($value);
    }


    public function tests() {
        //return $this->hasMany(ProjectItems::class, 'project_id')->where('item_type', Test::class);
        return $this->hasManyThrough(
            TestQuestions::class,
            ProjectItems::class,
            'project_id',
            'id',
            'id',
            'item_id'
        )->where('item_type', TestQuestions::class);
    }

    public function articles() {
        return $this->hasManyThrough(
            Articles::class,
            ProjectItems::class,
            'project_id',
            'id',
            'id',
            'item_id'
        )->where('item_type', Articles::class);
        //return $this->hasMany(ProjectItems::class, 'project_id')->where('item_type', Articles::class);
    }

    /**
     * Tags
     * @return mixed
     */
    public function tags()
    {
        return $this->hasOne('App\Models\PostTag', 'post_id', 'id')->with('project_tags');
    }

    /**
     * Items
     * @return mixed
     */
    public function items()
    {
        return $this->hasMany('App\Models\ProjectItems', 'project_id', 'id');
    }
}
