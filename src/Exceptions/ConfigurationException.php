<?php

namespace Billyfranklim\AbacatePay\Exceptions;

class ConfigurationException extends AbacatePayException
{
    public static function tokenNotConfigured(): self
    {
        return new self('ABACATEPAY_TOKEN não configurado. Defina no arquivo .env');
    }
}


