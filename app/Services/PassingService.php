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
     * @return integer
     */
    public static function getPassingTotal($content_id) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);
        $test_ids = TestService::pluckIDArticles($content_id);

        if ($articles_ids && $test_ids) {
            /*return Passing::whereRaw('`entity_type` = "'.Passing::PASSING_ENTITY_TYPE_TEST.'" AND `entity_id` IN(' . implode(",", $test_ids) . ')')
                ->orWhereRaw('`entity_type` = "'.Passing::PASSING_ENTITY_TYPE_POST.'" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')
                ->count();*/
            return Passing::where(function($query) use($test_ids)
            {
                $query->where('ulogic_projects_passing.entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_POST)
                    ->whereNotIn('ulogic_projects_passing.entity_id', $test_ids);

            })->orWhere(function($query) use($articles_ids)
            {
                $query->where('ulogic_projects_passing.entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_TEST)
                    ->whereNotIn('ulogic_projects_passing.entity_id', $articles_ids);

            })->count();
        } elseif ($articles_ids) {
            //return Passing::whereRaw('`entity_type` = "'.Passing::PASSING_ENTITY_TYPE_POST.'" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')->count();
            return Passing::where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_POST)
                ->whereIn('entity_id', $articles_ids)
                ->count();
        } elseif ($test_ids) {
            //return Passing::whereRaw('`entity_type` = "'.Passing::PASSING_ENTITY_TYPE_TEST.'" AND `entity_id` IN(' . implode(",", $test_ids) . ')')->count();
            Passing::where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_TEST)
                ->whereIn('entity_id', $test_ids)
                ->count();
        }

        return 0;
    }

    /**
     * @param $content_id
     * @param $status
     * @return integer
     */
    public static function getPassingTotalStatus($content_id, $status) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);
        $test_ids = TestService::pluckIDArticles($content_id);

        if ($articles_ids && $test_ids) {
            /*return Passing::whereRaw('(`status` = ' . $status . ' AND `entity_type` = "'.Passing::PASSING_ENTITY_TYPE_TEST.'" AND `entity_id` IN(' . implode(",", $test_ids) . '))')
                ->orWhereRaw('(`status` = ' . $status . ' AND `entity_type` = "'.Passing::PASSING_ENTITY_TYPE_POST.'" AND `entity_id` IN(' . implode(",", $articles_ids) . '))')
                ->count();*/
            return Passing::where(function($query) use($articles_ids, $status)
            {
                $query->where('status', $status)
                    ->where('ulogic_projects_passing.entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_POST)
                    ->whereIn('ulogic_projects_passing.entity_id', $articles_ids);

            })->orWhere(function($query) use($test_ids, $status)
            {
                $query->where('status', $status)
                    ->where('ulogic_projects_passing.entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_TEST)
                    ->whereIn('ulogic_projects_passing.entity_id', $test_ids);

            })->count();
        } elseif ($articles_ids) {
            //return Passing::whereRaw('(`status` = ' . $status . ' AND `entity_type` = "'.Passing::PASSING_ENTITY_TYPE_POST.'" AND `entity_id` IN(' . implode(",", $articles_ids) . '))')->count();
            return Passing::where('status', $status)
                ->where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_POST)
                ->whereIn('entity_id', $articles_ids)
                ->count();
        } elseif ($test_ids) {
            //return Passing::whereRaw('(`status` = ' . $status . ' AND `entity_type` = "'.Passing::PASSING_ENTITY_TYPE_TEST.'" AND `entity_id` IN(' . implode(",", $test_ids) . '))')->count();
            Passing::where('status', $status)
                ->where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_TEST)
                ->whereIn('entity_id', $test_ids)
                ->count();
        }

        return 0;
    }

    /**
     * @param $content_id
     * @return integer
     */
    public static function getNotUsersPassingTotal($content_id) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);
        $test_ids = TestService::pluckIDArticles($content_id);

        return User::isNotPassed($articles_ids, $test_ids)->count();
    }

    /**
     * @param $project_id
     * @return integer
     */
    public static function getNotUsersPassingTotalAll($project_id) {
        $research = ProjectResearches::select('id')->where('project_id', $project_id)->first();

        if ($research->id) {
            return self::getNotUsersPassingTotal($research->id);
        }

        return 0;
    }

    /**
     * @param $content_id
     * @param $status
     * @return integer
     */
    public static function getPassingTotalStatusTest($content_id, $status) {
        $test_ids = TestService::pluckIDArticles($content_id);

        return Passing::where('status', $status)
            ->where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_TEST)
            ->whereIn('entity_id', $test_ids)
            //->whereRaw('`entity_type` = "'.Passing::PASSING_ENTITY_TYPE_TEST.'" AND `entity_id` IN(' . implode(",", $test_ids) . ')')
            ->count();
    }

    /**
     * @param $content_id
     * @param $status
     * @return integer
     */
    public static function getPassingTotalStatusArticle($content_id, $status) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);

        if ($articles_ids) {
            return Passing::where('status', $status)
                ->where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_POST)
                ->whereIn('entity_id', $articles_ids)
                //->whereRaw('`entity_type` = "'.Passing::PASSING_ENTITY_TYPE_POST.'" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')
                ->count();
        }

        return 0;
    }

    /**
     * @param $content_id
     * @return integer
     */
    public static function getPassingTotalTest($content_id) {
        $test_ids = TestService::pluckIDArticles($content_id);

        return Passing::where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_TEST)
            ->whereIn('entity_id', $test_ids)
            ->count();
        //return Passing::whereRaw('`entity_type` = "'.Passing::PASSING_ENTITY_TYPE_TEST.'" AND `entity_id` IN(' . implode(",", $test_ids) . ')')->count();
    }

    /**
     * @param $content_id
     * @return integer
     */
    public static function getPassingTotalArticle($content_id) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);

        if ($articles_ids) {
            return Passing::where('entity_type', 'LIKE', Passing::PASSING_ENTITY_TYPE_POST)
                ->where('entity_id', $articles_ids)
                ->count();
            //return Passing::whereRaw('`entity_type` = "'.Passing::PASSING_ENTITY_TYPE_POST.'" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')->count();
        }

        return 0;
    }

    /**
     * @param $content_id
     * @return mixed
     */
    public static function getPassingTest() {
        //
    }

}
