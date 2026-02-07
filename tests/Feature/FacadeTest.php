<?php

use Billyfranklim\AbacatePay\Clients\BillingClient;
use Billyfranklim\AbacatePay\Clients\CustomerClient;
use Billyfranklim\AbacatePay\Facades\AbacatePay;

test('facade retorna instância de BillingClient', function () {
    config()->set('abacatepay.token', 'test_token');

    $billingClient = AbacatePay::billing();

    expect($billingClient)
        ->toBeInstanceOf(BillingClient::class);
});

test('facade retorna instância de CustomerClient', function () {
    config()->set('abacatepay.token', 'test_token');

    $customerClient = AbacatePay::customer();

    expect($customerClient)
        ->toBeInstanceOf(CustomerClient::class);
});

test('facade retorna novas instâncias a cada chamada', function () {
    config()->set('abacatepay.token', 'test_token');

    $billing1 = AbacatePay::billing();
    $billing2 = AbacatePay::billing();

    expect($billing1)
        ->not->toBe($billing2)
        ->and($billing1)
        ->toBeInstanceOf(BillingClient::class)
        ->and($billing2)
        ->toBeInstanceOf(BillingClient::class);
});
