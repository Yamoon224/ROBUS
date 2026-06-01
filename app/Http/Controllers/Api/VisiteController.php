<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\GeoService;
use App\Models\Sid\SidUser;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VisiteController extends Controller
{
    const MAX_DISTANCE_M = 100; // Distance max autorisée en mètres

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

        $visite = Visite::with('sidUser')->where('id', $validated['visite_id'])
            ->where('wallet_id', $validated['wallet_id'])
            ->first();

        if (!$visite) {
            return response()->json([
                'success' => false,
                'message' => 'Visite introuvable ou wallet incorrect',
            ], 404);
        }

        $validator = robus();
        $visiteComZoneId = $visite->sidUser_id;

        if (
            !$validator ||
            !$visiteComZoneId ||
            ((int) $validator->com_zone_id !== (int) $visiteComZoneId && (int) $validator->id !== (int) $visiteComZoneId)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas valider une visite d’un autre COM ZONE',
            ], 403);
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
            'wallet_visit' => $validator->wallet->id,
            'confirmed_at'  => now(),
            'status'        => 'confirmed',
            'distance_m'    => round($distance, 1),
        ]);

        return response()->json([
            'success'    => true,
            'message'    => 'Visite confirmée avec succès !',
            'distance_m' => round($distance, 1),
            'name_visite' => trim(optional($visite->sidUser)->firstname . ' ' . optional($visite->sidUser)->lastname),
            'photo_visite' => optional($visite->sidUser)->image,
            'visite'     => [
                'id'           => $visite->id,
                'confirmed_at' => $visite->confirmed_at,
                'distance_m'   => $visite->distance_m,
                'status'       => $visite->status,
            ],
        ]);
    }

    /**
     * Affiche l'historique des visites par valideur du COM ZONE.
     */
    public function historicals(Request $request)
    {
        $validated = $request->validate([
            'user_id'    => 'nullable|integer|exists:sid_users,id',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'limit'      => 'nullable|integer|min:1|max:100',
        ]);

        $comZone = robus();

        if (!$comZone || !$comZone->is_com_zone) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être un COM ZONE',
                'data' => [],
            ], 333);
        }

        $limit = (int) ($validated['limit'] ?? 10);
        $validatorQuery = SidUser::with('wallet')
            ->where(function ($query) use ($comZone) {
                $query->where('id', $comZone->id)
                    ->orWhere('com_zone_id', $comZone->id);
            })

            ->orderBy('firstname')
            ->orderBy('lastname');

        if (!empty($validated['user_id'])) {
            $validatorQuery->where('id', $validated['user_id']);
        }

        $validators = $validatorQuery->get();

        if (!empty($validated['user_id']) && $validators->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non rattaché à ce COM ZONE',
                'data' => [],
            ], 403);
        }

        $histories = $validators->map(function ($validator) use ($comZone, $validated, $limit) {
            $walletId = optional($validator->wallet)->id;

            $visitesQuery = Visite::where('sidUser_id', $comZone->id)
                ->where('status', 'confirmed')
                ->when($walletId, function ($query) use ($walletId) {
                    $query->where('wallet_visit', $walletId);
                }, function ($query) {
                    $query->whereRaw('1 = 0');
                })
                ->when(!empty($validated['start_date']), function ($query) use ($validated) {
                    $query->whereDate('created_at', '>=', $validated['start_date']);
                })
                ->when(!empty($validated['end_date']), function ($query) use ($validated) {
                    $query->whereDate('created_at', '<=', $validated['end_date']);
                });

            $total = (clone $visitesQuery)->count();
            $confirmed = (clone $visitesQuery)->where('status', 'confirmed')->count();

            return [
                'user' => [
                    'id' => $validator->id,
                    'firstname' => $validator->firstname,
                    'lastname' => $validator->lastname,
                    'phone' => $validator->phone,
                    'wallet_id' => $walletId,
                    'is_com_zone' => (bool) $validator->is_com_zone,
                ],
                'totals' => [
                    'all' => $total,
                    'confirmed' => $confirmed,
                ],
                'visites' => $visitesQuery
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get()
                    ->map(function ($visite) {
                        return [
                            'id' => $visite->id,
                            'wallet_id' => $visite->wallet_id,
                            'wallet_visit' => $visite->wallet_visit,
                            'code_visit' => $visite->code_visit,
                            'status' => $visite->status,
                            'latitude' => $visite->latitude,
                            'longitude' => $visite->longitude,
                            'confirmed_lat' => $visite->confirmed_lat,
                            'confirmed_lng' => $visite->confirmed_lng,
                            'distance_m' => $visite->distance_m,
                            'confirmed_at' => $visite->confirmed_at,
                            'created_at' => $visite->created_at,
                        ];
                    }),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Historiques des visites par valideur',
            'data' => [
                'com_zone_id' => $comZone->id,
                'filters' => [
                    'status' => 'confirmed',
                    'user_id' => $validated['user_id'] ?? null,
                    'start_date' => $validated['start_date'] ?? null,
                    'end_date' => $validated['end_date'] ?? null,
                    'limit' => $limit,
                ],
                'histories' => $histories,
            ],
        ]);
    }

    public function userhistoricals(Request $request)
    {
        $validated = $request->validate([
            'com_zone_id' => 'nullable|integer|exists:sid_users,id',
            'start_date'  => 'nullable|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'limit'       => 'nullable|integer|min:1|max:100',
        ]);

        $user = robus();
        $walletId = $user ? optional($user->wallet)->id : null;

        $limit = (int) ($validated['limit'] ?? 10);

        if (!$user || !$walletId) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet utilisateur introuvable',
                'data' => [],
            ], 404);
        }

        $visitedComZoneIdsQuery = Visite::where('wallet_visit', $walletId)
            ->where('status', 'confirmed')
            ->whereNotNull('sidUser_id')
            ->when(!empty($validated['com_zone_id']), function ($query) use ($validated) {
                $query->where('sidUser_id', $validated['com_zone_id']);
            })
            ->when(!empty($validated['start_date']), function ($query) use ($validated) {
                $query->whereDate('created_at', '>=', $validated['start_date']);
            })
            ->when(!empty($validated['end_date']), function ($query) use ($validated) {
                $query->whereDate('created_at', '<=', $validated['end_date']);
            });

        $comZones = SidUser::with('wallet')
            ->whereIn('id', (clone $visitedComZoneIdsQuery)->distinct()->pluck('sidUser_id'))
            ->orderBy('firstname')
            ->orderBy('lastname')
            ->get();

        if (!empty($validated['com_zone_id']) && $comZones->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune visite confirmée trouvée pour ce COM ZONE',
                'data' => [],
            ], 404);
        }

        $histories = $comZones->map(function ($comZone) use ($walletId, $validated, $limit) {
            $visitesQuery = Visite::where('sidUser_id', $comZone->id)
                ->where('wallet_visit', $walletId)
                ->where('status', 'confirmed')
                ->when(!empty($validated['start_date']), function ($query) use ($validated) {
                    $query->whereDate('created_at', '>=', $validated['start_date']);
                })
                ->when(!empty($validated['end_date']), function ($query) use ($validated) {
                    $query->whereDate('created_at', '<=', $validated['end_date']);
                });

            $total = (clone $visitesQuery)->count();
            $confirmed = (clone $visitesQuery)->where('status', 'confirmed')->count();

            return [
                'com_zone' => [
                    'id' => $comZone->id,
                    'firstname' => $comZone->firstname,
                    'lastname' => $comZone->lastname,
                    'phone' => $comZone->phone,
                    'photo_profil' => $comZone->image,
                    'wallet_id' => optional($comZone->wallet)->id,
                    'is_com_zone' => (bool) $comZone->is_com_zone,
                ],
                'totals' => [
                    'all' => $total,
                    'confirmed' => $confirmed,
                ],
                'visites' => $visitesQuery
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get()
                    ->map(function ($visite) {
                        return [
                            'id' => $visite->id,
                            'wallet_id' => $visite->wallet_id,
                            'wallet_visit' => $visite->wallet_visit,
                            'code_visit' => $visite->code_visit,
                            'status' => $visite->status,
                            'latitude' => $visite->latitude,
                            'longitude' => $visite->longitude,
                            'confirmed_lat' => $visite->confirmed_lat,
                            'confirmed_lng' => $visite->confirmed_lng,
                            'distance_m' => $visite->distance_m,
                            'confirmed_at' => $visite->confirmed_at,
                            'created_at' => $visite->created_at,
                        ];
                    }),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'COM ZONE qui vous ont visité',
            'data' => [
                'user_id' => $user->id,
                'wallet_id' => $walletId,
                'filters' => [
                    'status' => 'confirmed',
                    'com_zone_id' => $validated['com_zone_id'] ?? null,
                    'start_date' => $validated['start_date'] ?? null,
                    'end_date' => $validated['end_date'] ?? null,
                    'limit' => $limit,
                ],
                'histories' => $histories,
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
            'wallet_id'   => robus()->wallet->id,
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
                    'code_visit' => $visite->code_visit,
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
