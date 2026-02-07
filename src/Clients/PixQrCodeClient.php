<?php

namespace Billyfranklim\AbacatePay\Clients;

use Billyfranklim\AbacatePay\Resources\PixQrCode;
use GuzzleHttp\Client as GuzzleHttpClient;

class PixQrCodeClient extends Client
{
    const URI = 'pixQrCode';

    public function __construct(string $token, ?GuzzleHttpClient $client = null)
    {
        parent::__construct(self::URI, $token, $client);
    }

    public function create(array $data): PixQrCode
    {
        $requestData = [
            'amount' => $data['amount'],
        ];

        if (isset($data['expiresIn'])) {
            $requestData['expiresIn'] = $data['expiresIn'];
        } elseif (isset($data['expires_in'])) {
            $requestData['expiresIn'] = $data['expires_in'];
        }

        if (isset($data['description'])) {
            $requestData['description'] = $data['description'];
        }

        if (isset($data['customer'])) {
            $requestData['customer'] = $data['customer'];
        }

        $response = $this->request('POST', 'create', [
            'json' => $requestData,
        ]);

        return new PixQrCode($response);
    }

    public function check(string $pixQrCodeId): PixQrCode
    {
        $response = $this->request('GET', "check?id={$pixQrCodeId}");

        return new PixQrCode($response);
    }

    public function simulatePayment(string $pixQrCodeId, array $metadata = []): PixQrCode
    {
        $response = $this->request('POST', "simulate-payment?id={$pixQrCodeId}", [
            'json' => ['metadata' => $metadata],
        ]);

        return new PixQrCode($response);
    }
}
