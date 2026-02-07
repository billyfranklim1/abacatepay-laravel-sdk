<?php

use Billyfranklim\AbacatePay\Clients\StoreClient;
use Billyfranklim\AbacatePay\Exceptions\ApiException;
use Billyfranklim\AbacatePay\Resources\Store;

test('pode obter informações da loja', function () {
    $mockClient = getStoreResponseClient();
    $storeClient = new StoreClient('test_token', $mockClient);

    $store = $storeClient->get();

    expect($store)
        ->toBeInstanceOf(Store::class)
        ->and($store->id)
        ->toBe('store_123')
        ->and($store->name)
        ->toBe('Minha Loja')
        ->and($store->email)
        ->toBe('loja@example.com')
        ->and($store->document)
        ->toBe('12345678900')
        ->and($store->dev_mode)
        ->toBeTrue();
});

test('lança exceção quando a API retorna erro', function () {
    $mockClient = createErrorResponseClient(400, 'Invalid request');
    $storeClient = new StoreClient('test_token', $mockClient);

    expect(fn() => $storeClient->get())
        ->toThrow(ApiException::class, 'AbacatePay API Error');
});


