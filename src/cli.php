<?php

require 'vendor/autoload.php';

use App\Adapters\Config\Dotenv;
use App\Adapters\Database\Sqlite;
use App\Adapters\Ipc\Console;
use App\Container;
use App\Contracts\ConfigInterface;
use App\Contracts\DatabaseInterface;
use App\Contracts\IpcInterface;
use App\Quiz\Quiz;

$container = new Container();

$container->bind(ConfigInterface::class, function () {
    return new Dotenv(__DIR__);
});

$container->bind(IpcInterface::class, function () {
    return new Console();
});

$container->bind(DatabaseInterface::class, function () use ($container) {
    return new Sqlite($container->get(ConfigInterface::class));
});

try {
    $container->get(DatabaseInterface::class)->connect();
} catch (Exception $e) {
    throw new DomainException("Cannot connect to database: {$e->getMessage()}");
}

// start the game
$quiz = new Quiz(
    $container->get(IpcInterface::class),
    $container->get(ConfigInterface::class),
    $container->get(DatabaseInterface::class),
);
$quiz->start();


