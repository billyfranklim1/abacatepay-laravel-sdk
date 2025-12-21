<?php

use Billyfranklim\AbacatePay\Clients\WithdrawalClient;
use Billyfranklim\AbacatePay\Enums\Withdrawal\AccountType;
use Billyfranklim\AbacatePay\Resources\Withdrawal;
use Billyfranklim\AbacatePay\Resources\Withdrawal\BankAccount;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

test('withdrawal create envia parâmetros corretos', function () {
    $requestBody = null;
    $requestMethod = null;
    $requestUri = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => ['id' => 'withdrawal_123']
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
    $withdrawalClient = new WithdrawalClient('test_token', $mockClient);

    $withdrawal = new Withdrawal([
        'amount' => 10000,
        'bank_account' => new BankAccount([
            'bank_code' => '001',
            'agency' => '1234',
            'account' => '12345678',
            'account_type' => AccountType::CHECKING,
            'holder_name' => 'João da Silva',
            'holder_document' => '12345678900'
        ])
    ]);

    $withdrawalClient->create($withdrawal);

    expect($requestMethod)->toBe('POST')
        ->and($requestUri)->toContain('create')
        ->and($requestBody)->toHaveKey('amount')
        ->and($requestBody['amount'])->toBe(10000)
        ->and($requestBody)->toHaveKey('bankAccount')
        ->and($requestBody['bankAccount'])->toHaveKey('bankCode')
        ->and($requestBody['bankAccount']['bankCode'])->toBe('001')
        ->and($requestBody['bankAccount'])->toHaveKey('agency')
        ->and($requestBody['bankAccount']['agency'])->toBe('1234')
        ->and($requestBody['bankAccount'])->toHaveKey('account')
        ->and($requestBody['bankAccount']['account'])->toBe('12345678')
        ->and($requestBody['bankAccount'])->toHaveKey('accountType')
        ->and($requestBody['bankAccount']['accountType'])->toBe('CHECKING')
        ->and($requestBody['bankAccount'])->toHaveKey('holderName')
        ->and($requestBody['bankAccount']['holderName'])->toBe('João da Silva')
        ->and($requestBody['bankAccount'])->toHaveKey('holderDocument')
        ->and($requestBody['bankAccount']['holderDocument'])->toBe('12345678900');
});

test('withdrawal get chama endpoint correto com query parameter', function () {
    $requestUri = null;
    $requestMethod = null;

    $handler = new MockHandler([
        new Response(200, [], json_encode([
            'error' => null,
            'data' => ['id' => 'withdrawal_123']
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
    $withdrawalClient = new WithdrawalClient('test_token', $mockClient);

    $withdrawalClient->get('withdrawal_123456');

    expect($requestMethod)->toBe('GET')
        ->and($requestUri)->toContain('get')
        ->and($requestUri)->toContain('id=withdrawal_123456');
});

test('withdrawal list chama endpoint correto', function () {
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
    $withdrawalClient = new WithdrawalClient('test_token', $mockClient);

    $withdrawalClient->list();

    expect($requestMethod)->toBe('GET')
        ->and($requestUri)->toContain('list');
});

