<?php

namespace Theodo\QuizBundle\Tests\Model;

use Theodo\QuizBundle\Model\Question;

class QuestionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetChoicesReturnsAllByDefault()
    {
        $question = new Question();

        $correct = array(
            'correct one',
            'correct two',
        );
        $incorrect = array(
            'bad one',
            'bad two',
        );

        $all = array_merge($correct, $incorrect);

        $question->setCorrectAnswers($correct);
        $question->setBadAnswers($incorrect);

        $this->assertCount(count($all), $question->getChoices());
    }

    public function testCanLimitChoicesNumber()
    {
        $question = new Question();

        $correct = array(
            'correct one',
            'correct two',
        );
        $incorrect = array(
            'bad one',
            'bad two',
        );
        $all = array_merge($correct, $incorrect);

        $question->setCorrectAnswers($correct);
        $question->setBadAnswers($incorrect);

        $this->assertCount(3, $question->getChoices(3));
        $this->assertCount(1, array_diff($all, $question->getChoices(3)));
    }
}
