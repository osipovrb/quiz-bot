<?php

namespace App\Dto;

use App\Dto\EventPayloads\AnswerPayload;
use App\Dto\EventPayloads\HintPayload;
use App\Dto\EventPayloads\QuestionPayload;
use App\Dto\EventPayloads\RemainingSecondsPayload;
use App\Dto\EventPayloads\StartPayload;
use App\Enums\EventsEnum;

class EventPayload
{
    public function __construct(
        public readonly EventsEnum $event,
        public readonly StartPayload|AnswerPayload|QuestionPayload|RemainingSecondsPayload|HintPayload|null $payload = null,
    ) {
    }
}