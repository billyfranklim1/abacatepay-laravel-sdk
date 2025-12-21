<?php

use VendorName\AbacatePay\Clients\BillingClient;
use VendorName\AbacatePay\Enums\Billing\Frequencies;
use VendorName\AbacatePay\Enums\Billing\Methods;
use VendorName\AbacatePay\Enums\Billing\Statuses;
use VendorName\AbacatePay\Exceptions\ApiException;
use VendorName\AbacatePay\Resources\Billing;
use VendorName\AbacatePay\Resources\Billing\Metadata as BillingMetadata;
use VendorName\AbacatePay\Resources\Billing\Product;
use VendorName\AbacatePay\Resources\Customer;
use VendorName\AbacatePay\Resources\Customer\Metadata as CustomerMetadata;

test('pode listar cobranças', function () {
    $mockClient = getListBillingsResponseClient();
    $billingClient = new BillingClient('test_token', $mockClient);

    $billings = $billingClient->list();

    expect($billings)
        ->toBeArray()
        ->not->toBeEmpty()
        ->and($billings[0])
        ->toBeInstanceOf(Billing::class)
        ->and($billings[0]->id)
        ->toBe('bill_j2qugKAGHRPHLQGGCumAq0ZJ')
        ->and($billings[0]->status)
        ->toBe(Statuses::PAID)
        ->and($billings[0]->frequency)
        ->toBe(Frequencies::ONE_TIME)
        ->and($billings[0]->methods)
        ->toContain(Methods::PIX);
});

test('pode criar uma cobrança com novo cliente', function () {
    $mockClient = getCreateBillingResponseClient();
    $billingClient = new BillingClient('test_token', $mockClient);

    $billing = new Billing([
        'frequency' => Frequencies::ONE_TIME,
        'methods' => [Methods::PIX],
        'products' => [
            new Product([
                'external_id' => 'abc_123',
                'name' => 'Abacate',
                'description' => 'Abacate maduro',
                'quantity' => 1,
                'price' => 100
            ])
        ],
        'metadata' => new BillingMetadata([
            'return_url' => 'https://www.abacatepay.com',
            'completion_url' => 'https://www.abacatepay.com'
        ]),
        'customer' => new Customer([
            'metadata' => new CustomerMetadata([
                'name' => 'Abacate Lover',
                'cellphone' => '01912341234',
                'email' => 'lover@abacate.com',
                'tax_id' => '13827826837'
            ])
        ])
    ]);

    $createdBilling = $billingClient->create($billing);

    expect($createdBilling)
        ->toBeInstanceOf(Billing::class)
        ->and($createdBilling->id)
        ->toBe('bill_YcxMCe4Fq0wJEcnP5gLPq0Yc')
        ->and($createdBilling->status)
        ->toBe(Statuses::PENDING)
        ->and($createdBilling->url)
        ->toBe('https://abacatepay.com/pay/bill_YcxMCe4Fq0wJEcnP5gLPq0Yc')
        ->and($createdBilling->customer)
        ->toBeInstanceOf(Customer::class)
        ->and($createdBilling->customer->metadata->name)
        ->toBe('Abacate Lover');
});

test('pode criar uma cobrança com cliente existente', function () {
    $mockClient = getCreateBillingResponseClient();
    $billingClient = new BillingClient('test_token', $mockClient);

    $billing = new Billing([
        'frequency' => Frequencies::ONE_TIME,
        'methods' => [Methods::PIX],
        'products' => [
            new Product([
                'external_id' => 'abc_123',
                'name' => 'Abacate',
                'description' => 'Abacate maduro',
                'quantity' => 1,
                'price' => 100
            ])
        ],
        'metadata' => new BillingMetadata([
            'return_url' => 'https://www.abacatepay.com',
            'completion_url' => 'https://www.abacatepay.com'
        ]),
        'customer' => new Customer([
            'id' => 'cust_existing_123'
        ])
    ]);

    $createdBilling = $billingClient->create($billing);

    expect($createdBilling)
        ->toBeInstanceOf(Billing::class)
        ->and($createdBilling->id)
        ->toBe('bill_YcxMCe4Fq0wJEcnP5gLPq0Yc');
});

test('lança exceção quando a API retorna erro', function () {
    $mockClient = createErrorResponseClient(400, 'Invalid request');
    $billingClient = new BillingClient('test_token', $mockClient);

    expect(fn() => $billingClient->list())
        ->toThrow(ApiException::class, 'AbacatePay API Error');
});

