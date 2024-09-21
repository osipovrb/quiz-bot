<?php

require 'vendor/autoload.php';

use App\Container;
use App\Contracts\ConfigInterface;
use App\Contracts\DatabaseInterface;
use App\Contracts\IpcInterface;
use App\Exceptions\ContainerException;
use App\Implementations\Config\Dotenv;
use App\Implementations\Database\Sqlite;
use App\Implementations\Ipc\Console;
use App\Implementations\Quiz\Quiz;

$container = new Container();

// Bindings
$container->bind(ConfigInterface::class, function () {
    return new Dotenv(__DIR__);
});

$container->bind(IpcInterface::class, function () use ($container) {
    return new Console();
});

$container->bind(DatabaseInterface::class, function () use ($container) {
    return new Sqlite($container->get(ConfigInterface::class));
});

$container->bind(IpcInterface::class, function () {
    return new Console();
});

// database connect
try {
    $container->get(DatabaseInterface::class)->connect();
} catch (Exception $e) {
    throw new DomainException("Cannot connect to database: {$e->getMessage()}");
}

// start the game
try {
    $quiz = new Quiz(
        $container->get(IpcInterface::class),
        $container->get(ConfigInterface::class),
        $container->get(DatabaseInterface::class),
    );
    $quiz->start();
} catch (ContainerException $e) {
    throw new DomainException("Cannot start quiz: {$e->getMessage()}");
}

