<?php

namespace Billyfranklim\AbacatePay\Enums\Billing;

enum Statuses: string
{
    case PENDING = "PENDING";
    case EXPIRED = "EXPIRED";
    case CANCELLED = "CANCELLED";
    case PAID = "PAID";
    case REFUNDED = "REFUNDED";
}

