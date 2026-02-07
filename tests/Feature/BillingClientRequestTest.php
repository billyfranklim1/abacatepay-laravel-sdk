<?php

use Billyfranklim\AbacatePay\Clients\BillingClient;
use Billyfranklim\AbacatePay\Enums\Billing\Frequencies;
use Billyfranklim\AbacatePay\Enums\Billing\Methods;
use Billyfranklim\AbacatePay\Resources\Billing;
use Billyfranklim\AbacatePay\Resources\Billing\Metadata as BillingMetadata;
use Billyfranklim\AbacatePay\Resources\Billing\Product;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

test('billing create envia parâmetros corretos', function () {
    $requestBody = null;
    $requestMethod = null;
    $requestUri = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => ['id' => 'bill_123'],
        ])),
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
    $billingClient = new BillingClient('test_token', $mockClient);

    $billing = new Billing([
        'frequency' => Frequencies::ONE_TIME,
        'methods' => [Methods::PIX],
        'products' => [
            new Product([
                'external_id' => 'product-1',
                'name' => 'Test Product',
                'quantity' => 1,
                'price' => 1000,
            ]),
        ],
        'metadata' => new BillingMetadata([
            'return_url' => 'https://return.url',
            'completion_url' => 'https://completion.url',
        ]),
    ]);

    $billingClient->create($billing);

    expect($requestMethod)->toBe('POST')
        ->and($requestUri)->toContain('create')
        ->and($requestBody)->toHaveKey('frequency')
        ->and($requestBody['frequency'])->toBe('ONE_TIME')
        ->and($requestBody)->toHaveKey('methods')
        ->and($requestBody['methods'])->toContain('PIX')
        ->and($requestBody)->toHaveKey('products')
        ->and($requestBody['products'][0])->toHaveKey('externalId')
        ->and($requestBody['products'][0]['externalId'])->toBe('product-1')
        ->and($requestBody['products'][0])->toHaveKey('name')
        ->and($requestBody['products'][0]['name'])->toBe('Test Product')
        ->and($requestBody['products'][0])->toHaveKey('quantity')
        ->and($requestBody['products'][0]['quantity'])->toBe(1)
        ->and($requestBody['products'][0])->toHaveKey('price')
        ->and($requestBody['products'][0]['price'])->toBe(1000)
        ->and($requestBody)->toHaveKey('returnUrl')
        ->and($requestBody['returnUrl'])->toBe('https://return.url')
        ->and($requestBody)->toHaveKey('completionUrl')
        ->and($requestBody['completionUrl'])->toBe('https://completion.url');
});

test('billing createLink envia frequency como MULTIPLE_PAYMENTS', function () {
    $requestBody = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => ['id' => 'bill_123'],
        ])),
    ]);

    $handlerStack = HandlerStack::create($handler);
    $handlerStack->push(function (callable $handler) use (&$requestBody) {
        return function (RequestInterface $request, array $options) use ($handler, &$requestBody) {
            $requestBody = json_decode($request->getBody()->getContents(), true);

            return $handler($request, $options);
        };
    });

    $mockClient = new Client(['handler' => $handlerStack]);
    $billingClient = new BillingClient('test_token', $mockClient);

    $billing = new Billing([
        'methods' => [Methods::PIX],
        'products' => [
            new Product([
                'name' => 'Test Product',
                'quantity' => 1,
                'price' => 1000,
            ]),
        ],
        'metadata' => new BillingMetadata([
            'return_url' => 'https://return.url',
            'completion_url' => 'https://completion.url',
        ]),
    ]);

    $billingClient->createLink($billing);

    expect($requestBody)->toHaveKey('frequency')
        ->and($requestBody['frequency'])->toBe('MULTIPLE_PAYMENTS');
});

test('billing list chama endpoint correto', function () {
    $requestUri = null;
    $requestMethod = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => [],
        ])),
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
    $billingClient = new BillingClient('test_token', $mockClient);

    $billingClient->list();

    expect($requestMethod)->toBe('GET')
        ->and($requestUri)->toContain('list');
});
