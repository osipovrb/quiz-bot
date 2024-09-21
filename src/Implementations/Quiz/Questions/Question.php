<?php

namespace App\Implementations\Quiz\Questions;

class Question
{
    public function __construct(
        private readonly int $id,
        private readonly string $title,
        private readonly string $answer
    ) {}

    public function getTitle(): string
    {
        return trim($this->title);
    }

    public function getAnswer(): string
    {
        return trim($this->answer);
    }

    public function isAnswerCorrect(string $answer): bool
    {
        return $this->getAnswer() === trim($answer);
    }
}
