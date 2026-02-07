<?php

use Billyfranklim\AbacatePay\Clients\CustomerClient;
use Billyfranklim\AbacatePay\Exceptions\ApiException;
use Billyfranklim\AbacatePay\Resources\Customer;
use Billyfranklim\AbacatePay\Resources\Customer\Metadata;

test('pode listar clientes', function () {
    $mockClient = getListCustomersResponseClient();
    $customerClient = new CustomerClient('test_token', $mockClient);

    $customers = $customerClient->list();

    expect($customers)
        ->toBeArray()
        ->not->toBeEmpty()
        ->and($customers[0])
        ->toBeInstanceOf(Customer::class)
        ->and($customers[0]->id)
        ->toBe('cust_DEbpqcNruex6z4jmfYDSzyLf')
        ->and($customers[0]->metadata)
        ->toBeInstanceOf(Metadata::class)
        ->and($customers[0]->metadata->name)
        ->toBe('Abacate Lover')
        ->and($customers[0]->metadata->email)
        ->toBe('lover@abacate.com');
});

test('pode criar um cliente', function () {
    $mockClient = getCreateCustomerResponseClient();
    $customerClient = new CustomerClient('test_token', $mockClient);

    $customer = new Customer([
        'metadata' => new Metadata([
            'name' => 'Abacate Lover',
            'cellphone' => '01912341234',
            'email' => 'lover@abacate.com',
            'tax_id' => '13827826837'
        ])
    ]);

    $createdCustomer = $customerClient->create($customer);

    expect($createdCustomer)
        ->toBeInstanceOf(Customer::class)
        ->and($createdCustomer->id)
        ->toBe('cust_45ngpDrEWUqDA1r4NAaRFjKS')
        ->and($createdCustomer->metadata)
        ->toBeInstanceOf(Metadata::class)
        ->and($createdCustomer->metadata->name)
        ->toBe('Abacate Lover')
        ->and($createdCustomer->metadata->email)
        ->toBe('lover@abacate.com')
        ->and($createdCustomer->metadata->cellphone)
        ->toBe('01912341234')
        ->and($createdCustomer->metadata->tax_id)
        ->toBe('13827826837');
});

test('lança exceção quando metadata não é fornecida', function () {
    $mockClient = getCreateCustomerResponseClient();
    $customerClient = new CustomerClient('test_token', $mockClient);

    $customer = new Customer([]);

    expect(fn() => $customerClient->create($customer))
        ->toThrow(\InvalidArgumentException::class, 'Customer metadata is required');
});

test('lança exceção quando a API retorna erro', function () {
    $mockClient = createErrorResponseClient(400, 'Invalid request');
    $customerClient = new CustomerClient('test_token', $mockClient);

    expect(fn() => $customerClient->list())
        ->toThrow(ApiException::class, 'AbacatePay API Error');
});


