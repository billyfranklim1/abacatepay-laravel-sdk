<?php

namespace VendorName\AbacatePay\Clients;

use VendorName\AbacatePay\Resources\Billing;
use GuzzleHttp\Client as GuzzleHttpClient;

class BillingClient extends Client
{
    const URI = 'billing';

    public function __construct(string $token, ?GuzzleHttpClient $client = null)
    {
        parent::__construct(self::URI, $token, $client);
    }
    
    public function list(): array
    {
        $response = $this->request("GET", "list");
        return array_map(fn($data) => new Billing($data), $response);
    }

    public function create(Billing $data): Billing
    {
        $requestData = [
            'frequency' => $data->frequency?->value,
            'methods' => array_map(fn($method) => $method->value, $data->methods ?? []),
            'returnUrl' => $data->metadata?->return_url,
            'completionUrl' => $data->metadata?->completion_url,
            'products' => array_map(
                fn($product) => [
                    'externalId' => $product->external_id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'quantity' => $product->quantity,
                    'price' => $product->price
                ],
                $data->products ?? []
            ),
        ];

        if (isset($data->customer)) {
            if (isset($data->customer->id)) {
                $requestData['customerId'] = $data->customer->id;
            } elseif (isset($data->customer->metadata)) {
                $requestData['customer'] = [
                    'name' => $data->customer->metadata->name,
                    'email' => $data->customer->metadata->email,
                    'cellphone' => $data->customer->metadata->cellphone,
                    'taxId' => $data->customer->metadata->tax_id
                ];
            }
        }

        $response = $this->request("POST", "create", [
            'json' => $requestData
        ]);

        return new Billing($response);
    }
}

