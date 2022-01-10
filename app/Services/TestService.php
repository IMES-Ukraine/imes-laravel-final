<?php

namespace App\Services;


use App\Models\File;
use App\Models\Passing;
use App\Models\PassingProvider;
use App\Models\Question;
use App\Models\QuestionModeration;
use App\Models\TestQuestions as TestModel;
use App\Models\TestQuestions;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TestService
{
    const TEST_SUBMITTED = 'test_submit';
    const TEST_WILL_BE_MODERATED = 'test_moderate';

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
    public static function getComplexAll($variantsCnt, $research_id): bool
    {
        $count = TestQuestions::where('research_id', $research_id)->where('test_type', 'child')->count();

        return $count == $variantsCnt;
    }

    public static function getAllVariants($model, $user): array
    {
        $variants = [];
        if ($model->test_type === TestQuestions::TYPE_CHILD) {
            $childes = TestQuestions::where('parent_id', $model->parent_id)->get();
            foreach ($childes as $child) {
                $passed = Passing::where('entity_id', $child->id)->where('user_id', $user->id)->first();
                if ($passed) {
                    $variants[] = [
                        'test_id' => $child->id,
                        'variant' => $passed->answer
                    ];
                }
            }

        } else {
            $passed = Passing::where('entity_id', $model->id)->where('user_id', $user->id)->first();
            if ($passed) {
                $variants[] = [
                    'test_id' => $model->id,
                    'variant' => $passed->answer
                ];
            }
        }
        return $variants;
    }

    public static function verifyTest($variants, User $apiUser, $moderating = false): array
    {
Log::info('Варианты на проверку', [$moderating, $variants]);
        $passed = new PassingProvider($apiUser);

        //посчитаем общее число ответов и число правильных ответов
        $fullCount = self::getFullVariantsCount($variants);
        $dummyAnswersCount = 0; //опросы
        $correctAnswersCount = 0; //тесты - число правильных ответов у юзера
        $fullPassingBonus = 0;

        $resType = self::TEST_SUBMITTED;
        foreach ($variants as $var) {
            $testStatus = Passing::PASSING_RESULT_NOT_ACTIVE;

            $userVariants = $var['variant'];
            $test = TestQuestions::find($var['test_id']);
            $correctAnswer = $test->variants['correct_answer'];

            $passModel = $passed->setId($test, Passing::PASSING_ACTIVE, $userVariants);

            $fullPassingBonus = $test->passing_bonus;
            if (empty($correctAnswer)) {
                // Если текущий тест - опросник или текст

                // Текст
                if ($test->answer_type == Question::ANSWER_TEXT) {
                    //Если мы не в режиме модерации
                    if (!$moderating) {
                        //Если хоть в одном тесте тип - текст - ставим флаг для всего набора тестов
//                        $resType = self::TEST_SUBMITTED;
                        $resType = self::TEST_WILL_BE_MODERATED;

                        // Сделаем запись для модерации
                        QuestionModeration::updateOrCreate([
                            'user_id' => $apiUser->id,
                            'question_id' => $var['test_id']
                        ], [
                            'answer' => reset($userVariants),
                            'status' => QuestionModeration::TEST_MODERATION_PENDING
                        ]);
                    } else {
                        // Если тест прошел удачную модерацию - учитываем его в числе правильных ответов.
                        $moderated = QuestionModeration::where('question_id', $test->id)->where('user_id', $apiUser->id)->first();

                        if ($moderated && $moderated->status === QuestionModeration::TEST_MODERATION_ACCEPT) {
                            $passModel->answer = [$moderated->answer];
                            $passModel->result = Passing::PASSING_RESULT_ACTIVE;
                            $passModel->save();
                            $testStatus = Passing::PASSING_RESULT_ACTIVE;
                            $correctAnswersCount++;
                        }
                    }
                } //Опросник
                else {
                    $testStatus = Passing::PASSING_RESULT_ACTIVE;
                    $dummyAnswersCount += count($var['variant']);
                }
            } else {

                //TODO разобраться, может ли быть несколько правильных ответов к одному вопросу
                foreach ($correctAnswer as $answ) {
                    if (in_array($answ, $userVariants)) {
                        $testStatus = Passing::PASSING_RESULT_ACTIVE;
                        $correctAnswersCount++;
                    }
                }
            }
            //если мы в сложном тесте
            if ($test->test_type === TestQuestions::TYPE_CHILD) {
                //отметим родительский тест как открывавшийся
                $testParent = TestQuestions::find($test->parent_id);
                $passedTest = $testParent;
                $fullPassingBonus = $testParent->passing_bonus;
                $parentPassed = $passed->setId($testParent, Passing::PASSING_ACTIVE, []);
            }
            else {
                $passedTest = $test;
            }

            $passModel->result = $testStatus;
            $passModel->save();
        }

        //расчётное максимальное число правильных ответов
        $accountingAnswersCount = $fullCount - $dummyAnswersCount;

        // Проверяем общий результат
        $testStatus = Passing::PASSING_RESULT_NOT_ACTIVE;
        $userPassingBonus = 0;

        if ($accountingAnswersCount > 0) {
            // Если есть вопросы с выбором ответов. Считаем сумму полученных бонусов
            $correctAnswersPercent = ($correctAnswersCount / $accountingAnswersCount) * 100;
            switch (true) {
                case ($correctAnswersPercent < 20):
                    break;
                case ($correctAnswersPercent >= 20 && $correctAnswersPercent < 70):
                    $userPassingBonus = $fullPassingBonus / 2;
                    $testStatus = Passing::PASSING_RESULT_ACTIVE;
                    break;
                case ($correctAnswersPercent >= 70):
                    $userPassingBonus = $fullPassingBonus;
                    $testStatus = Passing::PASSING_RESULT_ACTIVE;
                    break;
                default :
            }
        } else {
            //иначе - мы в одиночном опроснике и отдаём сразу всю сумму бонусов
            $testStatus = Passing::PASSING_RESULT_ACTIVE;
            $userPassingBonus = $fullPassingBonus;
        }
Log::info('Результаты теста ' . $passedTest->title . ' ' . $passedTest->id, [$fullCount, $dummyAnswersCount, $testStatus, $resType, $userPassingBonus]);
        $testOutStatus = TestQuestions::STATUS_PASSED;
        //Если тест пройден
        if ($testStatus === Passing::PASSING_RESULT_ACTIVE && $resType === self::TEST_SUBMITTED && $userPassingBonus) {

            $apiUser->addBalance($userPassingBonus, $passedTest );

            // отметим и родительский тест
            if (isset($parentPassed)) {
                $parentPassed->result = Passing::PASSING_RESULT_ACTIVE;
                $parentPassed->save();
            }
        } else {
            $userPassingBonus = 0;
            if ($resType === self::TEST_SUBMITTED) {
                $testOutStatus = TestQuestions::STATUS_FAILED;
            }
        }

        $resType = self::TEST_SUBMITTED;
        $data = $apiUser->makeHidden(User::TO_HIDE)->toArray();

        return compact('resType', 'userPassingBonus', 'testOutStatus', 'data');
    }

    /**
     * @param $variants
     * @return int
     */
    private
    static function getFullVariantsCount($variants): int
    {
        $fullCount = 0;
        foreach ($variants as $v) {
            if (is_array($v['variant'])){
            $fullCount += count($v['variant']);
            }
        }
        return $fullCount;
    }

    /**
     * Add balance for user if question-answer has accepted
     * @param $content_id
     * @param $user_id
     */
    public
    static function addBalanceAllTests($content_id, $user_id)
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
