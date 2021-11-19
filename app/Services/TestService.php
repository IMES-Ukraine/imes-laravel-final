<?php
namespace App\Services;


use App\Models\TestQuestions as TestModel;
use App\Models\TestQuestions;

class TestService
{
    /**
     * @param $type
     * @param $question
     * @return mixed
     */
    public function addQuestion( $question) {

        $complexTestParentModel = new TestModel();
        $complexTestParentModel->fill( (array) $question);
        //$complexTestParentModel->test_type = $test->getTestType();

        $complexTestParentModel->save();

        return $complexTestParentModel;
    }

    public function updateQuestion() {

    }

    /**
     * Return pluck ID for Test
     * @param $content_id
     * @return array
     */
    public static function pluckIDArticles($content_id) {
        return TestQuestions::select('id')->where('research_id', $content_id)->pluck('id')->toArray();
    }

}
