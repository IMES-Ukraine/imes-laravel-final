<?php
namespace App\Exports;

use App\Models\Articles;
use App\Models\Passing;
use App\Models\ProjectResearches;
use App\Models\TestQuestions;
use App\Services\ArticleService;
use App\Services\TestService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportUserView implements FromView
{
    public $project_id;
    public $content_id;
    public $article;
    public $test;

    public function __construct($project_id, $content_id = null, $article = false, $test = false)
    {
        $this->project_id = $project_id;
        $this->content_id = $content_id;
        $this->article = $article;
        $this->test = $test;
    }

    public function view(): View
    {
        $research = ProjectResearches::select('id')->where('project_id', $this->project_id)->first();
        $articles_ids = ArticleService::pluckIDArticles($this->content_id??$research->id);
        $test_ids = TestService::pluckIDArticles($this->content_id??$research->id);

        $query = Passing::with('user')->with('withdraw');

        if ($this->article) {
            $query->whereRaw('`entity_type` = "App\Models\Post" AND `entity_id` IN(' . implode(",", $articles_ids) . ')');
        } elseif ($this->test) {
            $query->whereRaw('`entity_type` = "App\Models\TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . ')');
        } else {
            if ($articles_ids && $test_ids) {
                $query->whereRaw('`entity_type` = "App\Models\TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . ')')
                    ->orWhereRaw('`entity_type` = "App\Models\Post" AND `entity_id` IN(' . implode(",", $articles_ids) . ')');
            } elseif ($test_ids) {
                $query->whereRaw('`entity_type` = "App\Models\TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . ')')
                    ->orWhereRaw('`entity_type` = "App\Models\Post" AND `entity_id` IS NOT NULL');
            } elseif ($articles_ids) {
                $query->whereRaw('`entity_type` = "App\Models\TestQuestions" AND `entity_id` IS NOT NULL')
                    ->orWhereRaw('`entity_type` = "App\Models\Post" AND `entity_id` IN(' . implode(",", $articles_ids) . ')');
            }
        }

        $results = $query->get();

        return view('exports.users', [
            'results' => $results
        ]);
    }
}
