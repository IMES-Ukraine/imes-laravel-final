<?php

namespace App\Models;

use App\Traits\JsonFieldTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * Class ProjectResearches
 * @package App\Models
 *
 * @property integer $id
 * @property integer $project_id
 * @property string $title
 * @property string $article
 * @property string $test
 * @property string $schedule
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProjectResearches extends Model
{
    use JsonFieldTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'project_researches';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'title',
        'article',
        'test',
        'schedule',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'article' => 'object',
        'test' => 'object'
    ];

    const RuleList = [
        'project_id' => ['required', 'integer'],
        'title' => ['required', 'max:255'],
        'article' => ['required'],
        'test' => ['required'],
        'schedule' => ['required'],
    ];
    protected $appends = ['fullTest', 'fullArticle'];

    public function getFullTestAttribute()
    {
        $testQuestion = $this->testObject()->first();
        $test = (array)$this->test;
        $res = null;
        if ($testQuestion) {
            $res = TestQuestions::with(['cover_image', 'image', 'video', 'complex', 'featured_images', 'research'])
                ->where(['id' => $testQuestion->id])
                ->first()
                ->makeHidden('research')
                ->toArray();
            $res['picked'] = $test['picked'];
            $res['type'] = $res['test_type'];
            $res['question'] = $test['question'];
        }
        return $res;
    }

    public function getFullArticleAttribute()
    {
        $article = $this->articleObject()->first();
        $res = null;
        if ($article) {
            $res = Articles::with(['cover_image', 'featured_images'])
                ->where(['id' => $article->id])
                ->first()
                ->makeHidden('research')
                ->toArray();
        }
        return $res;
    }

    public function project(): HasOne
    {
        return $this->hasOne(Projects::class, 'id', 'project_id');
    }

    /**
     * All tests in research
     * @return HasOne
     */
    public function testObject(): HasOne
    {
        return $this->hasOne(TestQuestions::class, 'research_id', 'id');
    }

    /**
     * All articles in research
     * @return HasOne
     */
    public function articleObject(): HasOne
    {
        return $this->hasOne(Articles::class, 'research_id', 'id');
    }



//    public function destroyData()
//    {
//        $tests = $this->tests;
//        foreach ($tests as $item) {
//            $item->delete();
//        }
//
//        $articles = $this->articles;
//        foreach ($articles as $item) {
//            $item->delete();
//        }
//    }

}
