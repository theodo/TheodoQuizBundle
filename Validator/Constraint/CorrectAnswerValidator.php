<?php

namespace Theodo\QuizBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Theodo\QuizBundle\Model\Question;

class CorrectAnswerValidator extends ConstraintValidator
{
    public function validate($question, Constraint $constraint)
    {
        if (!($question instanceof Question)) {
            throw new \Exception('This validator handles only Question objects');
        }

        $answers = array_intersect_key($question->getChoices(), array_flip($question->getUserAnswers()));

        $violationText = array($question->getPhrase() . ':');

        if (count($this->getIncorrectAnswers($answers, $question))) {
            $violationText[] = 'There were some incorrect answers.';
        }

        if (count($this->getMissingCorrectAnswers($answers, $question))) {
            $violationText[] = 'Not all correct answers were selected.';
        }

        if (count($violationText) > 1) {
            $this->context->addViolation(implode(' ', $violationText));
        }
    }

    private function getIncorrectAnswers($answers, $question)
    {
        return array_intersect($answers, $question->getBadAnswers());
    }

    private function getMissingCorrectAnswers($answers, $question)
    {
        return array_diff($question->getCorrectAnswers(), $answers);
    }
}
