<?php

namespace App\Implementations\Ipc;

use App\Contracts\IpcInterface;
use App\Dto\EventPayloads\AnswerPayload;
use App\Dto\EventPayloads\HintPayload;
use App\Dto\EventPayloads\QuestionPayload;
use App\Dto\EventPayloads\RemainingSecondsPayload;
use App\Dto\EventPayloads\StartPayload;
use App\Dto\EventPayload;
use App\Enums\EventsEnum;
use Clue\React\Stdio\Stdio;

class Console implements IpcInterface
{
    private readonly Stdio $stdio;

    public function __construct()
    {
        $this->stdio = new Stdio();
    }

    public function setListenCallback(callable $callback): void
    {
        $this->stdio->on('data', function ($line) use ($callback) {
            call_user_func($callback, 0, rtrim($line, "\r\n"));
        });
    }

    public function listen(?string $prompt): void
    {
        $this->stdio->setPrompt($prompt ?? '> ');
    }

    public function send(EventPayload $payload): void// TODO: dto
    {
        $routes = [
            EventsEnum::START->value => 'sendStart',
            EventsEnum::QUESTION->value => 'sendQuestion',
            EventsEnum::REMAINING_SECONDS->value => 'sendRemainingSeconds',
            EventsEnum::HINT->value => 'sendHint',
            EventsEnum::CORRECT_ANSWER->value => 'sendCorrectAnswer',
            EventsEnum::INCORRECT_ANSWER->value => 'sendIncorrectAnswer',
            EventsEnum::QUESTION_TIMED_OUT->value => 'sendQuestionTimedOut',
        ];

        call_user_func([$this, $routes[$payload->event->value]], $payload->payload);
    }

    private function sendStart(StartPayload $payload): void
    {
        $this->stdio->write("Викторина началась! В базе данных ");
        $this->stdio->write("$payload->questionsCount вопросов" . PHP_EOL);
        $this->stdio->write("Для выхода нажмите CTRL+C" . PHP_EOL);
    }

    private function sendQuestion(QuestionPayload $payload): void
    {
        $this->stdio->write("Внимание, вопрос: $payload->question ");
        $this->stdio->write("На ответ $payload->remainingSeconds сек" . PHP_EOL);
        $this->listen("[ $payload->remainingSeconds ] > ");
    }

    private function sendRemainingSeconds(RemainingSecondsPayload $payload): void
    {
        $this->listen("[ $payload->remainingSeconds ] > ");
    }

    private function sendHint(HintPayload $payload): void
    {
        $this->stdio->write(
            "Подсказка: $payload->answerMask (всего букв: $payload->answerLength)"
            . PHP_EOL
        );
    }

    private function sendCorrectAnswer(AnswerPayload $payload): void
    {
        $this->stdio->write(
            "\033[32mВерно!\033[0m Правильный ответ - \"$payload->answer\"."
            . PHP_EOL
        );
    }

    private function sendIncorrectAnswer(AnswerPayload $payload): void
    {
        $this->stdio->write("\033[31mНеверно!\033[0m Попробуйте ещё." . PHP_EOL);
        $this->listen("[ $payload->remainingSeconds ] > ");

    }
    private function sendQuestionTimedOut(): void
    {
        $this->stdio->write("\033[33mВы не ответили на вопрос :(\033[0m" . PHP_EOL);
    }
}
