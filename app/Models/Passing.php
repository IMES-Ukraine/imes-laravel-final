<?php

namespace App\Models;

use App\Traits\JsonFieldTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Model
 */
class Passing extends Model
{
    use JsonFieldTrait;

    const PASSING_NOT_ACTIVE = 0;
    const PASSING_ACTIVE = 1;
    const NO_STATUS = 9;

    const PASSING_RESULT_ACTIVE = 1;
    const PASSING_RESULT_NOT_ACTIVE = 0;
    const NO_RESULT = 9;

    const PASSING_ENTITY_TYPE_POST = '%Post';
    const PASSING_ENTITY_TYPE_TEST = '%TestQuestions';

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'entity_type',
        'entity_id',
        'user_id',
        'status',
        'result',
        'answer',
        'updated_at'
    ];

    public function scopeIsEntityPassed($query, $entityType, $ids)
    {
        // TODO это потом убрать. Статьи теперь тоже результатом отмечаются
        if ($entityType !== TestQuestions::class) {
            return $query->where('entity_type', $entityType)
                ->whereIn('entity_id', $ids)
                ->where('status', '1');
        }
        return $query->where('entity_type', $entityType)
            ->whereIn('entity_id', $ids)
            ->where('result', '1');
    }

    public function scopeIsEntityNotPassed($query, $entityType, $ids)
    {
        if ($entityType !== TestQuestions::class) {
            return $query->where('entity_type', $entityType)
                ->whereIn('entity_id', $ids)
                ->where('status', '0');
        }
        return $query->where('entity_type', $entityType)
            ->whereIn('entity_id', $ids)
            ->where('result', '0');
    }

    public function scopeGetTotalPassed($query, $articles_ids, $test_ids)
    {
        if (!empty($test_ids) ) {
            $query->isEntityPassed(TestQuestions::class, $test_ids);
        }
        if (!empty($articles_ids)) {
            $query->isEntityPassed(Post::class, $articles_ids);
        }
    }

    public function scopeIsPassed($query, $articles_ids, $test_ids, $status = self::NO_STATUS, $result = self::NO_RESULT)
    {
        if ($articles_ids && $test_ids) {
            $query->where(function ($q) use ($test_ids, $status, $result) {
                if ($status != self::NO_STATUS) {
                    $q->where('ulogic_projects_passing.status', $status);
                }

                if ($result != self::NO_RESULT) {
                    $q->where('ulogic_projects_passing.result', $result);
                }

                $q->where('ulogic_projects_passing.entity_type', TestQuestions::class)
                    ->whereIn('ulogic_projects_passing.entity_id', $test_ids);

//                $q->where('created_at', function ($q) use ($test_ids){
//                    $q->select(DB::raw('max(created_at) as created_at'))->from('ulogic_projects_passing')
//                        ->where('entity_type', TestQuestions::class)
//                        ->whereIn('entity_id', $test_ids)
//                        ->max('created_at');
//                });

            })->orWhere(function ($q) use ($articles_ids, $status, $result) {
                if ($status != self::NO_STATUS) {
                    $q->where('ulogic_projects_passing.status', $status);
                }

                if ($result != self::NO_RESULT) {
                    $q->where('ulogic_projects_passing.result', $result);
                }

                $q->where('ulogic_projects_passing.entity_type', Post::class)
                    ->whereIn('ulogic_projects_passing.entity_id', $articles_ids);

            });
        }
        elseif ($articles_ids) {
            if ($status != self::NO_STATUS) {
                $query->where('status', $status);
            }

            if ($result != self::NO_RESULT) {
                $query->where('result', $result);
            }

            $query->where('entity_type', Post::class)
                ->whereIn('entity_id', $articles_ids);
        }
        elseif ($test_ids) {
            if ($status != self::NO_STATUS) {
                $query->where('status', $status);
            }

            if ($result != self::NO_RESULT) {
                $query->where('result', $result);
            }

            $query->where('entity_type', TestQuestions::class)
                ->whereIn('entity_id', $test_ids);

        }
    }

    public function scopeIsNotPassed($query, $articles_ids, $test_ids, $status = self::NO_STATUS)
    {
        if ($articles_ids && $test_ids) {
            $query->where(function ($q) use ($test_ids, $status) {
                if ($status != self::NO_STATUS) {
                    $q->where('ulogic_projects_passing.status', $status);
                }

                $q->where('ulogic_projects_passing.entity_type', TestQuestions::class)
                    ->whereNotIn('ulogic_projects_passing.entity_id', $test_ids);

            })->orWhere(function ($q) use ($articles_ids, $status) {
                if ($status != self::NO_STATUS) {
                    $q->where('ulogic_projects_passing.status', $status);
                }

                $q->where('ulogic_projects_passing.entity_type', Post::class)
                    ->whereNotIn('ulogic_projects_passing.entity_id', $articles_ids);

            });
        }
        elseif ($articles_ids) {
            if ($status != self::NO_STATUS) {
                $query->where('status', $status);
            }

            $query->where('entity_type', Post::class)
                ->whereNotIn('entity_id', $articles_ids);
        }
        elseif ($test_ids) {
            if ($status != self::NO_STATUS) {
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
