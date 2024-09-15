<?php

namespace App\Implementations\Ipc;

use App\Contracts\ConfigInterface;
use App\Contracts\IpcInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMq implements IpcInterface
{
    private $connection;
    private $channel;

    public function __construct(ConfigInterface $config)
    {
        $this->connection = new AMQPStreamConnection(
            $config->get('QUEUE_HOST'),
            $config->get('QUEUE_PORT'),
            $config->get('QUEUE_USER'),
            $config->get('QUEUE_PASSWORD'),
        );

        $this->channel = $this->connection->channel();

        $this->channel->queue_declare($config->get('QUEUE_NAME'));
    }

    public function listen(string $channel, callable $callback): void
    {
        $this->channel->basic_consume(
            $channel, 
            '', 
            false, 
            true, 
            false, 
            false, 
            $callback
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function send(string $channel, string $message): void
    {
        $this->channel->basic_publish($message);
    }
}
