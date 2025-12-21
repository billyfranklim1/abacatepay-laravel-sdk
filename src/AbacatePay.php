<?php

namespace VendorName\AbacatePay;

use VendorName\AbacatePay\Clients\BillingClient;
use VendorName\AbacatePay\Clients\CustomerClient;

class AbacatePay
{
    public function __construct(
        protected readonly string $token
    ) {
    }

    public function billing(): BillingClient
    {
        return new BillingClient($this->token);
    }

    public function customer(): CustomerClient
    {
        return new CustomerClient($this->token);
    }
}

