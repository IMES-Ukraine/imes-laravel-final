<?php

namespace App\Services;


use App\Models\File;
use App\Models\QuestionModeration;
use App\Models\TestQuestions as TestModel;
use App\Models\TestQuestions;

class TestService
{
    /**
     * @param $type
     * @param $question
     * @return mixed
     */
    public function addQuestion($question)
    {

        $complexTestParentModel = new TestModel();
        $complexTestParentModel->fill((array)$question);
        //$complexTestParentModel->test_type = $test->getTestType();

        $complexTestParentModel->save();

        return $complexTestParentModel;
    }


    /**
     * Return pluck ID for Test
     * @param $content_id
     * @return array
     */
    public static function pluckIDArticles($content_id)
    {
        return TestQuestions::select('id')->where('research_id', $content_id)->pluck('id')->toArray();
    }

    public static function setAttachment($model, $testId)
    {
//        dd($model, $testId);
        if ($testId) {
            if (isset($model['cover']['id'])) {
                $img = File::find($model['cover']['id']);
                if($img) {
                    $img->attachment_id = $testId;
                    $img->field = File::FIELD_COVER;
                    $img->save();
                }
            }
            if (isset($model['img']['id'])) {
                $img = File::find($model['img']['id']);
                if($img) {
                    $img->attachment_id = $testId;
                    $img->field = File::FIELD_IMAGE;
                    $img->save();
                }
            }
            if (isset($model['video']['id'])) {
                $img = File::find($model['video']['id']);
                if($img) {
                    $img->attachment_id = $testId;
                    $img->field = File::FIELD_VIDEO;
                    $img->save();
                }
            }
            if (isset($model['variants'])) {
                foreach ($model['variants'] as $key => $variant) {
                    if (isset($variant['media'][0])) {
                        $img = File::find($variant['media'][0]['id']);
                        $img->attachment_id = $testId;
                        $img->save();
                    }
                }
            }
        }
    }

    /**
     * Return bonus
     * @param $test_id
     * @return array
     */
    public static function getTestBonus($test_id)
    {
        return TestQuestions::select('passing_bonus')->where('id', $test_id)->pluck('passing_bonus');
    }

    /**
     * Return success answer
     * @param $content_id
     * @param $user_id
     * @return boolean
     */
    public static function getAnswerCorrect($content_id, $user_id)
    {
        $success = true;
        $tests = TestQuestions::where('research_id', $content_id)->all();

        foreach ($tests as $test) {
            if ($test->answer_type == 'text') {
                $test_moderation = QuestionModeration::where('user_id', $user_id)->where('question_id', $test->id)->pluck('status');

                if (isset($test_moderation[0]) && $test_moderation[0] != 'accept') {
                    $success = false;
                    break;
                }
            } else {
                //
            }
        }

        return $success;
    }
}
