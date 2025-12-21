<?php

use Billyfranklim\AbacatePay\Clients\CouponClient;
use Billyfranklim\AbacatePay\Enums\Coupon\DiscountKind;
use Billyfranklim\AbacatePay\Resources\Coupon;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

test('coupon create envia parâmetros corretos', function () {
    $requestBody = null;
    $requestMethod = null;
    $requestUri = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => ['id' => 'coupon_123']
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
    $couponClient = new CouponClient('test_token', $mockClient);

    $coupon = new Coupon([
        'code' => 'TEST10',
        'discount_kind' => DiscountKind::PERCENTAGE,
        'discount' => 10,
    ]);

    $couponClient->create($coupon);

    expect($requestMethod)->toBe('POST')
        ->and($requestUri)->toContain('create')
        ->and($requestBody)->toHaveKey('code')
        ->and($requestBody['code'])->toBe('TEST10')
        ->and($requestBody)->toHaveKey('discountKind')
        ->and($requestBody['discountKind'])->toBe('PERCENTAGE')
        ->and($requestBody)->toHaveKey('discount')
        ->and($requestBody['discount'])->toBe(10);
});

test('coupon list chama endpoint correto', function () {
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
    $couponClient = new CouponClient('test_token', $mockClient);

    $couponClient->list();

    expect($requestMethod)->toBe('GET')
        ->and($requestUri)->toContain('list');
});

