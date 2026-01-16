<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PixelInnovService
{
    private $secretKey;
    private $baseUrl = 'https://proxy-coreapi.pixelinnov.net/api_v1';
    
    public function __construct()
    {
        $this->secretKey = "PIX_26cdfbc9-469c-4ac2-a914-138bdc9f50ae";
        
        if (!$this->secretKey) {
            Log::error('Clé secrète Paystack non configurée');
            throw new \Exception('Paystack secret key is not configured');
        }
    }
    
    /**
     * Initialiser une transaction
     */
    public function initializeTransaction(array $data)
    {
        // try {
        // 7 wave
        // 5 Mtn
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->baseUrl . '/transaction/airtime', $data);
            
            $result = $response->json();
          
            
            return $result;
            
        // } catch (\Exception $e) {
        //     Log::error('Erreur PixelInnov initialization', [
        //         'error' => $e->getMessage(),
        //         'data' => $data
        //     ]);
        //     return [
        //         'status' => false, 
        //         'message' => 'Erreur de connexion à Paystack: ' . $e->getMessage()
        //     ];
        // }
    }
    
    /**
     * Vérifier une transaction
     */
    public function verifyTransaction(string $reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Cache-Control' => 'no-cache',
            ])->timeout(30)->get($this->baseUrl . '/transaction/verify/' . $reference);
            
            $result = $response->json();
            
            Log::info('PixelInnov verification response', [
                'reference' => $reference,
                'status' => $response->status(),
                'data' => $result
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Erreur PixelInnov verification', [
                'reference' => $reference,
                'error' => $e->getMessage()
            ]);
            return [
                'status' => false, 
                'message' => 'Erreur de vérification: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Créer un bénéficiaire de transfert
     */
    public function createTransferRecipient(array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transferrecipient', $data);
            
            return $response->json();
            
        } catch (\Exception $e) {
            Log::error('Erreur création bénéficiaire PixelInnov', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Effectuer un transfert
     */
    public function initiateTransfer(array $data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transfer', $data);
            
            return $response->json();
            
        } catch (\Exception $e) {
            Log::error('Erreur transfert PixelInnov', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Liste des banques
     */
    public function listBanks(string $country = 'nigeria')
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/bank', [
                'country' => $country,
                'perPage' => 100
            ]);
            
            return $response->json();
            
        } catch (\Exception $e) {
            Log::error('Erreur liste banques PixelInnov', ['error' => $e->getMessage()]);
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}