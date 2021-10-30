<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class UserCards
 * @package App\Models
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $card_id
 * @property integer $is_active
 * @property integer $cost
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class UserCards extends Model
{
    const USER_CARDS_IS_ACTIVE = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_cards';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'card_id',

        'is_active',

        'cost'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    const RuleList = [

        'card_id' => [],

        'is_active' => [],

        'cost' => []

    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function card() {
        return $this->belongsTo(Cards::class, 'card_id', 'id');
    }


}
