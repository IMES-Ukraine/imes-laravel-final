<?php

namespace App\Http\Controllers;

use App\Models\QuestionModeration;

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
        $model->save();
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
