<?php

namespace App\Dto;

use App\Enums\EventsEnum;

use App\Dto\EventPayloads\StartPayload;
use App\Dto\EventPayloads\AnswerPayload;
use App\Dto\EventPayloads\QuestionPayload;
use App\Dto\EventPayloads\RemainingSecondsPayload;

class EventPayload {
  function __construct(
    public readonly EventsEnum $event,
    public readonly StartPayload|AnswerPayload|QuestionPayload|RemainingSecondsPayload|null $payload = null,
  ) {}
}