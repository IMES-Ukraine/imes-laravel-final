<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * Class Cards
 * @package App\Models
 *
 * @property integer $id
 * @property string $name
 * @property integer $cost
 * @property string $short_description
 * @property string $description
 * @property string $cover
 * @property integer $category_id
 * @property integer $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Cards extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cards';

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
        'name',
        'cost',
        'short_description',
        'description',
        'cover',
        'category_id',
        'is_active',
        'detailed',
        'has_category'
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
'detailed' => 'boolean'
    ];

    const RuleList = [

        'name' => [],

        'cost' => [],

        'short_description' => [],

        'description' => [],

        'cover' => [],

        'category_id' => [],

        'is_active' => [],
        'detailed' => [],
        'has_category' => []

    ];


}
