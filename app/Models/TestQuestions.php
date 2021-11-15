<?php
namespace App\Models;

//imes-backend/plugins/ulogic/tests/models/Test.php
use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class TestQuestions extends Model
{
    const STATUS_PASSED = 'passed';
    const STATUS_FAILED = 'failed';
    const STATUS_SUBMITTED = 'submitted';

    const TYPE_SIMPLE = 'simple';
    const TYPE_COMPLEX = 'complex';
    const TYPE_CHILD = 'child';

    //use \October\Rain\Database\Traits\Validation;

    //use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'title',
        'test_type',
        'question',
        'answer_type',
        'variants',
        'options',
        'duration_seconds',
        'parent_id',
        'passing_bonus',
        'is_popular',
        'agreement',
        'schedule',
        'can_retake'
    ];

    /**
     * Атрибуты, которые должны быть типизированы.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        'variants' => 'array',
    ];

    protected $hidden = ['agreement'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_tests_questions';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];



    /**
     * Featured images
     * @return mixed
     */
    public function featured_images() {
        return $this->hasMany('App\Models\File', 'attachment_id', 'id');
    }

    /**
     * Cover images
     * @return mixed
     */
    public function cover_image() {
        return $this->hasOne('App\Models\File', 'attachment_id', 'id');
    }

    /**
     * Сложные тесты
     * @return mixed
     */
    public function complex() {
        return $this->hasMany('App\Models\TestQuestions', 'parent_id', 'id');
    }

    /**
     * Agreement
     * @return mixed
     */
    public function agreementAccepted()
    {
        return $this->hasMany(TestOpened::class, 'test_id', 'id');
    }
}
