<?php

namespace Theodo\QuizBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

class CorrectAnswer extends Constraint
{
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
