<?php

namespace Billyfranklim\AbacatePay\Facades;

use Illuminate\Support\Facades\Facade;

class AbacatePay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Billyfranklim\AbacatePay\AbacatePay::class;
    }
}
