<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class Withdraw extends Model
{
    const TYPE_CARD = 'card';
    const TYPE_TEST = 'test';
    const TYPE_ARTICLE = 'article';

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_profile_withdraw';

    protected $fillable = ['user_id', 'total', 'type', 'comment', 'entity_id'];

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
