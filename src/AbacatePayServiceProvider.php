<?php

namespace Billyfranklim\AbacatePay;

use Billyfranklim\AbacatePay\Clients\BillingClient;
use Billyfranklim\AbacatePay\Clients\CouponClient;
use Billyfranklim\AbacatePay\Clients\CustomerClient;
use Billyfranklim\AbacatePay\Clients\PixQrCodeClient;
use Billyfranklim\AbacatePay\Clients\StoreClient;
use Billyfranklim\AbacatePay\Clients\WithdrawalClient;
use Billyfranklim\AbacatePay\Exceptions\ConfigurationException;
use Illuminate\Support\Facades\Config;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AbacatePayServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('abacatepay')
            ->hasConfigFile();
    }

    public function register(): void
    {
        parent::register();

        $this->app->singleton(AbacatePay::class, function ($app) {
            return new AbacatePay($this->getToken());
        });

        $this->app->bind('abacatepay.billing', function ($app) {
            return new BillingClient($this->getToken());
        });

        $this->app->bind('abacatepay.customer', function ($app) {
            return new CustomerClient($this->getToken());
        });

        $this->app->bind('abacatepay.coupon', function ($app) {
            return new CouponClient($this->getToken());
        });

        $this->app->bind('abacatepay.pixQrCode', function ($app) {
            return new PixQrCodeClient($this->getToken());
        });

        $this->app->bind('abacatepay.withdrawal', function ($app) {
            return new WithdrawalClient($this->getToken());
        });

        $this->app->bind('abacatepay.store', function ($app) {
            return new StoreClient($this->getToken());
        });
    }

    protected function getToken(): string
    {
        $token = Config::get('abacatepay.token');

        if (empty($token)) {
            throw ConfigurationException::tokenNotConfigured();
        }

        return $token;
    }
}
