<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class Notifications extends Model
{
    const TYPE_MESSAGE  = 'MESSAGE';
    const TYPE_REFILL   = 'REFILL';
    const TYPE_WITHDRAW = 'WITHDRAW';
    const TYPE_NEWS     = 'NEWS';
    const TYPE_SUPPORT  = 'SUPPORT';
    const TYPE_ALL      = 'ALL';
    const ALL_USERS = 0;

    const TOPIC_MAPPING = [
        self::TYPE_WITHDRAW => 'balance',
        self::TYPE_MESSAGE  => 'messages',
        self::TYPE_REFILL   => 'balance',
        self::TYPE_NEWS     => 'news',
        self::TYPE_SUPPORT  => 'messages',
        self::TYPE_ALL      => 'all'
    ];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array
     */
    protected $casts = [
        'text' => 'array',
    ];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_notifications_feed';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
        'user' => ['App\Models\User'],
    ];


    /**
     * @var array Relations
     */
    public $hasMany = [
        'status' => [NotificationsStatus::class, 'key' => 'notification_id'],
    ];

    public function status()
    {
        return $this->hasMany(NotificationsStatus::class, 'notification_id');
    }
}
