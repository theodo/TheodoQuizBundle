<?php
namespace Theodo\QuizBundle\Model;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Theodo\QuizBundle\Validator\Constraint\CorrectAnswer;

/**
 * @author Marek Kalnik <marekk@theodo.fr>
 */
class Question implements \Serializable
{
    /**
     * Id for peristance layer
     *
     * @var integer
     */
    protected $id;

    /**
     * Stores the question phrase
     *
     * @var string
     */
    protected $phrase;

    /**
     * Stores the user answers
     * @var array
     */
    protected $userAnswers;

    /**
     * Stores the answers that are considered as correct
     * @var array
     */
    protected $correctAnswers;

    /**
     * Stores the answers that are considered as incorrect
     * @var array
     */
    protected $badAnswers;

    /**
     * Local cache for precalculated choices
     * @var array
     */
    protected $choices;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPhrase()
    {
        return $this->phrase;
    }

    public function setPhrase($phrase)
    {
        $this->phrase = $phrase;
    }

    /**
     * Returns a random subset of possible answers.
     * All questions are returned if no parameter provided.
     *
     * @param integer|null $number The number of required answers.
     *
     * @return array A randomized array of correct and incorrect questions
     */
    public function getChoices($number = null)
    {
        if ($this->choices) {
            return $this->choices;
        }

        if ($number) {
            $correct = $this->getCorrectAnswers();
            $incorrect = $this->getBadAnswers();

            shuffle($correct);
            shuffle($incorrect);

            if ($number >= (count($correct) + count($incorrect))) {
                $choices = array_merge($correct, $incorrect);
            } else {
                // At least one question has to be correct
                $nbCorrect = rand(1, $number);
                $correct = array_slice($correct, 0, $nbCorrect);

                $incorrect = array_slice($incorrect, 0, $number - count($correct));

                $choices = array_merge($correct, $incorrect);
            }
        } else {
            $choices = array_merge($this->getBadAnswers(), $this->getCorrectanswers());
        }

        shuffle($choices);
        $this->choices = $choices;

        return $this->choices;
    }

    public function setChoices($choices)
    {
        $this->setUserAnswers($choices);
    }

    public function getUserAnswers()
    {
        return $this->userAnswers;
    }

    public function setUserAnswers(array $useranswers)
    {
        $this->userAnswers = $useranswers;
    }

    public function getCorrectAnswers()
    {
        return $this->correctAnswers;
    }

    public function setCorrectAnswers(array $correctanswers)
    {
        $this->correctAnswers = $correctanswers;
    }

    public function getBadAnswers()
    {
        return $this->badAnswers;
    }

    public function setBadAnswers(array $badanswers)
    {
        $this->badAnswers = $badanswers;
    }

    /**
     * Create the question from definition array
     *
     * @param array $definition
     *
     * @return Question
     */
    public static function fromArray(array $definition)
    {
        $question = new static;

        $question->setPhrase($definition['phrase']);
        $question->setBadAnswers($definition['bad_answers']);
        $question->setCorrectAnswers($definition['correct_answers']);

        return $question;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addConstraint(new CorrectAnswer());
    }

    public function serialize()
    {
        return serialize(array(
            'phrase' => $this->phrase,
            'bad_answers' => $this->badAnswers,
            'correct_answers' => $this->correctAnswers,
            'choices' => $this->choices,
        ));
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        $this->phrase = $data['phrase'];
        $this->badAnswers = $data['bad_answers'];
        $this->correctAnswers = $data['correct_answers'];
        $this->choices = $data['choices'];
    }
}
