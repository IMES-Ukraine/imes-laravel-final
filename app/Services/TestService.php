<?php
namespace App\Services;


use App\Models\TestQuestions as TestModel;

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



}
