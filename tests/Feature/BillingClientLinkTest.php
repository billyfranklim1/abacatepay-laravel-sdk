<?php

use Billyfranklim\AbacatePay\Clients\BillingClient;
use Billyfranklim\AbacatePay\Enums\Billing\Frequencies;
use Billyfranklim\AbacatePay\Enums\Billing\Methods;
use Billyfranklim\AbacatePay\Resources\Billing;
use Billyfranklim\AbacatePay\Resources\Billing\Metadata as BillingMetadata;
use Billyfranklim\AbacatePay\Resources\Billing\Product;

test('pode criar link de cobrança sem cliente obrigatório', function () {
    $mockClient = getCreateBillingResponseClient();
    $billingClient = new BillingClient('test_token', $mockClient);

    $billing = new Billing([
        'frequency' => Frequencies::ONE_TIME,
        'methods' => [Methods::PIX],
        'products' => [
            new Product([
                'external_id' => 'abc_123',
                'name' => 'Produto',
                'description' => 'Descrição',
                'quantity' => 1,
                'price' => 100
            ])
        ],
        'metadata' => new BillingMetadata([
            'return_url' => 'https://www.abacatepay.com',
            'completion_url' => 'https://www.abacatepay.com'
        ])
    ]);

    $createdBilling = $billingClient->createLink($billing);

    expect($createdBilling)
        ->toBeInstanceOf(Billing::class)
        ->and($createdBilling->id)
        ->toBe('bill_YcxMCe4Fq0wJEcnP5gLPq0Yc');
});


