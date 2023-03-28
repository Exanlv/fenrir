<?php

declare(strict_types=1);

namespace Tests\Ragnarok\Fenrir\Gateway;

use Ragnarok\Fenrir\Bitwise\Bitwise;
use Ragnarok\Fenrir\Constants\WebsocketEvents;
use Ragnarok\Fenrir\EventHandler;
use Ragnarok\Fenrir\Gateway;
use Fakes\Ragnarok\Fenrir\DataMapperFake;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\Mock;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\EventLoop\LoopInterface;
use React\Promise\Promise;

class GatewayTestCase extends MockeryTestCase
{
    /**
     * @var Mock
     */
    protected $loop;

    /**
     * @var Mock
     */
    protected $websocket;

    protected Gateway $gateway;

    protected array $websocketHandlers = [];

    protected function setUp(): void
    {
        /**
         * @var Mock|LoopInterface
         */
        $this->loop = Mockery::mock('React\EventLoop\LoopInterface');

        $bucketMock = Mockery::mock('overload:Ragnarok\Fenrir\Bucket');
        $bucketMock->shouldReceive('run')->andReturnUsing(fn ($fn) => $fn());

        $websocketMock = Mockery::mock('overload:Ragnarok\Fenrir\Websocket');
        $websocketMock->shouldReceive('on')->andReturnUsing(function (string $event, callable $handler) {
            $this->websocketHandlers[$event] = $handler;
        });

        $websocketMock->shouldReceive('open')->withAnyArgs()->andReturn(new Promise(function ($resolve) {
            $resolve();
        }));

        $websocketMock->shouldReceive('send')->withAnyArgs();
        $websocketMock->shouldReceive('close')->withAnyArgs();

        $this->gateway = new Gateway(
            $this->loop,
            '::token::',
            new Bitwise(123),
            DataMapperFake::get()
        );

        $this->gateway->events = Mockery::mock(EventHandler::class);
        $this->gateway->events->shouldReceive('handle');

        $this->gateway->connect();

        $this->gateway->websocket->shouldHaveReceived('open', [Gateway::WEBSOCKET_URL]);
    }

    protected function mockIncomingMessage(array $message): void
    {
        /**
         * @var Mock
         */
        $messageMock = Mockery::mock(MessageInterface::class);
        $messageMock->shouldReceive('__toString')->andReturn(json_encode($message));

        ($this->websocketHandlers[WebsocketEvents::MESSAGE])($messageMock);
    }

    protected function assertMessageSent(array $message, bool $useBucket = true): void
    {
        $this->gateway->websocket->shouldHaveReceived('send', [json_encode($message), $useBucket]);
    }

    protected function assertMessageNotSent(array $message, bool $useBucket = true): void
    {
        $this->gateway->websocket->shouldNotHaveReceived('send', [json_encode($message), $useBucket]);
    }
}
