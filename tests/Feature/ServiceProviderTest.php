<?php

use VendorName\AbacatePay\AbacatePay;
use VendorName\AbacatePay\AbacatePayServiceProvider;
use VendorName\AbacatePay\Clients\BillingClient;
use VendorName\AbacatePay\Clients\CustomerClient;
use VendorName\AbacatePay\Exceptions\ConfigurationException;

test('service provider registra o singleton do AbacatePay', function () {
    config()->set('abacatepay.token', 'test_token');

    $app = app();
    $provider = new AbacatePayServiceProvider($app);
    $provider->register();

    $instance = $app->make(AbacatePay::class);

    expect($instance)
        ->toBeInstanceOf(AbacatePay::class)
        ->and($app->make(AbacatePay::class))
        ->toBe($instance);
});

test('service provider registra binding para billing client', function () {
    config()->set('abacatepay.token', 'test_token');

    $app = app();
    $provider = new AbacatePayServiceProvider($app);
    $provider->register();

    $billingClient = $app->make('abacatepay.billing');

    expect($billingClient)
        ->toBeInstanceOf(BillingClient::class);
});

test('service provider registra binding para customer client', function () {
    config()->set('abacatepay.token', 'test_token');

    $app = app();
    $provider = new AbacatePayServiceProvider($app);
    $provider->register();

    $customerClient = $app->make('abacatepay.customer');

    expect($customerClient)
        ->toBeInstanceOf(CustomerClient::class);
});

test('lança exceção quando token não está configurado', function () {
    config()->set('abacatepay.token', null);

    $app = app();
    $provider = new AbacatePayServiceProvider($app);
    $provider->register();

    expect(fn() => $app->make(AbacatePay::class))
        ->toThrow(ConfigurationException::class, 'ABACATEPAY_TOKEN não configurado');
});

test('lança exceção quando token está vazio', function () {
    config()->set('abacatepay.token', '');

    $app = app();
    $provider = new AbacatePayServiceProvider($app);
    $provider->register();

    expect(fn() => $app->make(AbacatePay::class))
        ->toThrow(ConfigurationException::class, 'ABACATEPAY_TOKEN não configurado');
});

