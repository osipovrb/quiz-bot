<?php

namespace App\Dto\EventPayloads;

class QuestionPayload
{
    public function __construct(
        public string $question,
        public int $remainingSeconds,
    ) {
    }
}
