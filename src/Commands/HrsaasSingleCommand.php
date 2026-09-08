<?php

namespace Wiltechsteam\HrsaasServiceSingle\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class HrsaasSingleCommand extends Command
{
    protected $signature = 'hrsaas:work';
    protected $description = 'Hrsaas Queue Work';

    public function handle()
    {
        ini_set('memory_limit', '1024M');

        $queue = config('hrsaas.rabbitmq_queue');

        $connection = new AMQPStreamConnection(
            config('hrsaas.rabbitmq_host'),
            config('hrsaas.rabbitmq_port'),
            config('hrsaas.rabbitmq_login'),
            config('hrsaas.rabbitmq_password'),
            config('hrsaas.rabbitmq_vhost', '/'),
        );

        $channel = $connection->channel();
        $channel->queue_declare($queue, true, false, true, false);
        $channel->basic_qos(0, 1, false);
        $channel->basic_consume($queue, 'consumer-' . getmypid(), false, false, false, false, fn($e) => $this->process($e));

        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    private function process($callback): void
    {
        $exchange = $callback->delivery_info['exchange'];
        $channel = $callback->delivery_info['channel'];
        $tag = $callback->delivery_info['delivery_tag'];
        $bodyData = json_decode($callback->body, true);

        try {
            $this->bindEvent($exchange, $bodyData);
            $channel->basic_ack($tag);
            $this->info(date('Y-m-d H:i:s') . ' ' . $exchange . ' - succeed');
        } catch (\Throwable $e) {
            Log::error('Hrsaas Queue Error', [
                'exchange' => $exchange,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            $this->error(date('Y-m-d H:i:s') . ' ' . $exchange . ' - error: ' . $e->getMessage());
            $this->error('File: ' . $e->getFile() . ':' . $e->getLine());

            try {
                $this->publishError($channel, $bodyData, $e);
            } catch (\Throwable $publishException) {
                $this->error('Failed to publish to error queue: ' . $publishException->getMessage());
            }

            $channel->basic_ack($tag);
        }
    }

    private function publishError($channel, array $bodyData, \Throwable $error): void
    {
        $errorQueue = config('hrsaas.rabbitmq_queue_error');

        if (empty($errorQueue)) {
            $this->error("HRSAAS_RABBITMQ_QUEUE_ERROR is not configured, skipping error queue publish");
            return;
        }

        try {
            $channel->exchange_declare($errorQueue, 'direct', false, true, false);
            $channel->queue_declare($errorQueue, true, false, false, false);
            $channel->queue_bind($errorQueue, $errorQueue, $errorQueue);
        } catch (\Throwable $e) {
            $this->error("Failed to setup error queue: " . $e->getMessage());
            return;
        }

        $bodyData['error'] = [
            'code' => $error->getCode(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
            'trace' => $error->getTraceAsString(),
        ];

        $channel->basic_publish(
            new AMQPMessage(json_encode($bodyData), ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]),
            $errorQueue
        );
    }

    private function bindEvent(string $exchangeName, array $bodyData): void
    {
        if (strpos($exchangeName, ':') === false) {
            throw new \Exception('Exchange name is illegality.');
        }

        $eventAlias = explode(':', $exchangeName)[1];
        $eventClass = config('hrsaas.events')[$eventAlias] ?? null;

        if (!$eventClass || !class_exists($eventClass)) {
            throw new \Exception("Event '$eventAlias' is not found.");
        }

        event(new $eventClass($bodyData));
    }
}
