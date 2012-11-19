<?php

namespace Theodo\QuizBundle\Tests\Form\Type;

use Symfony\Component\Form\Tests\Extension\Core\Type\TypeTestCase;
use Theodo\QuizBundle\Form\Type\QuestionAnswerType;
use Theodo\QuizBundle\Model\Question;

class QuestionAnswersTypeTest extends TypeTestCase
{
    public function testCreateForm()
    {
        $type = new QuestionAnswerType();
        $question = \Phake::partialMock('Theodo\QuizBundle\Model\Question');
        $question->setCorrectAnswers(array(
            'Test 1',
            'Test 2',
        ));

        $question->setBadAnswers(array(
            'Test 3',
            'Test 4',
        ));
        $question->setPhrase('Which test is the best?');

        $form = $this->factory->create($type, $question);
        $view = $form->createView();

        $this->assertInstanceOf('Theodo\QuizBundle\Model\Question', $form->getData());
        $this->assertArrayHasKey('choices', $view->children);
        $this->assertEquals('Which test is the best?', $view->children['choices']->get('label'));
    } 
}
