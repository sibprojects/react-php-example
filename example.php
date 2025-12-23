<?php

require __DIR__ . '/vendor/autoload.php';

use Bunny\Async\Client;
use React\Http\Browser;
use React\EventLoop\Loop;
use React\Promise;

$client  = new Client();
$browser = new Browser();

$app = function ($message, $channel, $client) use ($browser) {
    $body = json_decode($message->content);

    $promises = [];

    foreach ($body->order->items as $item) {
        $promises[] = $browser->post(
            'https://external-service.org/post',
            [],
            http_build_query([
                'sku'    => $item->orderItemNumber,
                'amount' => $item->orderQuantity,
            ])
        );
    }

    Promise\all($promises)->then(
        function () use ($channel, $message) {
            $channel->ack($message);
        }
    );
};

$client
    ->connect()
    ->then(fn (Client $client) => $client->channel())
    ->then(function ($channel) use ($app) {
        $channel->consume($app, 'orders');
    });

Loop::get()->run();
