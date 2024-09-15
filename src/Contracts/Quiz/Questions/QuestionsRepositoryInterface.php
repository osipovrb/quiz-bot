<?php

namespace App\Contracts\Quiz\Questions;

use App\Contracts\Quiz\Questions\QuestionInterface;

interface QuestionsRepositoryInterface
{
    public function getRandomQuestion(): QuestionInterface;
    public function getQuestionsCount(): int;
}
