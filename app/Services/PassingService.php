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
        $test_ids = TestService::pluckIDTests($content_id);

        return Passing::IsNotPassed($articles_ids, $test_ids)->count();
    }

    /**
     * @param $content_id
     * @param $status
     * @return integer
     */
    public static function getPassingTotalStatus($content_id, $status) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);
        $test_ids = TestService::pluckIDRootTests($content_id);

        return Passing::IsPassed($articles_ids, $test_ids, $status)->count();
    }

    /**
     * @param $content_id
     * @return integer
     */
    public static function getNotUsersPassingTotal($content_id) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);
        $test_ids = TestService::pluckIDTests($content_id);

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
     * @param $result
     * @return integer
     */
    public static function getPassingTotalStatusTest($content_id, $status, $result) {
        $test_ids = TestService::pluckIDRootTests($content_id);

        if ($test_ids) {
            return Passing::IsPassed(false, $test_ids, $status, $result)->distinct()->orderBy('created_at')->count('user_id');
        }

        return 0;
    }


    /**
     * @param $content_id
     * @param $status
     * @return integer
     */
    public static function getPassingTotalStatusArticle($content_id, $status) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);

        if ($articles_ids) {
            return Passing::IsPassed($articles_ids, false, $status)->distinct()->orderBy('created_at')->count('user_id');
        }

        return 0;
    }

    /**
     * @param $content_id
     * @return integer
     */
    public static function getPassingTotalTest($content_id) {
        $test_ids = TestService::pluckIDTests($content_id);

        if ($test_ids) {
            return Passing::IsPassed(false, $test_ids)->count();
        }

        return 0;
    }

    /**
     * @param $content_id
     * @return integer
     */
    public static function getPassingTotalArticle($content_id) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);

        if ($articles_ids) {
            return Passing::IsPassed($articles_ids, false)->count();
        }

        return 0;
    }

}
