<?php

namespace App\Dto\EventPayloads;

class HintPayload
{
    public function __construct(
        public readonly int $answerLength,
        public readonly string $answerMask
    ) {
    }
}
