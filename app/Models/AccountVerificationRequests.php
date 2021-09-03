<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class AccountVerificationRequests extends Model
{

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_registration_verification';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public function user() {
        return $this->hasOne('RainLab\User\Models\User', 'id', 'user_id');
    }
}
