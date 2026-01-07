<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackService
{
    private $secretKey;
    private $baseUrl = 'https://api.paystack.co';
    
    public function __construct()
    {
        $this->secretKey = "sk_live_b5fb72bca12ad500dc305fb22ec6895c4320084a";
        
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
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
            ])->timeout(30)->post($this->baseUrl . '/transaction/initialize', $data);
            
            $result = $response->json();
            
            Log::info('Paystack initialization response', [
                'status' => $response->status(),
                'data' => $result
            ]);
            
            return $result;
            
        // } catch (\Exception $e) {
        //     Log::error('Erreur Paystack initialization', [
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
            
            Log::info('Paystack verification response', [
                'reference' => $reference,
                'status' => $response->status(),
                'data' => $result
            ]);
            
            return $result;
            
        } catch (\Exception $e) {
            Log::error('Erreur Paystack verification', [
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
            Log::error('Erreur création bénéficiaire Paystack', [
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
            Log::error('Erreur transfert Paystack', [
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
            Log::error('Erreur liste banques Paystack', ['error' => $e->getMessage()]);
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}