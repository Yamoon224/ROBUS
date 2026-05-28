<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\GeoService;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VisiteController extends Controller
{
    const MAX_DISTANCE_M = 50; // Distance max autorisée en mètres

    /**
     * Vérifie la localisation du scanner et confirme la visite
     */
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'visite_id'  => 'required|exists:visites,id',
            'wallet_id'  => 'required|string',
            'latitude'   => 'required|numeric|between:-90,90',
            'longitude'  => 'required|numeric|between:-180,180',
        ]);

        $visite = Visite::where('id', $validated['visite_id'])
            ->where('wallet_id', $validated['wallet_id'])
            ->first();

        if (!$visite) {
            return response()->json([
                'success' => false,
                'message' => 'Visite introuvable ou wallet incorrect',
            ], 404);
        }

        // Vérifie que la visite n'est pas déjà confirmée
        if ($visite->status === 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Cette visite a déjà été confirmée',
                'confirmed_at' => $visite->confirmed_at,
            ], 422);
        }

        // Vérifie que les coordonnées de référence existent
        if (is_null($visite->latitude) || is_null($visite->longitude)) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune localisation de référence définie pour cette visite',
            ], 422);
        }

        // Calcule la distance entre le scanner et la référence
        $distance = GeoService::distanceMeters(
            $visite->latitude,
            $visite->longitude,
            $validated['latitude'],
            $validated['longitude']
        );
      
        // Trop loin → rejet
        if ($distance > self::MAX_DISTANCE_M) {
            return response()->json([
                'success'      => false,
                'message'      => sprintf(
                    'Vous êtes trop loin (%dm). Distance maximum : %dm.',
                    (int) $distance,
                    self::MAX_DISTANCE_M
                ),
                'distance_m'   => round($distance, 1),
                'max_distance' => self::MAX_DISTANCE_M,
            ], 422);
        }

        // Distance OK → confirme
        $visite->update([
            'confirmed_lat' => $validated['latitude'],
            'confirmed_lng' => $validated['longitude'],
            'wallet_visit' => $validated['wallet_visit'],
            'confirmed_at'  => now(),
            'status'        => 'confirmed',
            'distance_m'    => round($distance, 1),
        ]);

        return response()->json([
            'success'    => true,
            'message'    => 'Visite confirmée avec succès !',
            'distance_m' => round($distance, 1),
            'visite'     => [
                'id'           => $visite->id,
                'confirmed_at' => $visite->confirmed_at,
                'distance_m'   => $visite->distance_m,
                'status'       => $visite->status,
            ],
        ]);
    }

    /**
     * Crée une visite avec les coordonnées de référence
     * (appelé quand tu génères le QR de visite)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Générer un code unique
        $code = $this->generateUniqueCode();

        $visite = Visite::create([
            'wallet_id'   =>robus()->wallet->id ,
            'sidUser_id'  => robus()->id,
            'latitude'    => $validated['latitude'],
            'longitude'   => $validated['longitude'],
            'status'      => 'pending',
            'type' => 'visite',
            'code_visit'  => $code,
        ]);

        return response()->json([
            'success' => true,
            'data'    => [
                'visite_id' => $visite->id,
                'wallet_id' => $visite->wallet_id,
                'qr_data'   => json_encode([
                    'visite_id' => $visite->id,
                    'wallet_id' => $visite->wallet_id,
                    'code_visit'=> $visite->code_visit,
                    'type'      => 'visite',
                ]),
            ],
        ]);
    }

    /**
     * Génère un code unique alphanumérique pour la visite.
     *
     * @return string
     */
    private function generateUniqueCode(): string
    {
        do {
            // Génère un code de 8 caractères aléatoires (majuscules + chiffres)
            $code = Str::upper(Str::random(10));
        } while (Visite::where('code_visit', $code)->exists());

        return $code;
    }
}
