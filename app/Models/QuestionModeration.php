<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class QuestionModeration extends Model
{
    const TEST_MODERATION_PENDING = 'pending';
    const TEST_MODERATION_ACCEPT = 'accept';
    const TEST_MODERATION_CANCEL = 'cancel';

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_tests_moderation';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public function question() {
        return $this->hasOne('App\Models\TestQuestions', 'id' ,'question_id');
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id' ,'user_id');
    }
}
