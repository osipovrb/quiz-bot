<?php
/*
namespace App\Implementations\Ipc;

use App\Contracts\ConfigInterface;
use App\Contracts\IpcInterface;
use Exception;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMq implements IpcInterface
{
    private $connection;
    private $channel;
    private $channelName;
    private $listenCallback;

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

    public function setListenCallback(callable $listenCallback): void
    {
        $this->listenCallback = $listenCallback;
    }

    public function listen(): void
    {
        if (!is_callable($this->listenCallback)) {
            throw new Exception('Listen callback is not set');
        }

        $parseMessage = function ($msg) {
            $json = json_decode($msg, true);
            call_user_func(
                [$this, 'listenCallback'],
                $json['user_id'],
                $json['message']
            );
        };

        $this->channel->basic_consume(
            $this->channelName,
            '',
            false,
            true,
            false,
            false,
            $parseMessage
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function send(string $event, array $payload): void
    {
        $this->channel->basic_publish(json_encode($payload));
    }
}
*/
