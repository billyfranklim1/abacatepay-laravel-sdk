<?php

namespace Billyfranklim\AbacatePay\Clients;

use Billyfranklim\AbacatePay\Resources\Store;
use GuzzleHttp\Client as GuzzleHttpClient;

class StoreClient extends Client
{
    const URI = 'store';

    public function __construct(string $token, ?GuzzleHttpClient $client = null)
    {
        parent::__construct(self::URI, $token, $client);
    }
    
    public function get(): Store
    {
        $response = $this->request("GET", "get");
        return new Store($response);
    }
}


