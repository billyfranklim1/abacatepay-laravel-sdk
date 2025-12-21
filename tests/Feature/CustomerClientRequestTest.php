<?php

use Billyfranklim\AbacatePay\Clients\CustomerClient;
use Billyfranklim\AbacatePay\Resources\Customer;
use Billyfranklim\AbacatePay\Resources\Customer\Metadata;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

test('customer create envia parâmetros corretos', function () {
    $requestBody = null;
    $requestMethod = null;
    $requestUri = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => ['id' => 'cust_123']
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
    $customerClient = new CustomerClient('test_token', $mockClient);

    $customer = new Customer([
        'metadata' => new Metadata([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'cellphone' => '1234567890',
            'tax_id' => '12345678900'
        ])
    ]);

    $customerClient->create($customer);

    expect($requestMethod)->toBe('POST')
        ->and($requestUri)->toContain('create')
        ->and($requestBody)->toHaveKey('name')
        ->and($requestBody['name'])->toBe('Test Customer')
        ->and($requestBody)->toHaveKey('email')
        ->and($requestBody['email'])->toBe('test@example.com')
        ->and($requestBody)->toHaveKey('cellphone')
        ->and($requestBody['cellphone'])->toBe('1234567890')
        ->and($requestBody)->toHaveKey('taxId')
        ->and($requestBody['taxId'])->toBe('12345678900');
});

test('customer list chama endpoint correto', function () {
    $requestUri = null;
    $requestMethod = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => []
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
    $customerClient = new CustomerClient('test_token', $mockClient);

    $customerClient->list();

    expect($requestMethod)->toBe('GET')
        ->and($requestUri)->toContain('list');
});

