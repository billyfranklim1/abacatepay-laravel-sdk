<?php

namespace Billyfranklim\AbacatePay;

use Billyfranklim\AbacatePay\Clients\BillingClient;
use Billyfranklim\AbacatePay\Clients\CouponClient;
use Billyfranklim\AbacatePay\Clients\CustomerClient;
use Billyfranklim\AbacatePay\Clients\PixQrCodeClient;
use Billyfranklim\AbacatePay\Clients\StoreClient;
use Billyfranklim\AbacatePay\Clients\WithdrawalClient;

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

    public function coupon(): CouponClient
    {
        return new CouponClient($this->token);
    }

    public function pixQrCode(): PixQrCodeClient
    {
        return new PixQrCodeClient($this->token);
    }

    public function withdrawal(): WithdrawalClient
    {
        return new WithdrawalClient($this->token);
    }

    public function store(): StoreClient
    {
        return new StoreClient($this->token);
    }
}

