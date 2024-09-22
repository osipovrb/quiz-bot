<?php

namespace App\Implementations\Quiz;

use App\Contracts\IpcInterface;
use App\Dto\EventPayload;
use App\Dto\EventPayloads\AnswerPayload;
use App\Dto\EventPayloads\HintPayload;
use App\Dto\EventPayloads\QuestionPayload;
use App\Dto\EventPayloads\RemainingSecondsPayload;
use App\Dto\EventPayloads\StartPayload;
use App\Enums\EventsEnum;

class Events
{
    public function __construct(private readonly IpcInterface $ipc)
    {
    }

    public function start(int $questionsCount): void
    {
        $payload = new EventPayload(
            EventsEnum::START,
            new StartPayload($questionsCount)
        );

        $this->ipc->send($payload);
    }

    public function question(string $title, int $remainingSeconds): void
    {
        $payload = new EventPayload(
            EventsEnum::QUESTION,
            new QuestionPayload($title, $remainingSeconds)
        );

        $this->ipc->send($payload);
    }

    public function answer(
        bool $isAnswerCorrect,
        int $userId,
        string $answer,
        int $remainingSeconds
    ): void {
        $event = $isAnswerCorrect
            ? EventsEnum::CORRECT_ANSWER
            : EventsEnum::INCORRECT_ANSWER;

        $payload = new EventPayload(
            $event,
            new AnswerPayload($userId, $answer, $remainingSeconds)
        );

        $this->ipc->send($payload);
    }

    public function hint(int $answerLength, string $answerMask): void
    {
        $payload = new EventPayload(
            EventsEnum::HINT,
            new HintPayload($answerLength, $answerMask),
        );

        $this->ipc->send($payload);
    }

    public function remainingSeconds(int $remainingSeconds): void
    {
        $payload = new EventPayload(
            EventsEnum::REMAINING_SECONDS,
            new RemainingSecondsPayload($remainingSeconds),
        );

        $this->ipc->send($payload);
    }

    public function questionTimedOut(): void
    {
        $payload = new EventPayload(EventsEnum::QUESTION_TIMED_OUT);

        $this->ipc->send($payload);
    }
}