<?php

namespace Theodo\QuizBundle\Model;

use Symfony\Component\Yaml\Yaml;

/**
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class YamlQuestionManager
{
    private $questions = array();

    public function parseYaml($yaml)
    {
        $items = Yaml::parse($yaml);

        if (!$items) {
            throw new \InvalidArgumentException('The passed Yaml is empty');
        }

        $questions = array();

        foreach ($items as $item) {
            $questions[] = Question::fromArray($item);
        }

        return $questions;
    }

    /**
     * Draws a given number of random Questions from inner array
     */
    public function draw($number)
    {
        if ($number > count($this->questions)) {
            throw new \OutOfRangeException('Can not draw ' .  $number . ' questions as only ' . count($this->questions) . ' in memory.');
        }

        return (array) array_rand($this->questions, $number);
    }

    public function addQuestion(Question $question)
    {
        $this->questions[] = $question;
    }

    public function getQuestions()
    {
        return $this->questions;
    }
}
