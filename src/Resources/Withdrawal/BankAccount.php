<?php

namespace Billyfranklim\AbacatePay\Resources\Withdrawal;

use Billyfranklim\AbacatePay\Enums\Withdrawal\AccountType;
use Billyfranklim\AbacatePay\Resources\Resource;

class BankAccount extends Resource
{
    public ?string $bank_code;
    public ?string $agency;
    public ?string $account;
    public ?AccountType $account_type;
    public ?string $holder_name;
    public ?string $holder_document;

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
            case 'account_type':
                return $this->__initializeEnum(AccountType::class, $value);
            default:
                return $value;
        }
    }
}

