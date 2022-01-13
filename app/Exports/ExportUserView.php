<?php

namespace App\Exports;

use App\Models\Articles;
use App\Models\Passing;
use App\Models\Post;
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

        $articles_ids = ArticleService::pluckIDArticles($this->content_id ?? $research->id);
        $test_ids = TestService::pluckIDRootTests($this->content_id ?? $research->id);

        $results = Passing::with('user')
            ->where(function ($q) use ($articles_ids) {
                $q->isEntityPassed(Post::class, $articles_ids);
            })
            ->orWhere(function ($q) use ($articles_ids) {
                $q->isEntityNotPassed(Post::class, $articles_ids);
            })
            ->orWhere(function ($q) use ($test_ids) {
                $q->isEntityPassed(TestQuestions::class, $test_ids);
            })
            ->orWhere(function ($q) use ($test_ids) {
                $q->isEntityNotPassed(TestQuestions::class, $test_ids);
            })
            ->get();

        return view('exports.users', [
            'results' => $results
        ]);
    }
}
