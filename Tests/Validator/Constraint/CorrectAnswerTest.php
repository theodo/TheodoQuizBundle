<?php

namespace Theodo\QuizBundle\Tests\Validator\Constraint;

use Theodo\QuizBundle\Validator\Constraint\CorrectAnswer;

class CorrectAnswerTest extends \PHPUnit_Framework_TestCase
{
    public function testIsValidConstraint()
    {
        $constraint = new CorrectAnswer();
    }
}
