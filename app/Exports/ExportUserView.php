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

    public function __construct($project_id)
    {
        $this->project_id = $project_id;
    }

    public function view(): View
    {
        $research = ProjectResearches::select('id')->where('project_id', $this->project_id)->first();
        $articles_ids = ArticleService::pluckIDArticles($research->id);
        $test_ids = TestService::pluckIDArticles($research->id);

        $results = Passing::with('user')
            ->with('withdraw')
            ->whereRaw('`entity_type` = "TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . ')')
            ->orWhereRaw('`entity_type` = "Post" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')
            ->get();

        return view('exports.users', [
            'results' => $results
        ]);
    }
}
