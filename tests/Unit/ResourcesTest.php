<?php

use Billyfranklim\AbacatePay\Enums\Billing\Frequencies;
use Billyfranklim\AbacatePay\Enums\Billing\Methods;
use Billyfranklim\AbacatePay\Enums\Billing\Statuses;
use Billyfranklim\AbacatePay\Resources\Billing;
use Billyfranklim\AbacatePay\Resources\Billing\Metadata as BillingMetadata;
use Billyfranklim\AbacatePay\Resources\Billing\Product;
use Billyfranklim\AbacatePay\Resources\Customer;
use Billyfranklim\AbacatePay\Resources\Customer\Metadata as CustomerMetadata;

test('billing resource inicializa corretamente com dados da API', function () {
    $data = [
        'id' => 'bill_123',
        'accountId' => 'acco_123',
        'status' => 'PENDING',
        'frequency' => 'ONE_TIME',
        'methods' => ['PIX'],
        'amount' => 10000,
        'devMode' => true,
        'url' => 'https://abacatepay.com/pay/bill_123',
        'createdAt' => '2024-12-07T22:25:54.738Z',
        'updatedAt' => '2024-12-07T22:25:54.738Z',
        'metadata' => [
            'fee' => 100,
            'returnUrl' => 'https://example.com/return',
            'completionUrl' => 'https://example.com/completion'
        ],
        'products' => [
            [
                'id' => 'prod_123',
                'externalId' => 'ext_123',
                'name' => 'Produto',
                'description' => 'Descrição',
                'quantity' => 1,
                'price' => 10000
            ]
        ],
        'customer' => [
            'id' => 'cust_123',
            'metadata' => [
                'name' => 'Cliente',
                'email' => 'cliente@example.com',
                'cellphone' => '01912341234',
                'taxId' => '12345678900'
            ]
        ]
    ];

    $billing = new Billing($data);

    expect($billing->id)
        ->toBe('bill_123')
        ->and($billing->account_id)
        ->toBe('acco_123')
        ->and($billing->status)
        ->toBe(Statuses::PENDING)
        ->and($billing->frequency)
        ->toBe(Frequencies::ONE_TIME)
        ->and($billing->methods)
        ->toContain(Methods::PIX)
        ->and($billing->amount)
        ->toBe(10000)
        ->and($billing->dev_mode)
        ->toBeTrue()
        ->and($billing->metadata)
        ->toBeInstanceOf(BillingMetadata::class)
        ->and($billing->metadata->fee)
        ->toBe(100)
        ->and($billing->products)
        ->toBeArray()
        ->and($billing->products[0])
        ->toBeInstanceOf(Product::class)
        ->and($billing->customer)
        ->toBeInstanceOf(Customer::class)
        ->and($billing->created_at)
        ->toBeInstanceOf(\DateTime::class);
});

test('customer resource inicializa corretamente com dados da API', function () {
    $data = [
        'id' => 'cust_123',
        'metadata' => [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'cellphone' => '01912341234',
            'taxId' => '12345678900'
        ]
    ];

    $customer = new Customer($data);

    expect($customer->id)
        ->toBe('cust_123')
        ->and($customer->metadata)
        ->toBeInstanceOf(CustomerMetadata::class)
        ->and($customer->metadata->name)
        ->toBe('João Silva')
        ->and($customer->metadata->email)
        ->toBe('joao@example.com')
        ->and($customer->metadata->cellphone)
        ->toBe('01912341234')
        ->and($customer->metadata->tax_id)
        ->toBe('12345678900');
});

test('product resource inicializa corretamente', function () {
    $data = [
        'productId' => 'prod_123',
        'externalId' => 'ext_123',
        'name' => 'Produto Teste',
        'description' => 'Descrição do produto',
        'quantity' => 2,
        'price' => 5000
    ];

    $product = new Product($data);

    expect($product->product_id ?? null)
        ->toBe('prod_123')
        ->and($product->external_id)
        ->toBe('ext_123')
        ->and($product->name)
        ->toBe('Produto Teste')
        ->and($product->description)
        ->toBe('Descrição do produto')
        ->and($product->quantity)
        ->toBe(2)
        ->and($product->price)
        ->toBe(5000);
});

