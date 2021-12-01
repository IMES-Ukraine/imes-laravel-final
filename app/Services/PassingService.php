<?php
namespace App\Services;


use App\Models\Articles;
use App\Models\Passing;
use App\Models\ProjectResearches;
use App\Models\TestQuestions;
use App\Models\User;

class PassingService
{
    /**
     * @param $content_id
     * @return mixed
     */
    public static function getPassingTotal($content_id) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);
        $test_ids = TestService::pluckIDArticles($content_id);

        if ($articles_ids && $test_ids) {
            return Passing::whereRaw('`entity_type` = "App\Models\TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . ')')
                ->orWhereRaw('`entity_type` = "App\Models\Post" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')
                ->count();
        } elseif ($articles_ids) {
            return Passing::whereRaw('`entity_type` = "App\Models\Post" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')->count();
        } elseif ($test_ids) {
            return Passing::whereRaw('`entity_type` = "App\Models\TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . ')')->count();
        }

        return 0;
    }

    /**
     * @param $content_id
     * @param $status
     * @return mixed
     */
    public static function getPassingTotalStatus($content_id, $status) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);
        $test_ids = TestService::pluckIDArticles($content_id);

        if ($articles_ids && $test_ids) {
            return Passing::whereRaw('(`status` = ' . $status . ' AND `entity_type` = "App\Models\TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . '))')
                ->orWhereRaw('(`status` = ' . $status . ' AND `entity_type` = "App\Models\Post" AND `entity_id` IN(' . implode(",", $articles_ids) . '))')
                ->count();
        } elseif ($articles_ids) {
            return Passing::whereRaw('(`status` = ' . $status . ' AND `entity_type` = "App\Models\Post" AND `entity_id` IN(' . implode(",", $articles_ids) . '))')->count();
        } elseif ($test_ids) {
            return Passing::whereRaw('(`status` = ' . $status . ' AND `entity_type` = "App\Models\TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . '))')->count();
        }

        return 0;
    }

    /**
     * @param $content_id
     * @return mixed
     */
    public static function getNotUsersPassingTotal($content_id) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);
        $test_ids = TestService::pluckIDArticles($content_id);

        $query = User::leftJoin('ulogic_projects_passing', 'ulogic_projects_passing.user_id', '=', 'users.id')
            ->whereNull('ulogic_projects_passing.user_id');

        if ($test_ids && $articles_ids) {
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\TestQuestions" AND `ulogic_projects_passing`.`entity_id` NOT IN(' . implode(",", $test_ids) . '))');
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\Post" AND `ulogic_projects_passing`.`entity_id` NOT IN(' . implode(",", $articles_ids) . '))');
        } elseif ($test_ids) {
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\TestQuestions" AND `ulogic_projects_passing`.`entity_id` NOT IN(' . implode(",", $test_ids) . '))');
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\Post" AND `ulogic_projects_passing`.`entity_id` IS NOT NULL)');
        } elseif ($articles_ids) {
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\TestQuestions" AND `ulogic_projects_passing`.`entity_id` IS NOT NULL)');
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\Post" AND `ulogic_projects_passing`.`entity_id` NOT IN(' . implode(",", $articles_ids) . '))');
        }

        return $query->count();
    }

    /**
     * @param $content_id
     * @return mixed
     */
    public static function getNotUsersPassingTotalAll($project_id) {
        $research = ProjectResearches::select('id')->where('project_id', $project_id)->first();
        $articles_ids = ArticleService::pluckIDArticles($research->id);
        $test_ids = TestService::pluckIDArticles($research->id);

        $query = User::leftJoin('ulogic_projects_passing', 'ulogic_projects_passing.user_id', '=', 'users.id')
            ->whereNull('ulogic_projects_passing.user_id');

        if ($test_ids && $articles_ids) {
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\TestQuestions" AND `ulogic_projects_passing`.`entity_id` NOT IN(' . implode(",", $test_ids) . '))');
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\Post" AND `ulogic_projects_passing`.`entity_id` NOT IN(' . implode(",", $articles_ids) . '))');
        } elseif ($test_ids) {
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\TestQuestions" AND `ulogic_projects_passing`.`entity_id` NOT IN(' . implode(",", $test_ids) . '))');
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\Post" AND `ulogic_projects_passing`.`entity_id` IS NOT NULL)');
        } elseif ($articles_ids) {
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\TestQuestions" AND `ulogic_projects_passing`.`entity_id` IS NOT NULL)');
            $query->orWhereRaw('(`ulogic_projects_passing`.`entity_type` = "App\Models\Post" AND `ulogic_projects_passing`.`entity_id` NOT IN(' . implode(",", $articles_ids) . '))');
        }

        return $query->count();
    }

    /**
     * @param $content_id
     * @param $status
     * @return mixed
     */
    public static function getPassingTotalStatusTest($content_id, $status) {
        $test_ids = TestService::pluckIDArticles($content_id);

        return Passing::where('status', $status)
            ->whereRaw('`entity_type` = "App\Models\TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . ')')
            ->count();
    }

    /**
     * @param $content_id
     * @param $status
     * @return mixed
     */
    public static function getPassingTotalStatusArticle($content_id, $status) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);

        if ($articles_ids) {
            return Passing::where('status', $status)
                ->whereRaw('`entity_type` = "App\Models\Post" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')
                ->count();
        }

        return 0;
    }

    /**
     * @param $content_id
     * @return mixed
     */
    public static function getPassingTotalTest($content_id) {
        $test_ids = TestService::pluckIDArticles($content_id);

        return Passing::whereRaw('`entity_type` = "App\Models\TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . ')')->count();
    }

    /**
     * @param $content_id
     * @return mixed
     */
    public static function getPassingTotalArticle($content_id) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);

        if ($articles_ids) {
            return Passing::whereRaw('`entity_type` = "App\Models\Post" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')->count();
        }

        return 0;
    }

}
