<?php

namespace App\Dto\EventPayloads;

class AnswerPayload
{
    function __construct(
        public readonly int $userId,
        public readonly string $answer,
        public readonly int $remainingSeconds,
    ) {}
}
