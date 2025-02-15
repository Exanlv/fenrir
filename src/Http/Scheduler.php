<?php

declare(strict_types=1);

namespace Ragnarok\Fenrir\Http;

use Discord\Http\Endpoint;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use React\Promise\Promise;
use React\Promise\PromiseInterface;

class Scheduler
{
    private array $processors = [];

    public function __construct(
        private readonly LoopInterface $loop,
        private readonly Client $client,
        private readonly LoggerInterface $log,
    ) {
    }

    public function request(Verb $verb, Endpoint $endpoint, mixed $content = null, array $headers = []): PromiseInterface
    {
        $key = $endpoint->toAbsoluteEndpoint(true);

        if (!isset($this->processors[$key])) {
            $this->log->debug('Creating new HTTP processor', [$key]);

            $this->processors[$key] = new Processor($this->loop, $key, $this->log);
        }

        /** @var Processor */
        $processor = &$this->processors[$key];

        $processor->on(
            Processor::DESTRUCT,
            function () use ($key) {
                $this->log->debug('Destructing HTTP processor', [$key]);

                unset($this->processors[$key]);
            }
        );

        $this->log->debug('Queueing request', [$key]);

        return new Promise(function (callable $resolver) use ($processor, $verb, $endpoint, $content, $headers) {
            $processor->queue(
                $resolver,
                new Job(
                    $this->client,
                    $verb,
                    $endpoint,
                    $content,
                    $headers,
                ),
            );

            $processor->start();
        });
    }

    public function get(Endpoint $endpoint, mixed $content = null, array $headers = []): PromiseInterface
    {
        return $this->request(Verb::GET, $endpoint, $content, $headers);
    }

    public function head(Endpoint $endpoint, mixed $content = null, array $headers = []): PromiseInterface
    {
        return $this->request(Verb::HEAD, $endpoint, $content, $headers);
    }

    public function post(Endpoint $endpoint, mixed $content = null, array $headers = []): PromiseInterface
    {
        return $this->request(Verb::POST, $endpoint, $content, $headers);
    }

    public function put(Endpoint $endpoint, mixed $content = null, array $headers = []): PromiseInterface
    {
        return $this->request(Verb::PUT, $endpoint, $content, $headers);
    }

    public function delete(Endpoint $endpoint, mixed $content = null, array $headers = []): PromiseInterface
    {
        return $this->request(Verb::DELETE, $endpoint, $content, $headers);
    }

    public function connect(Endpoint $endpoint, mixed $content = null, array $headers = []): PromiseInterface
    {
        return $this->request(Verb::CONNECT, $endpoint, $content, $headers);
    }

    public function options(Endpoint $endpoint, mixed $content = null, array $headers = []): PromiseInterface
    {
        return $this->request(Verb::OPTIONS, $endpoint, $content, $headers);
    }

    public function trace(Endpoint $endpoint, mixed $content = null, array $headers = []): PromiseInterface
    {
        return $this->request(Verb::TRACE, $endpoint, $content, $headers);
    }

    public function patch(Endpoint $endpoint, mixed $content = null, array $headers = []): PromiseInterface
    {
        return $this->request(Verb::PATCH, $endpoint, $content, $headers);
    }
}
