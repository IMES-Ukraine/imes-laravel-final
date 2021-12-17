<?php

namespace App\Http\Controllers;

use App\Models\QuestionModeration;
use App\Models\User;
use App\Services\TestService;

class TestModerationController extends Controller
{
    /**
     * accept the specified resource from storage.
     *
     * @param integer $id
     */
    public function accept($id)
    {
        $model = QuestionModeration::findOrFail($id);
        $model->status = QuestionModeration::TEST_MODERATION_ACCEPT;

        if ($model->save()) {
            $bonus = TestService::getTestBonus($model->question_id);

            if ($bonus) {
                $user = User::findOrFail($model->user_id);
                $user->balance = $user->balance + $bonus[0];
                $user->save();
            }
        }
    }

    /**
     * accept the specified resource from storage.
     *
     * @param integer $id
     * @param integer $content_id
     */
    public function acceptComplex($id, $content_id)
    {
        $model = QuestionModeration::findOrFail($id);
        $model->status = QuestionModeration::TEST_MODERATION_ACCEPT;

        if ($model->save()) {
            $bonus = TestService::getTestBonus($model->question_id);

            $success = TestService::getAnswerCorrect($content_id, $model->user_id);

            if ($bonus && $success) {
                TestService::addBalanceAllTests($content_id, $model->user_id);
            }
        }
    }

    /**
     * decline the specified resource from storage.
     *
     * @param integer $id
     */
    public function decline($id)
    {
        $model = QuestionModeration::findOrFail($id);
        $model->status = QuestionModeration::TEST_MODERATION_CANCEL;
        $model->save();
    }
}
