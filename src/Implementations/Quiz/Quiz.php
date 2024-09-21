<?php

namespace App\Implementations\Quiz;

use App\Contracts\ConfigInterface;
use App\Contracts\DatabaseInterface;
use App\Contracts\IpcInterface;
use App\Implementations\Quiz\Questions\Question;
use App\Implementations\Quiz\Questions\QuestionHint;
use App\Implementations\Quiz\Questions\QuestionsRepository;
use React\EventLoop\Loop;

class Quiz
{
    private readonly Config $config;
    private readonly QuestionsRepository $repo;
    private readonly Events $events;

    private ?Question $question;
    private ?QuestionHint $questionHint;
    private ?int $remainingSeconds;

    public function __construct(

        private readonly IpcInterface $ipc,
        private readonly ConfigInterface $baseConfig,
        private readonly DatabaseInterface $database,
    ) {
        $this->config = new Config($this->baseConfig);
        $this->repo = new QuestionsRepository($this->database);
        $this->events = new Events($ipc);
    }

    public function start(): void
    {
        $this->ipc->setListenCallback([$this, 'readUserInput']);

        $this->events->start($this->repo->getQuestionsCount());

        $this->nextQuestion();

        Loop::addPeriodicTimer(1, fn() => $this->tick());
    }

    private function nextQuestion(): void
    {
        // new question
        $this->question = $this->repo->getRandomQuestion();
        $this->remainingSeconds = $this->config->questionTime;
        $this->events->question(
            $this->question->getTitle(),
            $this->remainingSeconds
        );

        // new hint
        $this->questionHint = new QuestionHint($this->question->getAnswer());
    }

    private function tick(): void
    {
        $this->remainingSeconds -= 1;
        $this->checkForHint();
        $this->checkForQuestionTimeout();
    }

    private function checkForHint(): void
    {
        $hintNumber = array_search(
            $this->remainingSeconds,
            $this->config->hintsTiming
        );

        if ($hintNumber === false) {
            return;
        }

        $revealPercentage = $this->config->hintsRevealPercentage[$hintNumber];
        $answerMask = $this->questionHint->getAnswerMask($revealPercentage);
        $answerLength = $this->questionHint->getAnswerLength();

        $this->events->hint($answerLength, $answerMask);
    }

    private function checkForQuestionTimeout(): void
    {
        if ($this->remainingSeconds === 0) {
            $this->events->questionTimedOut();
            $this->nextQuestion();
        } else {
            $this->events->remainingSeconds($this->remainingSeconds);
        }
    }

    public function readUserInput(int $userId, string $answer): void
    {
        $isAnswerCorrect = $this->question->isAnswerCorrect($answer);

        $this->events->answer(
            $isAnswerCorrect,
            $userId,
            $answer,
            $this->remainingSeconds
        );

        if ($isAnswerCorrect) {
            $this->nextQuestion();
        }
    }

}
