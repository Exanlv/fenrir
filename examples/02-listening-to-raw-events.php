<?php

use Ragnarok\Fenrir\Bitwise\Bitwise;
use Ragnarok\Fenrir\Constants\Events;
use Ragnarok\Fenrir\Discord;
use Ragnarok\Fenrir\Websocket\Objects\Payload;

require './vendor/autoload.php';

$discord = new Discord('TOKEN');

$discord
    ->withGateway(new Bitwise(), raw: true)// Enable your desired Gateway intents
    ->withRest();

$discord->gateway->events->on(Events::RAW, function (Payload $payload) {
    echo 'Received event ', $payload->t, PHP_EOL;
});

$discord->gateway->connect(); // Nothing after this line is executed
