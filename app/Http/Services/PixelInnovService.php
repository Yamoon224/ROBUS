<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PixelInnovService
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.pixel_innov.api_key', '');
        $this->baseUrl = config('services.pixel_innov.sandbox', false)
            ? 'https://standbox-api.pixelinnov.net/api_v1'
            : 'https://proxy-coreapi.pixelinnov.net/api_v1';
    }

    /**
     * Initialiser une transaction (tous providers, tous pays)
     * Le service_id dans $data détermine le provider et le pays
     */
    public function initializeTransaction(array $data): array
    {
        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(30)
                ->post($this->baseUrl . '/transaction/airtime', array_merge($data, [
                    'api_key' => $this->apiKey,
                ]));

            $result = $response->json() ?? [];

            Log::info('Pixel Innov — init transaction', [
                'payload_sent'   => array_merge($data, ['api_key' => substr($this->apiKey, 0, 6) . '***']),
                'http'           => $response->status(),
                'full_response'  => $result,
                'statut_code'    => $result['statut_code'] ?? null,
                'transaction_id' => $result['data']['transaction_id'] ?? null,
                'state'          => $result['data']['state'] ?? null,
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Pixel Innov — erreur init', ['error' => $e->getMessage()]);
            return ['statut_code' => 500, 'message' => $e->getMessage()];
        }
    }

    /**
     * Vérifier le statut d'une transaction côté Pixel Innov
     * POST /transaction/status  { api_key, transaction_ids }
     */
    public function checkTransactionStatus(string $transactionId): array
    {
        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(15)
                ->post($this->baseUrl . '/transaction/status', [
                    'api_key'         => $this->apiKey,
                    'transaction_ids' => $transactionId,
                ]);

            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('Pixel Innov — erreur status', ['error' => $e->getMessage()]);
            return ['statut_code' => 500, 'message' => $e->getMessage()];
        }
    }

    /**
     * Vérifier le hash IPN : SHA256(api_key + transaction_id)
     */
    public function verifyIpnHash(string $transactionId, string $receivedHash): bool
    {
        $expected = hash('sha256', $this->apiKey . $transactionId);
        return hash_equals($expected, $receivedHash);
    }

    /**
     * Sélectionne la clé API selon le type de devise du service.
     * is_xaf=true → config pixel_innov_xaf, sinon → config pixel_innov
     */
    public function setXaf(bool $isXaf): static
    {
        $configKey    = $isXaf ? 'pixel_innov_xaf' : 'pixel_innov';
        $this->apiKey = config("services.{$configKey}.api_key", '');
        return $this;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }
}
