<?php

namespace VendorName\AbacatePay\Clients;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use VendorName\AbacatePay\Exceptions\ApiException;

class Client
{
    private GuzzleHttpClient $client;

    protected readonly string $token;

    final public const BASE_URI = 'https://api.abacatepay.com/v1';

    public function __construct(string $uri, string $token, ?GuzzleHttpClient $client = null)
    {
        $this->token = $token;
        
        $this->client = $client ?? new GuzzleHttpClient([
            'base_uri' => self::BASE_URI . "/" . $uri . "/",
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ]
        ]);
    }

    public function request(string $method, string $uri, array $options = []): array
    {
        try {
            $response = $this->client->request($method, $uri, $options);
            $body = json_decode($response->getBody()->getContents(), true);
            
            return $body['data'] ?? [];
        } catch (RequestException $e) {
            $errorMessage = $this->extractErrorMessage($e);
            
            Log::error('AbacatePay API Request Failed', [
                'method' => $method,
                'uri' => $uri,
                'error' => $errorMessage,
                'status_code' => $e->getCode(),
            ]);

            throw new ApiException($errorMessage, $e->getCode(), $e);
        } catch (\Throwable $e) {
            Log::error('AbacatePay API Unexpected Error', [
                'method' => $method,
                'uri' => $uri,
                'error' => $e->getMessage(),
            ]);

            throw new ApiException("Unexpected error: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    protected function extractErrorMessage(RequestException $e): string
    {
        if (!$e->hasResponse()) {
            return $e->getMessage();
        }

        try {
            $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
            return $errorResponse['message'] ?? $errorResponse['error'] ?? $e->getMessage();
        } catch (\Throwable) {
            return $e->getMessage();
        }
    }
}

