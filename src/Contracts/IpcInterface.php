<?php

namespace App\Contracts;

interface IpcInterface
{
    public function listen(string $channel, callable $callback): void;
    public function send(string $channel, string $message): void;
}
