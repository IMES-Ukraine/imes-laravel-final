<?php

namespace App\Services;


use App\Models\File;
use App\Models\QuestionModeration;
use App\Models\TestQuestions as TestModel;
use App\Models\TestQuestions;
use App\Models\User;

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
        $tests = TestQuestions::where('research_id', $content_id)->get();

        foreach ($tests as $test) {
            if ($test->answer_type == 'text') {
                $test_moderation = QuestionModeration::where('user_id', $user_id)->where('question_id', $test->id)->pluck('status');

                if (isset($test_moderation[0]) && $test_moderation[0] != 'accept') {
                    $success = false;
                    break;
                }
            }
        }

        return $success;
    }

    /**
     * Return if complex answer all
     * @param $variants
     * @param $research_id
     * @return boolean
     */
    public static function getComplexAll($variantsCnt, $research_id)
    {
        $count = TestQuestions::where('research_id', $research_id)->where('test_type', 'child')->count();

        return $count == $variantsCnt;
    }

    /**
     * Return if complex answer all
     * @param $variants
     * @return boolean
     */
    public static function ifComplexAnswerQuestion($variants)
    {
        $answerQuestion = false;
        foreach ($variants as $variant) {
            $test = TestQuestions::find($variant->test_id)->where('answer_type', 'text')->first();

            if ($test) {
                $answerQuestion = true;
                break;
            }
        }

        return $answerQuestion;
    }

    /**
     * Add balance for user if question-answer has accept
     * @param $content_id
     * @param $user_id
     */
    public static function addBalanceAllTests($content_id, $user_id)
    {
        $tests = TestQuestions::where('research_id', $content_id)->all();

        foreach ($tests as $test) {
            if ($test->test_type == 'child') {
                $user = User::findOrFail($user_id);
                $user->balance = $user->balance + $test->passing_bonus;
                $user->save();
            }
        }
    }
}
