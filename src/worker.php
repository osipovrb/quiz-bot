<?php

require 'vendor/autoload.php';

use App\Container;
use App\Contracts\ConfigInterface;
use App\Contracts\IpcInterface;
use App\Implementations\Config\Dotenv;
use App\Implementations\Ipc\RabbitMq;

$container = new Container();

// Bindings
$container->bind(ConfigInterface::class, function () {
    return new Dotenv(__DIR__);
});

$container->bind(IpcInterface::class, function () use ($container) {
    return new RabbitMq($container->get(ConfigInterface::class));
});
