<?php

namespace Billyfranklim\AbacatePay\Clients;

use Billyfranklim\AbacatePay\Resources\Withdrawal;
use GuzzleHttp\Client as GuzzleHttpClient;

class WithdrawalClient extends Client
{
    const URI = 'withdrawal';

    public function __construct(string $token, ?GuzzleHttpClient $client = null)
    {
        parent::__construct(self::URI, $token, $client);
    }
    
    public function list(): array
    {
        $response = $this->request("GET", "list");
        return array_map(fn($data) => new Withdrawal($data), $response);
    }

    public function get(string $withdrawalId): Withdrawal
    {
        $response = $this->request("GET", "get?id={$withdrawalId}");
        return new Withdrawal($response);
    }

    public function create(Withdrawal $data): Withdrawal
    {
        if (!isset($data->bank_account)) {
            throw new \InvalidArgumentException('Bank account is required');
        }

        $requestData = [
            'amount' => $data->amount,
            'bankAccount' => [
                'bankCode' => $data->bank_account->bank_code,
                'agency' => $data->bank_account->agency,
                'account' => $data->bank_account->account,
                'accountType' => $data->bank_account->account_type?->value,
                'holderName' => $data->bank_account->holder_name,
                'holderDocument' => $data->bank_account->holder_document
            ]
        ];

        $response = $this->request("POST", "create", [
            'json' => $requestData
        ]);

        return new Withdrawal($response);
    }
}


