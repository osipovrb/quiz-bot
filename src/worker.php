<?php

require 'vendor/autoload.php';

use App\Container;
use App\Contracts\ConfigInterface;
use App\Contracts\DatabaseInterface;
use App\Contracts\IpcInterface;
use App\Contracts\Quiz\Questions\QuestionsRepositoryInterface;
use App\Contracts\Quiz\Questions\QuestionsSeederInterface;
use App\Implementations\Config\Dotenv;
use App\Implementations\Database\Sqlite;
use App\Implementations\Ipc\RabbitMq;
use App\Implementations\Quiz\Questions\QuestionsSeeder;
use App\Implementations\Quiz\Questions\QuestionsRepository;

$container = new Container();

// Bindings
$container->bind(ConfigInterface::class, function () {
    return new Dotenv(__DIR__);
});

$container->bind(IpcInterface::class, function () use ($container) {
    return new RabbitMq($container->get(ConfigInterface::class));
});

$container->bind(DatabaseInterface::class, function () use ($container) {
    return new Sqlite($container->get(ConfigInterface::class));
});

$container->bind(QuestionsRepositoryInterface::class, function () use ($container) {
    return new QuestionsRepository($container->get(DatabaseInterface::class));
});

$container->bind(QuestionsSeederInterface::class, function() use ($container) {
    return new QuestionsSeeder(
        $container->get(DatabaseInterface::class),
        $container->get(QuestionsRepositoryInterface::class)
    );
});

// trigger seeder
$container->get(DatabaseInterface::class)->connect();
$container->get(QuestionsSeederInterface::class)->seed();
