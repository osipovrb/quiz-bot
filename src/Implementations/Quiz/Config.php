<?php

namespace App\Implementations\Quiz;

use App\Contracts\ConfigInterface;

class Config
{
    public readonly int $questionTime;
    public readonly array $hintsTiming;
    public readonly array $hintsRevealPercentage;

    public function __construct(
        private readonly ConfigInterface $cfg,
    ) {
        $this->questionTime = intval($this->cfg->get('QUIZ_QUESTION_TIME'));
        $this->hintsTiming = array_map(
            "intval",
            explode(',', $this->cfg->get('QUIZ_HINTS_TIMING'))
        );
        $this->hintsRevealPercentage = array_map(
            "intval",
            explode(',', $this->cfg->get('QUIZ_HINTS_REVEAL_PERCENTAGE'))
        );
    }
}