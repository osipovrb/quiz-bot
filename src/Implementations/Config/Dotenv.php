<?php

namespace App\Implementations\Config;

use App\Contracts\ConfigInterface;

class Dotenv implements ConfigInterface
{
    private \Dotenv\Dotenv $dotenv;

    public function __construct(string $path)
    {
        $this->dotenv = \Dotenv\Dotenv::createImmutable($path);
        $this->dotenv->load();
    }

    public function get(string $key): string
    {
        $this->dotenv->required($key);

        return $_ENV[$key];
    }
}
