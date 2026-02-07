<?php

namespace Billyfranklim\AbacatePay\Resources\Billing;

use Billyfranklim\AbacatePay\Resources\Resource;

class Metadata extends Resource
{
    public ?int $fee;

    public ?string $return_url;

    public ?string $completion_url;

    public function __construct(array $data)
    {
        $this->__fill($this, $data);
    }
}
