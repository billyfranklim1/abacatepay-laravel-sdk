<?php

namespace Billyfranklim\AbacatePay\Resources;

use Billyfranklim\AbacatePay\Enums\Withdrawal\Status;
use Billyfranklim\AbacatePay\Resources\Withdrawal\BankAccount;
use DateTime;

class Withdrawal extends Resource
{
    public ?string $id;

    public ?int $amount;

    public ?Status $status;

    public ?bool $dev_mode;

    public ?BankAccount $bank_account;

    public ?DateTime $created_at;

    public ?DateTime $updated_at;

    public ?DateTime $processed_at;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->__set($key, $value);
        }
    }

    public function __set($name, $value)
    {
        $name = $this->__camelToSnakeCase($name);

        if (! property_exists($this, $name)) {
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
            case 'processed_at':
                return $this->__initializeDateTime($value);
            case 'status':
                return $this->__initializeEnum(Status::class, $value);
            case 'bank_account':
                return $this->__initializeResource(BankAccount::class, $value);
            default:
                return $value;
        }
    }
}
