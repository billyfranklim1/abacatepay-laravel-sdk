<?php

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

function createMockClient(string $responseFilePath): Client
{
    $handler = new MockHandler();

    $handler->append(
        new Response(
            status: 200,
            body: file_get_contents(__DIR__ . '/Mocks/Response/' . $responseFilePath . '.json')
        )
    );

    return new Client([
        'handler' => $handler
    ]);
}

function getListBillingsResponseClient(): Client
{
    return createMockClient('Billing/list');
}

function getCreateBillingResponseClient(): Client
{
    return createMockClient('Billing/create');
}

function getListCustomersResponseClient(): Client
{
    return createMockClient('Customer/list');
}

function getCreateCustomerResponseClient(): Client
{
    return createMockClient('Customer/create');
}

function getListCouponsResponseClient(): Client
{
    return createMockClient('Coupon/list');
}

function getCreateCouponResponseClient(): Client
{
    return createMockClient('Coupon/create');
}

function getListWithdrawalsResponseClient(): Client
{
    return createMockClient('Withdrawal/list');
}

function getCreateWithdrawalResponseClient(): Client
{
    return createMockClient('Withdrawal/create');
}

function getStoreResponseClient(): Client
{
    return createMockClient('Store/get');
}

function getCreatePixQrCodeResponseClient(): Client
{
    return createMockClient('PixQrCode/create');
}

function createErrorResponseClient(int $statusCode = 400, string $message = 'Bad Request'): Client
{
    $handler = new MockHandler();

    $handler->append(
        new \GuzzleHttp\Exception\RequestException(
            $message,
            new \GuzzleHttp\Psr7\Request('GET', 'test'),
            new Response(
                status: $statusCode,
                body: json_encode([
                    'error' => $message,
                    'message' => $message
                ])
            )
        )
    );

    return new Client([
        'handler' => $handler
    ]);
}

