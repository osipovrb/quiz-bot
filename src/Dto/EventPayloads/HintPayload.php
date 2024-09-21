<?php

namespace App\Dto\EventPayloads;

class HintPayload
{
    function __construct(
        public readonly int $answerLength,
        public readonly string $answerMask,
    ) {}
}
