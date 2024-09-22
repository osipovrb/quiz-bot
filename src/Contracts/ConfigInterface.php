<?php

namespace App\Contracts;

interface ConfigInterface
{
    public function __construct(string $path);

    public function get(string $key): string;
}
