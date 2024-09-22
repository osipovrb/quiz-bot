<?php

namespace App\Dto\EventPayloads;

class RemainingSecondsPayload
{
    public function __construct(public readonly int $remainingSeconds)
    {
    }
}
