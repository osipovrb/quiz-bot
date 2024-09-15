<?php

namespace App\Contracts\Quiz\Questions;

interface QuestionInterface
{
    public function getTitle(): string;
    public function getAnswer(): string;
    public function getHint(int $uncoverPercentage = 5): string;
    public function isAnswerCorrect(string $answer): bool;
}
