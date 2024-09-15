<?php

namespace App\Implementations\Ipc;

use App\Contracts\ConfigInterface;
use App\Contracts\IpcInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMq implements IpcInterface
{
    private $connection;
    private $channel;
    private $channelName;

    public function __construct(ConfigInterface $config)
    {
        $this->connection = new AMQPStreamConnection(
            $config->get('RABBITMQ_HOST'),
            $config->get('RABBITMQ_PORT'),
            $config->get('RABBITMQ_USER'),
            $config->get('RABBITMQ_PASSWORD'),
        );

        $this->channel = $this->connection->channel();

        $this->channelName = $config->get('RABBITMQ_QUEUE');

        $this->channel->queue_declare($this->channelName);

    }

    public function listen(callable $callback): void
    {
        $this->channel->basic_consume(
            $this->channelName, 
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

    public function send(string $message): void
    {
        $this->channel->basic_publish($message);
    }
}
