<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class Recommended extends Model
{
    //use \October\Rain\Database\Traits\Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_news_recommended';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public function article()
    {
        return $this->hasOne('App\Models\Post', 'id', 'recommended_id');

    }

    public function post()
    {
        return $this->hasOne('App\Models\Articles', 'id', 'recommended_id');
    }

    protected $fillable = ['parent_id', 'recommended_id'];
}
