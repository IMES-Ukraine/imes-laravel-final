<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TestQuestions;


/**
 * Model
 */
class ProjectItems extends Model
{

    protected $dates = ['deleted_at'];
    //protected $jsonable = ['data'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_projects_items';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public function getDataAttribute($value)
    {
        return json_decode($value);
    }

    public function testsEntities() {
        return $this->hasOne(TestQuestions::class, 'item_id');
    }

    public function articlesEntities() {
        return $this->hasOne(TestQuestions::class, 'item_id');
    }
}
