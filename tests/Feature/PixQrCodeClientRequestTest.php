<?php

use Billyfranklim\AbacatePay\Clients\PixQrCodeClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

test('pixQrCode create envia parâmetros corretos', function () {
    $requestBody = null;
    $requestMethod = null;
    $requestUri = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => ['id' => 'pix_123']
        ]))
    ]);

    $handlerStack = HandlerStack::create($handler);
    $handlerStack->push(function (callable $handler) use (&$requestBody, &$requestMethod, &$requestUri) {
        return function (RequestInterface $request, array $options) use ($handler, &$requestBody, &$requestMethod, &$requestUri) {
            $requestMethod = $request->getMethod();
            $requestUri = (string) $request->getUri();
            $requestBody = json_decode($request->getBody()->getContents(), true);
            return $handler($request, $options);
        };
    });

    $mockClient = new Client(['handler' => $handlerStack]);
    $pixClient = new PixQrCodeClient('test_token', $mockClient);

    $pixClient->create([
        'amount' => 1000,
        'expiresIn' => 3600,
        'description' => 'Test payment',
    ]);

    expect($requestMethod)->toBe('POST')
        ->and($requestUri)->toContain('create')
        ->and($requestBody)->toHaveKey('amount')
        ->and($requestBody['amount'])->toBe(1000)
        ->and($requestBody)->toHaveKey('expiresIn')
        ->and($requestBody['expiresIn'])->toBe(3600)
        ->and($requestBody)->toHaveKey('description')
        ->and($requestBody['description'])->toBe('Test payment');
});

test('pixQrCode check chama endpoint correto com query parameter', function () {
    $requestUri = null;
    $requestMethod = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => ['id' => 'pix_123']
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
    $pixClient = new PixQrCodeClient('test_token', $mockClient);

    $pixClient->check('pix_char_abc123');

    expect($requestMethod)->toBe('GET')
        ->and($requestUri)->toContain('check')
        ->and($requestUri)->toContain('id=pix_char_abc123');
});

test('pixQrCode simulatePayment envia parâmetros corretos', function () {
    $requestBody = null;
    $requestUri = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => ['id' => 'pix_123']
        ]))
    ]);

    $handlerStack = HandlerStack::create($handler);
    $handlerStack->push(function (callable $handler) use (&$requestBody, &$requestUri) {
        return function (RequestInterface $request, array $options) use ($handler, &$requestBody, &$requestUri) {
            $requestUri = (string) $request->getUri();
            $requestBody = json_decode($request->getBody()->getContents(), true);
            return $handler($request, $options);
        };
    });

    $mockClient = new Client(['handler' => $handlerStack]);
    $pixClient = new PixQrCodeClient('test_token', $mockClient);

    $metadata = ['source' => 'test'];
    $pixClient->simulatePayment('pix_char_abc123', $metadata);

    expect($requestUri)->toContain('simulate-payment')
        ->and($requestUri)->toContain('id=pix_char_abc123')
        ->and($requestBody)->toHaveKey('metadata')
        ->and($requestBody['metadata'])->toBe($metadata);
});

