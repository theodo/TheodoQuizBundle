<?php

namespace Theodo\QuizBundle\Model;

use Theodo\QuizBundle\Model\YamlQuestionManager;

/**
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class YamlQuestionManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testParsesYaml()
    {
        $yaml = file_get_contents(__DIR__ . '/../../Resources/samples/questions.yml');

        $manager = new YamlQuestionManager();
        $questions = $manager->parseYaml($yaml);

        $this->assertCount(2, $questions);

        $question = $questions[0];
        $this->assertInstanceOf('Theodo\QuizBundle\Model\Question', $question);

        $this->assertEquals('Do you like TheodoQuizBundle?', $question->getPhrase());
        $this->assertCount(2, $question->getBadAnswers());
        $this->assertCount(2, $question->getCorrectAnswers());

        $manager->addQuestion($question);
        $this->assertCount(1, $manager->getQuestions());

        $manager->addQuestion($questions[1]);
        $this->assertCount(2, $manager->getQuestions());

        $this->assertCount(1, $manager->draw(1));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testsParsingEmptyThrowsException()
    {
        $manager = new YamlQuestionManager();
        $manager->parseYaml('');
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function testDrawingToMuchThrowsException()
    {
        $manager = new YamlQuestionManager();
        $manager->draw(1);
    }
}
