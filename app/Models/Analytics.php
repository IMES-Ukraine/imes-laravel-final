<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class Analytics extends Model
{

    protected $dates = ['deleted_at'];

    const IN_PROGRESS = 0;
    const APPROVED = 1;
    const DECLINED = 2;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_analytics_reports';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $hasOne = [
        'user' => ['App\Models\User', 'key' => 'id', ]
    ];

    public function scopeIsInProgress($query)
    {
        return $query->where('status', self::IN_PROGRESS)->with('user');
    }

    public function scopeFilterByGroup($query, $filter)
    {
        return $query->whereHas('groups', function($group) use ($filter) {
            $group->whereIn('id', $filter);
        });
    }
}
