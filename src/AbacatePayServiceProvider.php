<?php

namespace VendorName\AbacatePay;

use Illuminate\Support\Facades\Config;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VendorName\AbacatePay\Clients\BillingClient;
use VendorName\AbacatePay\Clients\CustomerClient;
use VendorName\AbacatePay\Exceptions\ConfigurationException;

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

