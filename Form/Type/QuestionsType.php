<?php

namespace Theodo\QuizBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * QuestionsType.
 *
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class QuestionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($options['data'] as $key => $question) {
            $builder->add('question_' . ($key + 1), new QuestionAnswerType(), array(
                'data' => $question,
            ));
        }
    }

    public function getName()
    {
        return 'theodo_quiz_questions';
    }
}
