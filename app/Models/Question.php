<?php
namespace App\Models;

use App\Models\File;

class Question
{
    const BUTTONS_TEXT = 'text';
    const BUTTONS_CARD = 'card';

    const ANSWER_VARIANTS = 'variants';
    const ANSWER_TEXT     = 'text';
    const ANSWER_MEDIA     = 'media';

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
    public $answer_type  = self::ANSWER_VARIANTS;
    public $answer_correct  = self::ANSWER_VARIANTS;

    public $isTextAnswerType;


    public $agreement;
    public $options;

    public function __construct( $question)
    {
        $this->title = $question['title'];
        $this->question = $question['text'];
        $this->description = $question['question']['description'];

        $this->agreement = $question['question']['agreement'];
        $this->test_type = $question['type'];

        if ($question['question']['answer']['type'] == self::BUTTONS_CARD) $this->buttonsType = self::BUTTONS_CARD;

        $this->answer_type = $question['question']['answer']['type'];
        $this->answer_correct = $question['question']['answer']['correct'];

        $this->isTextAnswerType = (boolean) ($this->answer_type == self::ANSWER_TEXT);

        $this->passing_bonus = !empty($question['question']['points']) ? $question['question']['points'] : 0;

        $options = [];

        $description = $question['question']['description'];
        if( !empty( $description)) {
            $options[] = [
                'type' => 'description',
                'data' => $description
            ];
        }

        /* buttons with pictures */

        $buttons = [];

        foreach ( $question['variants'] as $variant) {
            $fields = [
                'itemId' => $variant['itemId'],
                'variant' => $variant['text'],
                'title' => $variant['title'],
            ];
            $mediaFields = [];

            if ( isset( $variant['file']) && isset( $variant['file']['id']) && $attachmentId = $variant['file']['id']) {
                $mediaFields = [
                    'description' => $variant['variant'],
                    'file'        => File::find( $attachmentId)
                ];
            }


            if ( $this->buttonsType == self::BUTTONS_CARD) $fields = array_merge($fields, $mediaFields);
            $buttons[] = $fields;
        }

        if ( $this->isTextAnswerType ) {

            $textVariant = $question['variants'][0]['variant'];
            $this->correctAnswer[] = $textVariant;
        }


        $this->variants = array(
            'correct_answer' => $this->answer_correct,
            'type'  => $this->buttonsType,
            'buttons' => $buttons
        );

        $options[] =
            [
                'type' => 'to_learn',
                'data' => $question['question']['link'],
            ];

        if ( isset($question['question']['media']['cover']) && $question['question']['media']['cover']['id'] ) {

            $coverImage = File::find( $question['question']['media']['cover']['id']);
            $this->cover_image = $coverImage;
        }

        if ( isset($question['question']['media']['video']) && isset($question['question']['media']['video']['id']) ) {

            $videoId   = $question['question']['media']['video']['id'];
            $videoFile = File::find($videoId);
            $options[] =
                [
                    'type' => 'video',
                    'data' => $videoFile->path,
                    'file' => $videoFile
                ];
        }

        $this->options = $options;
        $this->is_popular = rand(0,1);

        //$saveStatus = $model->save();

        //


    }

    public function setType( string $type) {
        $this->test_type = $type;
    }

    public function setParentId($id) {
        $this->parent_id = $id;
    }

}
