<?php

namespace App\Implementations\Quiz\Questions;

use App\Contracts\Quiz\Questions\QuestionInterface;

class Question implements QuestionInterface
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $answer
    ) {}

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function getHint(int $uncoverPercentage = 5): string
    {
        // ...
        return '...';
    }

    public function isAnswerCorrect(string $answer): bool
    {
        return trim($this->answer) === trim($answer);
    }
}
