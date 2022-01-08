<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class City extends Model
{

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_profile_cities';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
