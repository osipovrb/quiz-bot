<?php

namespace App\Dto\EventPayloads;

class StartPayload
{
    public function __construct(public readonly int $questionsCount)
    {
    }
}
