<?php

namespace App\Contracts;

interface IpcInterface
{
    public function listen(callable $callback): void;
    public function send(string $message): void;
}
