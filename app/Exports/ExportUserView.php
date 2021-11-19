<?php
namespace App\Exports;

use App\Models\Articles;
use App\Models\Passing;
use App\Models\ProjectResearches;
use App\Models\TestQuestions;
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
        $articles_ids = Articles::select('id')->where('research_id', $research->id)->pluck('id')->toArray();
        $test_ids = TestQuestions::select('id')->where('research_id', $research->id)->pluck('id')->toArray();

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
