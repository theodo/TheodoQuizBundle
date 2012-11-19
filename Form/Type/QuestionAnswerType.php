<?php

namespace Theodo\QuizBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class QuestionAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['data'])) {
            throw new \LogicException('No data provided');
        }

        $builder->add('choices', 'choice', array(
            'multiple' => true,
            'expanded' => true,
            'choices' => $options['data']->getChoices($options['choices_number']),
            'label' => $options['data']->getPhrase(),
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->set('label', '');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'model_class' => 'Theodo\QuizBundle\Model\Question',
            'choices_number' => 4,
            'error_mapping' => array(
                '.' => 'choices',
            ),
            'error_bubbling' => false,
        ));
    }

    public function getName()
    {
        return 'theodo_quiz_question_response';
    }
}

