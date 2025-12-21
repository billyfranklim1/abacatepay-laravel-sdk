<?php

namespace Billyfranklim\AbacatePay\Clients;

use Billyfranklim\AbacatePay\Resources\PixQrCode;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Support\Facades\Log;

class PixQrCodeClient extends Client
{
    const URI = 'pixQrCode';

    public function __construct(string $token, ?GuzzleHttpClient $client = null)
    {
        parent::__construct(self::URI, $token, $client);
    }

    public function create(array $data): PixQrCode
    {
        // #region agent log
        file_put_contents('/Users/billy/Projetos/code/cafezinho/.cursor/debug.log', json_encode([
            'sessionId' => 'debug-session',
            'runId' => 'run1',
            'hypothesisId' => 'N',
            'location' => 'PixQrCodeClient.php:19',
            'message' => 'Creating PIX QR Code',
            'data' => [
                'request_data' => $data,
            ],
            'timestamp' => time() * 1000
        ]) . "\n", FILE_APPEND);
        // #endregion

        $requestData = [
            'amount' => $data['amount'],
        ];

        if (isset($data['expires_in'])) {
            $requestData['expiresIn'] = $data['expires_in'];
        }

        if (isset($data['description'])) {
            $requestData['description'] = $data['description'];
        }

        if (isset($data['customer'])) {
            $requestData['customer'] = $data['customer'];
        }

        Log::debug('AbacatePay: Criando PIX QR Code', [
            'request_data' => $requestData,
        ]);

        try {
            $response = $this->request("POST", "create", [
                'json' => $requestData
            ]);

            // #region agent log
            file_put_contents('/Users/billy/Projetos/code/cafezinho/.cursor/debug.log', json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'O',
                'location' => 'PixQrCodeClient.php:54',
                'message' => 'PIX QR Code created',
                'data' => [
                    'response_keys' => array_keys($response),
                    'response_full' => $response,
                ],
                'timestamp' => time() * 1000
            ]) . "\n", FILE_APPEND);
            // #endregion

            Log::debug('AbacatePay: PIX QR Code criado', [
                'response' => $response,
            ]);

            return new PixQrCode($response);
        } catch (\Exception $e) {
            // #region agent log
            file_put_contents('/Users/billy/Projetos/code/cafezinho/.cursor/debug.log', json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'P',
                'location' => 'PixQrCodeClient.php:72',
                'message' => 'PIX QR Code creation failed',
                'data' => [
                    'error_message' => $e->getMessage(),
                    'error_class' => get_class($e),
                ],
                'timestamp' => time() * 1000
            ]) . "\n", FILE_APPEND);
            // #endregion
            throw $e;
        }
    }

    public function check(string $pixQrCodeId): ?array
    {
        try {
            $response = $this->request("GET", "check?id={$pixQrCodeId}");
            
            // #region agent log
            file_put_contents('/Users/billy/Projetos/code/cafezinho/.cursor/debug.log', json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'Q',
                'location' => 'PixQrCodeClient.php:101',
                'message' => 'Check response received from request method',
                'data' => [
                    'response_type' => gettype($response),
                    'response_is_array' => is_array($response),
                    'response_class' => is_object($response) ? get_class($response) : null,
                    'response_keys' => is_array($response) ? array_keys($response) : null,
                ],
                'timestamp' => time() * 1000
            ]) . "\n", FILE_APPEND);
            // #endregion
            
            // Garantir que retorna array - verificação explícita e forçada
            $finalResponse = $response;
            if (!is_array($finalResponse)) {
                if (is_object($finalResponse)) {
                    // Se for objeto (incluindo PixQrCode), converter para array
                    $finalResponse = json_decode(json_encode($finalResponse), true);
                    // Garantir que o resultado é um array, não um objeto
                    if (!is_array($finalResponse)) {
                        $finalResponse = [];
                    }
                } else {
                    $finalResponse = [];
                }
            }
            
            // Verificação final antes do retorno
            if (!is_array($finalResponse)) {
                // Se ainda não for array, forçar array vazio
                $finalResponse = [];
            }
            
            // #region agent log
            file_put_contents('/Users/billy/Projetos/code/cafezinho/.cursor/debug.log', json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'run1',
                'hypothesisId' => 'W',
                'location' => 'PixQrCodeClient.php:155',
                'message' => 'About to return from check method',
                'data' => [
                    'final_response_type' => gettype($finalResponse),
                    'final_response_is_array' => is_array($finalResponse),
                    'final_response_class' => is_object($finalResponse) ? get_class($finalResponse) : null,
                ],
                'timestamp' => time() * 1000
            ]) . "\n", FILE_APPEND);
            // #endregion
            
            // Retornar array garantido
            return is_array($finalResponse) ? $finalResponse : [];
        } catch (\Exception $e) {
            Log::error('AbacatePay API Error ao verificar QR Code PIX', [
                'error' => $e->getMessage(),
                'pix_qr_code_id' => $pixQrCodeId,
            ]);
            return null;
        }
    }
}

