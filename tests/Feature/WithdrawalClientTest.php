<?php

use Billyfranklim\AbacatePay\Clients\WithdrawalClient;
use Billyfranklim\AbacatePay\Enums\Withdrawal\AccountType;
use Billyfranklim\AbacatePay\Enums\Withdrawal\Status;
use Billyfranklim\AbacatePay\Exceptions\ApiException;
use Billyfranklim\AbacatePay\Resources\Withdrawal;
use Billyfranklim\AbacatePay\Resources\Withdrawal\BankAccount;

test('pode listar saques', function () {
    $mockClient = getListWithdrawalsResponseClient();
    $withdrawalClient = new WithdrawalClient('test_token', $mockClient);

    $withdrawals = $withdrawalClient->list();

    expect($withdrawals)
        ->toBeArray()
        ->not->toBeEmpty()
        ->and($withdrawals[0])
        ->toBeInstanceOf(Withdrawal::class)
        ->and($withdrawals[0]->id)
        ->toBe('withdrawal_123')
        ->and($withdrawals[0]->amount)
        ->toBe(10000)
        ->and($withdrawals[0]->status)
        ->toBe(Status::PENDING)
        ->and($withdrawals[0]->bank_account)
        ->toBeInstanceOf(BankAccount::class);
});

test('pode criar um saque', function () {
    $mockClient = getCreateWithdrawalResponseClient();
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

    $createdWithdrawal = $withdrawalClient->create($withdrawal);

    expect($createdWithdrawal)
        ->toBeInstanceOf(Withdrawal::class)
        ->and($createdWithdrawal->id)
        ->toBe('withdrawal_123')
        ->and($createdWithdrawal->amount)
        ->toBe(10000)
        ->and($createdWithdrawal->status)
        ->toBe(Status::PENDING)
        ->and($createdWithdrawal->bank_account)
        ->toBeInstanceOf(BankAccount::class)
        ->and($createdWithdrawal->bank_account->account_type)
        ->toBe(AccountType::CHECKING);
});

test('pode buscar um saque por ID', function () {
    $mockClient = getCreateWithdrawalResponseClient();
    $withdrawalClient = new WithdrawalClient('test_token', $mockClient);

    $withdrawal = $withdrawalClient->get('withdrawal_123');

    expect($withdrawal)
        ->toBeInstanceOf(Withdrawal::class)
        ->and($withdrawal->id)
        ->toBe('withdrawal_123');
});

test('lança exceção quando bank account não é fornecida', function () {
    $mockClient = getCreateWithdrawalResponseClient();
    $withdrawalClient = new WithdrawalClient('test_token', $mockClient);

    $withdrawal = new Withdrawal([
        'amount' => 10000
    ]);

    expect(fn() => $withdrawalClient->create($withdrawal))
        ->toThrow(\InvalidArgumentException::class, 'Bank account is required');
});

test('lança exceção quando a API retorna erro', function () {
    $mockClient = createErrorResponseClient(400, 'Invalid request');
    $withdrawalClient = new WithdrawalClient('test_token', $mockClient);

    expect(fn() => $withdrawalClient->list())
        ->toThrow(ApiException::class, 'AbacatePay API Error');
});


