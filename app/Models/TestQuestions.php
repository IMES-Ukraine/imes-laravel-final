<?php
namespace App\Models;

//imes-backend/plugins/ulogic/tests/models/Test.php
use App\Traits\JsonFieldTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Model
 */
class TestQuestions extends Model
{
    use JsonFieldTrait;
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

    /**
     * @var string The database table used by the model.
     */
    public $table = 'ulogic_tests_questions';


    /**
     * @var array Validation rules
     */
    public $rules = [
    ];


    protected $appends = ['isAgreementAccepted', 'isOpened'];

    public function getIsAgreementAcceptedAttribute()
    {
        $userModel = auth()->user();
        if ($userModel) {
            return (bool)ProjectsAgreement::where(['user_id' => $userModel->id])->where(['project_id' => $this->research->project_id])->count();
        }
        return false;
    }

    public function getIsOpenedAttribute()
    {
        $apiUser = auth()->user();
        if($apiUser) {
            return (bool)TestOpened::where(['user_id' => $apiUser->id])->where(['test_id' => $this->id])->count();
        }
        return false;
    }


    /**
     * Featured images
     * @return mixed
     */
    public function featured_images() {
        return $this->hasMany('App\Models\File', 'attachment_id', 'id')->where(['field' => File::FIELD_IMAGE ]);
    }

    /**
     * Cover images
     * @return mixed
     */
    public function cover_image() {
        return $this->hasOne('App\Models\File', 'attachment_id', 'id')->where(['field' => File::FIELD_COVER ]);
    }

    /**
     * Cover images
     * @return mixed
     */
    public function image() {
        return $this->hasOne('App\Models\File', 'attachment_id', 'id')->where(['field' => File::FIELD_IMAGE ]);
    }

    /**
     * Cover images
     * @return mixed
     */
    public function video() {
        return $this->hasOne('App\Models\File', 'attachment_id', 'id')->where(['field' => File::FIELD_VIDEO ]);
    }

    /**
     * Cover images
     * @return mixed
     */
    public function research() {
        return $this->hasOne(ProjectResearches::class, 'id', 'research_id');
    }

    /**
     * Сложные тесты
     * @return mixed
     */
    public function complex() {
        return $this->hasMany('App\Models\TestQuestions', 'parent_id', 'id');
    }


    public function scopeIsProjectActive( $query)
    {
        return $query->join('project_researches', 'project_researches.id',  '=', 'ulogic_tests_questions.research_id')
            ->leftJoin('ulogic_projects_settings', 'ulogic_projects_settings.id', '=', 'project_researches.project_id')
            ->where('ulogic_projects_settings.status', 'LIKE', Projects::STATUS_ACTIVE);
    }

    public function scopeIsNotPassed( $query, $userId)
    {
        return $query->whereNotExists(function ($q) use ($userId){
            $q->select(DB::raw(1))->from('ulogic_projects_passing')
                    ->whereRaw('ulogic_projects_passing.entity_type = "' . quotemeta(self::class) . '"' )
                    ->whereRaw('ulogic_projects_passing.user_id = ' . $userId)
                    ->whereRaw('ulogic_projects_passing.status = 1')
                    ->whereColumn('ulogic_projects_passing.entity_id', 'ulogic_tests_questions.id');
            });
    }
}
