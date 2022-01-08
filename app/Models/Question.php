<?php

namespace App\Models;

class Question
{

    const BUTTONS_TEXT = 'text';
    const BUTTONS_CARD = 'card';

    const ANSWER_VARIANTS = 'variants';
    const ANSWER_TEXT = 'text';
    const ANSWER_MEDIA = 'media';

    const TYPE_TO_LEARN = 'to_learn';

    public $title;
    public $question;
    public $duration_seconds = 360;
    public $passing_bonus;
    public $is_popular;
    public $variants;
    public $cover_image;
    public $test_type;
    public $parent_id;

    public $description;

    public $buttonsType = self::BUTTONS_TEXT;
    public $answer_type = self::ANSWER_VARIANTS;
    public $answer_correct = self::ANSWER_VARIANTS;

    public $isTextAnswerType;


    public $agreement;
    public $options;

    public $schedule;
    public $can_retake;

    public function __construct($question)
    {

        $this->title = $question['title'];
        $this->question = $question['text'];

        $this->agreement = $question['question']['agreement'] ?? '';
        $this->test_type = $question['type'];

        if ($question['question']['type'] == self::BUTTONS_CARD) {
            $this->buttonsType = self::BUTTONS_CARD;
        }

        $this->answer_type = $question['question']['type'];
        $this->answer_correct = $question['question']['correct'];

        $this->isTextAnswerType = $this->answer_type == self::ANSWER_TEXT;

        $this->passing_bonus = $question['points'] ??  0;

        $this->schedule = $question['schedule'];
        $this->can_retake = $question['canRetake'];

        $options = [];
        $description = $question['question']['description'] ?? '';
        if (!empty($description)) {
            $options[] = [
                'type' => 'description',
                'data' => $description
            ];
        }

        /* buttons with pictures */

        $buttons = [];

        foreach ($question['question']['variants'] as $variant) {
            $fields = [
                'itemId' => $variant['itemId'],
                'description' => $variant['description'],
                'variant' => $variant['variant'],
                'title' => $variant['title'],
                'file' => $variant['media'][0] ?? null,
            ];


            //АПИ Не предусмотрено более 1 картинки в вариантах ответа
//            $mediaFields = [];
//
//            if (isset($variant['file']['id']) && $attachmentId = $variant['file']['id']) {
//                $mediaFields = [
//                    'description' => $variant['variant'],
//                    'file' => File::find($attachmentId)
//                ];
//            }
//
//
//            if ($this->buttonsType == self::BUTTONS_CARD) {
//                $fields = array_merge($fields, $mediaFields);
//            }


            $buttons[] = $fields;
        }

        if ($this->isTextAnswerType) {
            $textVariant = $question['question']['variants'][0]['variant'];
            $this->correctAnswer[] = $textVariant;
        }


        $this->variants = array(
            'correct_answer' => $this->answer_correct,
            'type' => $this->buttonsType,
            'buttons' => $buttons
        );

        $options[] =
            [
                'type' => self::TYPE_TO_LEARN,
                'data' => $question['article_id'] ? (int)$question['article_id'] : null,
            ];

        if (isset($question['question']['fileType'])  && isset($question['question']['file']['path'])) {
            $options[] =
                [
                    'type' => $question['question']['fileType'],
                    'data' => $question['question']['file']['path'],
                    'file' => $question['question']['file']
                ];
        }
        $this->options = $options;



        $this->is_popular = rand(0, 1);

    }

    public function setType(string $type)
    {
        $this->test_type = $type;
    }

    public function setParentId($id)
    {
        $this->parent_id = $id;
    }

}
