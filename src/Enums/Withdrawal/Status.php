<?php

namespace Billyfranklim\AbacatePay\Enums\Withdrawal;

enum Status: string
{
    case PENDING = "PENDING";
    case PROCESSING = "PROCESSING";
    case COMPLETED = "COMPLETED";
    case FAILED = "FAILED";
    case CANCELLED = "CANCELLED";
}


