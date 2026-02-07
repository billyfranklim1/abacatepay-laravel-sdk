<?php

namespace Billyfranklim\AbacatePay\Exceptions;

class ApiException extends AbacatePayException
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct("AbacatePay API Error: {$message}", $code, $previous);
    }
}
