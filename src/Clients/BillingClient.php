<?php

namespace Billyfranklim\AbacatePay\Clients;

use Billyfranklim\AbacatePay\Enums\Billing\Frequencies;
use Billyfranklim\AbacatePay\Resources\Billing;
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
        $response = $this->request('GET', 'list');

        return array_map(fn ($data) => new Billing($data), $response);
    }

    public function create(Billing $data): Billing
    {
        $requestData = $this->buildRequestData($data);

        $response = $this->request('POST', 'create', [
            'json' => $requestData,
        ]);

        return new Billing($response);
    }

    public function createLink(Billing $data): Billing
    {
        $requestData = $this->buildRequestData($data);
        $requestData['frequency'] = Frequencies::MULTIPLE_PAYMENTS->value;

        $response = $this->request('POST', 'create', [
            'json' => $requestData,
        ]);

        return new Billing($response);
    }

    public function get(string $billingId): Billing
    {
        $response = $this->request('GET', "get?id={$billingId}");

        return new Billing($response);
    }

    protected function buildRequestData(Billing $data): array
    {
        $methodsArray = [];
        if (! empty($data->methods) && is_array($data->methods)) {
            foreach ($data->methods as $method) {
                if ($method instanceof \BackedEnum) {
                    $methodsArray[] = $method->value;
                } elseif (is_string($method)) {
                    $methodsArray[] = $method;
                }
            }
        }

        $requestData = [
            'methods' => $methodsArray,
            'returnUrl' => $data->metadata?->return_url,
            'completionUrl' => $data->metadata?->completion_url,
            'products' => array_map(
                fn ($product) => [
                    'externalId' => $product->external_id ?? null,
                    'name' => $product->name,
                    'description' => $product->description ?? '',
                    'quantity' => $product->quantity,
                    'price' => $product->price,
                ],
                $data->products ?? []
            ),
        ];

        if (isset($data->frequency)) {
            $requestData['frequency'] = $data->frequency instanceof \BackedEnum ? $data->frequency->value : ($data->frequency?->value ?? $data->frequency);
        }

        if (isset($data->customer)) {
            if (isset($data->customer->id)) {
                $requestData['customerId'] = $data->customer->id;
            } elseif (isset($data->customer->metadata)) {
                $requestData['customer'] = [
                    'name' => $data->customer->metadata->name,
                    'email' => $data->customer->metadata->email,
                    'cellphone' => $data->customer->metadata->cellphone,
                    'taxId' => $data->customer->metadata->tax_id,
                ];
            }
        }

        return $requestData;
    }
}
