<?php

namespace Billyfranklim\AbacatePay\Enums\Billing;

enum Frequencies: string
{
    case ONE_TIME = "ONE_TIME";
    case MULTIPLE_PAYMENTS = "MULTIPLE_PAYMENTS";
}

