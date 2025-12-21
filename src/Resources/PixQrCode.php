<?php

namespace Billyfranklim\AbacatePay\Resources;

use Billyfranklim\AbacatePay\Enums\Billing\Statuses;
use Billyfranklim\AbacatePay\Resources\Customer;
use DateTime;

class PixQrCode extends Resource
{
    public ?string $id;
    public ?int $amount;
    public ?Statuses $status;
    public ?bool $dev_mode;
    public ?string $br_code;
    public ?string $br_code_base64;
    public ?int $platform_fee;
    public ?DateTime $created_at;
    public ?DateTime $updated_at;
    public ?DateTime $expires_at;
    public ?Customer $customer;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
    }

    public function __set($name, $value)
    {
        $name = $this->__camelToSnakeCase($name);

        if (!property_exists($this, $name)) {
            return;
        }

        $this->{$name} = $this->processValue($name, $value);
    }

    private function processValue($name, $value)
    {
        if ($value === null) {
            return null;
        }

        switch ($name) {
            case 'created_at':
            case 'updated_at':
            case 'expires_at':
                return $this->__initializeDateTime($value);
            case 'status':
                return $this->__initializeEnum(Statuses::class, $value);
            case 'customer':
                return $this->__initializeResource(Customer::class, $value);
            default:
                return $value;
        }
    }
}

