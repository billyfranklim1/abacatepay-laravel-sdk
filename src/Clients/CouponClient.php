<?php

namespace Billyfranklim\AbacatePay\Clients;

use Billyfranklim\AbacatePay\Resources\Coupon;
use GuzzleHttp\Client as GuzzleHttpClient;

class CouponClient extends Client
{
    const URI = 'coupon';

    public function __construct(string $token, ?GuzzleHttpClient $client = null)
    {
        parent::__construct(self::URI, $token, $client);
    }
    
    public function list(): array
    {
        $response = $this->request("GET", "list");
        return array_map(fn($data) => new Coupon($data), $response);
    }

    public function create(Coupon $data): Coupon
    {
        $requestData = [
            'code' => $data->code,
            'discountKind' => $data->discount_kind?->value,
            'discount' => $data->discount,
        ];

        if (isset($data->max_redeems)) {
            $requestData['maxRedeems'] = $data->max_redeems;
        }

        if (isset($data->notes)) {
            $requestData['notes'] = $data->notes;
        }

        if (isset($data->metadata)) {
            $requestData['metadata'] = $data->metadata;
        }

        $response = $this->request("POST", "create", [
            'json' => $requestData
        ]);

        return new Coupon($response);
    }
}

