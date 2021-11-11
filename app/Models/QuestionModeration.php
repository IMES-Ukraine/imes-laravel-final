<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class QuestionModeration extends Model
{

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
        return $this->hasMany('App\Models\TestQuestions', 'id' ,'question_id');
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id' ,'user_id');
    }
}
