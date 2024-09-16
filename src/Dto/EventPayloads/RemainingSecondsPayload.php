<?php

namespace App\Dto\EventPayloads;

class RemainingSecondsPayload
{
    function __construct(public readonly int $remainingSeconds)
    {}
}
