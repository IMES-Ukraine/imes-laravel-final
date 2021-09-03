<?php
namespace App\Models;


class Test implements \Iterator
{
    const TEST_SIMPLE  = 'simple';
    const TEST_COMPLEX = 'complex';
    const TEST_CHILD   = 'child';

    public $isComplex = false;
    public $parentId = null;
    public $isSaved = false;
    public $entityId = null;
    public $questions;

    private $position;
    private $testType = self::TEST_SIMPLE;


    public function __construct( $questions)
    {
        $this->isComplex = count($questions) === 1 ? false : true;
        if ( $this->isComplex) $this->testType = self::TEST_COMPLEX;

        foreach ( $questions as $question) {
            $testQuestion = new Question($question);
            $this->questions[] = $testQuestion;
        }
    }

    /**
     * Получим айди теста или айди теста-родителя
     */
    public function setEntityId( $model) {

        if ( is_int( $this->parentId)) {
            $this->entityId = $this->parentId;
        } else {
            $this->entityId = $model->id;
        }
    }

    public function hasParent() {
        return (bool) $this->parentId;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->questions[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {

        $this->testType = self::TEST_CHILD;
        ++$this->position;
    }

    public function valid() {
        return isset($this->questions[$this->position]);
    }

    public function getTestType() {
        if ( $this->parentId) {
            $this->testType = self::TEST_CHILD;
        }

        return $this->testType;
    }

}
