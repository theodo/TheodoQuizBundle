<?php

namespace Theodo\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Theodo\QuizBundle\Form\Type\QuestionsType;
use Theodo\QuizBundle\Model\YamlQuestionManager;

class QuestionsController extends Controller
{
    public function askAction(Request $request)
    {
        $manager = new YamlQuestionManager();

        $yamlSource = $this->container->hasParameter('theodo_quiz_questions_file') ?
            $this->container->getParameter('theodo_quiz_questions_file') :
            $this->get('kernel')->getBundle('TheodoQuizBundle')->getPath() . '/Resources/samples/questions.yml';

        
        $questions = $manager->parseYaml($yamlSource);
        shuffle($questions);
        $questions = array_slice($questions, 0, 5);

        $form = $this->get('form.factory')->create(new QuestionsType(), $questions);

        $request->getSession()->set('theodo-quiz/questions', $questions);

        return $this->render('TheodoQuizBundle:Questions:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function answerAction(Request $request)
    {
        $questions = $request->getSession()->get('theodo-quiz/questions');
        $form = $this->get('form.factory')->create(new QuestionsType(), $questions);
        $form->bind($request);

        return $this->render('TheodoQuizBundle:Questions:form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
