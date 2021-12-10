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
