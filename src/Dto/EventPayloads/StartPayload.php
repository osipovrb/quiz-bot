<?php

namespace App\Dto\EventPayloads;

class StartPayload
{
    function __construct(public readonly int $questionsCount)
    {}
}
