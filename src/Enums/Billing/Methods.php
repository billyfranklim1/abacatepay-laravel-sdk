<?php

namespace Billyfranklim\AbacatePay\Enums\Billing;

enum Methods: string
{
    case PIX = 'PIX';
    case CARD = 'CARD';
}
