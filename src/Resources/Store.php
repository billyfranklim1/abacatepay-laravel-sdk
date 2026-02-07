<?php

namespace Billyfranklim\AbacatePay\Resources;

use DateTime;

class Store extends Resource
{
    public ?string $id;

    public ?string $name;

    public ?string $email;

    public ?string $phone;

    public ?string $document;

    public ?bool $dev_mode;

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
                return $this->__initializeDateTime($value);
            default:
                return $value;
        }
    }
}
