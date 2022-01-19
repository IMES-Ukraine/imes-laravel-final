<?php

namespace App\Exports;

use App\Models\Articles;
use App\Models\Passing;
use App\Models\Post;
use App\Models\ProjectResearches;
use App\Models\Projects;
use App\Models\TestQuestions;
use App\Models\User;
use App\Services\ArticleService;
use App\Services\TestService;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ItemNotFoundException;
use Maatwebsite\Excel\Concerns\FromView;

class ExportUserView implements FromView
{
    public $project_id;
    public $content_id;
    public $project;
    public $article;
    public $test;

    public function __construct($project_id, $content_id = null, $article = false, $test = false)
    {
        $this->project_id = $project_id;
        $this->project = Projects::where('id', $project_id) ->first();
        $this->content_id = $content_id;
        $this->article = $article;
        $this->test = $test;
    }

    public function view(): View
    {
        if (! $this->project){
           throw new ItemNotFoundException();
        }
        $research = ProjectResearches::select('id')->where('project_id', $this->project_id)->pluck('id')->all();

        $articles_ids = ArticleService::pluckIDArticles($this->content_id ?? $research);
        $test_ids = TestService::pluckIDRootTests($this->content_id ?? $research);

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

        $audience = User::whereIn('id', $this->project->audience)->get();

        $res = [];
        foreach ($audience as $item){
            $res[$item->id]['user'] = $item;
            $res[$item->id]['bonus-article'] = 0;
            $res[$item->id]['bonus-test'] = 0;
        }
        foreach ($results as $item){
            $res[$item->user_id]['bonus-article'] +=   $item->withdraw['article'];
            $res[$item->user_id]['bonus-test'] +=  $item->withdraw['test'];
        }


        return view('exports.users', [
            'results' => $res
        ]);
    }
}
