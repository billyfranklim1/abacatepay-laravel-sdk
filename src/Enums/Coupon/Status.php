<?php

namespace Billyfranklim\AbacatePay\Enums\Coupon;

enum Status: string
{
    case ACTIVE = 'ACTIVE';
    case DELETED = 'DELETED';
    case DISABLED = 'DISABLED';
}
