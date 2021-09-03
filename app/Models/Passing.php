<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class Passing extends Model
{

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_projects_passing';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
