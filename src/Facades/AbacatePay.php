<?php

namespace VendorName\AbacatePay\Facades;

use Illuminate\Support\Facades\Facade;

class AbacatePay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \VendorName\AbacatePay\AbacatePay::class;
    }
}

