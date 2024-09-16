<?php

namespace App\Implementations\Quiz;

use App\Contracts\ConfigInterface;
use App\Contracts\IpcInterface;
use App\Contracts\Quiz\BotInterface;
use App\Contracts\Quiz\Questions\QuestionInterface;
use App\Contracts\Quiz\Questions\QuestionsRepositoryInterface;
use App\Dto\EventPayloads\AnswerPayload;
use App\Dto\EventPayloads\QuestionPayload;
use App\Dto\EventPayloads\RemainingSecondsPayload;
use App\Dto\EventPayloads\StartPayload;
use App\Dto\EventPayload;
use App\Enums\EventsEnum;
use React\EventLoop\Loop;

class Bot implements BotInterface
{
    private ?QuestionInterface $currentQuestion = null;
    private ?int $remainingSeconds = null;

    public function __construct(
        private readonly QuestionsRepositoryInterface $repo,
        private readonly IpcInterface $ipc,
        private readonly ConfigInterface $cfg,
    ) {}

    public function start()
    {
        $this->ipc->setListenCallback([$this, 'readUserInput']);

        $payload = new EventPayload(
            EventsEnum::START,
            new StartPayload($this->repo->getQuestionsCount())
        );
        $this->ipc->send($payload);

        $this->nextQuestion();

        Loop::addPeriodicTimer(1, fn() => $this->tick());
    }

    private function tick(): void
    {
        $this->remainingSeconds -= 1;
        if ($this->remainingSeconds === 0) {
            $this->questionTimedOut();
            $this->nextQuestion();
        } else {
            $this->sendRemainingSeconds();
        }
    }

    private function nextQuestion(): void
    {
        $this->currentQuestion = $this->repo->getRandomQuestion();
        $this->remainingSeconds = intval($this->cfg->get('BOT_QUESTION_TIME'));
        $this->sendCurrentQuestion();
    }

    private function sendCurrentQuestion(): void
    {
        $payload = new EventPayload(
            EventsEnum::QUESTION,
            new QuestionPayload(
                $this->currentQuestion->getTitle(),
                $this->remainingSeconds
            )
        );

        $this->ipc->send($payload);
    }

    private function sendRemainingSeconds(): void
    {
        $payload = new EventPayload(
            EventsEnum::REMAINING_SECONDS,
            new RemainingSecondsPayload($this->remainingSeconds),
        );
        $this->ipc->send($payload);
    }

    private function questionTimedOut(): void
    {
        $this->ipc->send(new EventPayload(EventsEnum::QUESTION_TIMED_OUT));
    }

    public function readUserInput(int $userId, string $answer): void
    {
        $isAnswerCorrect = $this->currentQuestion->isAnswerCorrect($answer);

        $event = $isAnswerCorrect
            ? EventsEnum::CORRECT_ANSWER
            : EventsEnum::INCORRECT_ANSWER;

        $payload = new AnswerPayload($userId, $answer, $this->remainingSeconds);
        $this->ipc->send(new EventPayload($event, $payload));

        if ($isAnswerCorrect) {
            $this->nextQuestion();
        }
    }

}
