<?php
namespace App\Models;

use App\Traits\JsonFieldTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Model
 */
class Passing extends Model
{
    use JsonFieldTrait;

    const PASSING_NOT_ACTIVE = 0;
    const PASSING_ACTIVE = 1;

    const PASSING_ENTITY_TYPE_POST = '%Post';
    const PASSING_ENTITY_TYPE_TEST = '%TestQuestions';

    protected $dates = ['deleted_at'];

    public function scopeIsPassed($query, $articles_ids, $test_ids, $status = self::PASSING_PASSED)
    {
        if ($articles_ids && $test_ids) {
            $query->where(function($q) use($test_ids, $status)
            {
                if ($status != self::PASSING_PASSED) {
                    $q->where('ulogic_projects_passing.status', $status);
                }

                $q->where('ulogic_projects_passing.entity_type', TestQuestions::class)
                    ->whereIn('ulogic_projects_passing.entity_id', $test_ids);

            })->orWhere(function($q) use($articles_ids, $status)
            {
                if ($status != self::PASSING_PASSED) {
                    $q->where('ulogic_projects_passing.status', $status);
                }

                $q->where('ulogic_projects_passing.entity_type', Post::class)
                    ->whereIn('ulogic_projects_passing.entity_id', $articles_ids);

            });
        } elseif ($articles_ids) {
            if ($status != self::PASSING_PASSED) {
                $query->where('status', $status);
            }

            $query->where('entity_type', Post::class)
                ->whereIn('entity_id', $articles_ids);
        } elseif ($test_ids) {
            if ($status != self::PASSING_PASSED) {
                $query->where('status', $status);
            }

            $query->where('entity_type', TestQuestions::class)
                ->whereIn('entity_id', $test_ids);
        }
    }

    public function scopeIsNotPassed($query, $articles_ids, $test_ids, $status = self::PASSING_PASSED)
    {
        if ($articles_ids && $test_ids) {
            $query->where(function($q) use($test_ids, $status)
            {
                if ($status != self::PASSING_PASSED) {
                    $q->where('ulogic_projects_passing.status', $status);
                }

                $q->where('ulogic_projects_passing.entity_type', TestQuestions::class)
                    ->whereNotIn('ulogic_projects_passing.entity_id', $test_ids);

            })->orWhere(function($q) use($articles_ids, $status)
            {
                if ($status != self::PASSING_PASSED) {
                    $q->where('ulogic_projects_passing.status', $status);
                }

                $q->where('ulogic_projects_passing.entity_type', Post::class)
                    ->whereNotIn('ulogic_projects_passing.entity_id', $articles_ids);

            });
        } elseif ($articles_ids) {
            if ($status != self::PASSING_PASSED) {
                $query->where('status', $status);
            }

            $query->where('entity_type', Post::class)
                ->whereNotIn('entity_id', $articles_ids);
        } elseif ($test_ids) {
            if ($status != self::PASSING_PASSED) {
                $query->where('status', $status);
            }

            $query->where('entity_type', TestQuestions::class)
                ->whereNotIn('entity_id', $test_ids);
        }
    }

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
