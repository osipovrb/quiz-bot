<?php

namespace App\Contracts;

use App\Dto\EventPayload;

interface IpcInterface
{
    public function __construct();

    public function setListenCallback(callable $callback): void;

    public function send(EventPayload $payload): void;
}
