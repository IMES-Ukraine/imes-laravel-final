<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class NotificationsStatus extends Model
{

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_notifications_status';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
