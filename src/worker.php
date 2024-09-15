<?php

require 'vendor/autoload.php';

use App\Container;
use App\Contracts\ConfigInterface;
use App\Implementations\Config\Dotenv;

$container = new Container();

$container->bind(ConfigInterface::class, function () {
    return new Dotenv(__DIR__);
});

$config = $container->get(ConfigInterface::class);

