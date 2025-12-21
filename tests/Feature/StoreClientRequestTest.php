<?php

use Billyfranklim\AbacatePay\Clients\StoreClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

test('store get chama endpoint correto', function () {
    $requestUri = null;
    $requestMethod = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => ['id' => 'store_123']
        ]))
    ]);

    $handlerStack = HandlerStack::create($handler);
    $handlerStack->push(function (callable $handler) use (&$requestUri, &$requestMethod) {
        return function (RequestInterface $request, array $options) use ($handler, &$requestUri, &$requestMethod) {
            $requestMethod = $request->getMethod();
            $requestUri = (string) $request->getUri();
            return $handler($request, $options);
        };
    });

    $mockClient = new Client(['handler' => $handlerStack]);
    $storeClient = new StoreClient('test_token', $mockClient);

    $storeClient->get();

    expect($requestMethod)->toBe('GET')
        ->and($requestUri)->toContain('get');
});

