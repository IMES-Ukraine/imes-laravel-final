<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class Passing extends Model
{
    const PASSING_ACTIVE = 2;
    const PASSING_NOT_ACTIVE = 1;
    const PASSING_NOT_PARTICIPATE = 0;

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

    protected $casts = ['answer' => 'object'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function withdraw()
    {
        return $this->hasOne(Withdraw::class, 'id', 'user_id');
    }
}
