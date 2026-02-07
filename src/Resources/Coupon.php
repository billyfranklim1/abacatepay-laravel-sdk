<?php

namespace Billyfranklim\AbacatePay\Resources;

use Billyfranklim\AbacatePay\Enums\Coupon\DiscountKind;
use Billyfranklim\AbacatePay\Enums\Coupon\Status;
use DateTime;

class Coupon extends Resource
{
    public ?string $id;
    public ?string $code;
    public ?DiscountKind $discount_kind;
    public ?int $discount;
    public ?int $max_redeems;
    public ?int $redeems_count;
    public ?Status $status;
    public ?bool $dev_mode;
    public ?string $notes;
    public ?array $metadata;
    public ?DateTime $created_at;
    public ?DateTime $updated_at;

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
                return $this->__initializeDateTime($value);
            case 'status':
                return $this->__initializeEnum(Status::class, $value);
            case 'discount_kind':
                return $this->__initializeEnum(DiscountKind::class, $value);
            default:
                return $value;
        }
    }
}


