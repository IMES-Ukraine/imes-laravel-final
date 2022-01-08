<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class Withdraw extends Model
{

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_profile_withdraw';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'user' => ['App\Models\User'],
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
