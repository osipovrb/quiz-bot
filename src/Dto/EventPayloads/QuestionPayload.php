<?php

namespace App\Dto\EventPayloads;

class QuestionPayload
{
    function __construct(
        public string $question,
        public int $remainingSeconds,
    ) {}
}
