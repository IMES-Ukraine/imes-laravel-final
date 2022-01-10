<?php

namespace App\Http\Controllers;

use App\Models\QuestionModeration;
use App\Models\User;
use App\Services\TestService;
use Illuminate\Support\Facades\Log;

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
            $variants = TestService::getAllVariants($model->question, $model->user);
            return $this->helpers->apiArrayResponseBuilder(200, 'test accepted', TestService::verifyTest($variants, $model->user, true) );
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
        if ($model->save()) {
            $variants = TestService::getAllVariants($model->question, $model->user);
            return $this->helpers->apiArrayResponseBuilder(200, 'test declined', TestService::verifyTest($variants, $model->user, true) );
        }
    }
}
