<?php
namespace App\Services;


use App\Models\Articles;
use App\Models\Passing;
use App\Models\TestQuestions;

class PassingService
{
    /**
     * @param $entity_type
     * @param $entity_id
     * @param $status
     * @return mixed
     */
    public static function getPassingTypeStatusAllUsers($entity_type, $entity_id, $status) {
        return Passing::where(['entity_type' => $entity_type])
            ->where(['entity_id' => $entity_id])
            ->where(['status' => $status])
            ->get();
    }

    /**
     * @param $entity_type
     * @param $entity_id
     * @return mixed
     */
    public static function getPassingTypeAllUsers($entity_type, $entity_id) {
        return Passing::where(['entity_type' => $entity_type])
            ->where(['entity_id' => $entity_id])
            ->get();
    }

    /**
     * @param $content_id
     * @return mixed
     */
    public static function getPassingTotal($content_id) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);
        $test_ids = TestService::pluckIDArticles($content_id);

        return Passing::whereRaw('`entity_type` = "TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . ')')
            ->orWhereRaw('`entity_type` = "Post" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')
            ->count();
    }

    /**
     * @param $content_id
     * @param $status
     * @return mixed
     */
    public static function getPassingTotalStatus($content_id, $status) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);
        $test_ids = TestService::pluckIDArticles($content_id);

        return Passing::whereRaw('(`status` = '.$status.' AND `entity_type` = "TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . '))')
            ->orWhereRaw('(`status` = '.$status.' AND `entity_type` = "Post" AND `entity_id` IN(' . implode(",", $articles_ids) . '))')
            ->count();
    }

    /**
     * @param $content_id
     * @param $status
     * @return mixed
     */
    public static function getPassingTotalStatusTest($content_id, $status) {
        $test_ids = TestService::pluckIDArticles($content_id);

        return Passing::where('status', $status)
            ->whereRaw('`entity_type` = "TestQuestions" AND `entity_id` IN(' . implode(",", $test_ids) . ')')
            ->count();
    }

    /**
     * @param $content_id
     * @param $status
     * @return mixed
     */
    public static function getPassingTotalStatusArticle($content_id, $status) {
        $articles_ids = ArticleService::pluckIDArticles($content_id);

        return Passing::where('status', $status)
            ->whereRaw('`entity_type` = "Post" AND `entity_id` IN(' . implode(",", $articles_ids) . ')')
            ->count();
    }

}
