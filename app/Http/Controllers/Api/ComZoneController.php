<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Services\UserCreateService;
use App\Http\Services\ValidatorService;
use App\Mail\NewAdmin;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletBankSend;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletVersement;
use App\Models\Wallets\WalletWave;
use App\Models\Wallets\WalletXbet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Services\ComZoneCommissionService;
use App\Models\OperatorService;

class ComZoneController extends Controller
{
    private const COMMISSION_TRANSACTION_STATUS = 'SUCCESS';


    protected $userValidatorService;
    protected $createUserService;
    protected $comZoneCommissionService;
    protected array $transactionModels = [
        'transactions' => WalletTransaction::class,
        'credits' => WalletPhoneCredit::class,
        'paiements' => WalletPayment::class,
        'internationals' => WalletInter::class,
        'rechargements' => WalletReload::class,
        'virements' => WalletBank::class,
        'commandes' => WalletDeposit::class,
        'push' => WalletPush::class,
        'djamo' => WalletDjamo::class,
        'xbets' => WalletXbet::class,
        'bank_sends' => WalletBankSend::class,
        'wave' => WalletWave::class,
        'tresort_money' => WalletTresortMoney::class,
        'versements' => WalletVersement::class,
    ];
    protected array $transactionServiceLabels = [
        'DEPOT' => 'transactions',
        'CREDIT TELEPHONIQUE' => 'credits',
        'PAIEMENT DE FACTURE' => 'paiements',
        'TRANSFERT INTERNATIONAL' => 'internationals',
        'RECHARGEMENT' => 'rechargements',
        'Active Virements' => 'virements',
        'Active Commande' => 'commandes',
        'Active Push' => 'push',
        'Active Djamo' => 'djamo',
        'XBET' => 'xbets',
        'Active Bank Send' => 'bank_sends',
        'Active Wave' => 'wave',
        'Active Tresort Money' => 'tresort_money',
        'VERSEMENT BANCAIRE' => 'versements',
    ];

    public function __construct(
        ValidatorService $userValidatorService,
        UserCreateService $createUserService,
        ComZoneCommissionService $comZoneCommissionService
    ) {
        $this->userValidatorService = $userValidatorService;
        $this->createUserService = $createUserService;
        $this->comZoneCommissionService = $comZoneCommissionService;
    }

    public function operationService(Request $request, ?int $comZoneId = null)
    {
        $comZone = $comZoneId ? SidUser::whereId($comZoneId)->first() : robus();

        if (!$comZone || !$comZone->is_com_zone) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être un COM ZONE',
                'data' => [],
            ], 333);
        }

        $status = $request->get('status', 'SUCCESS');
        try {
            $dateRange = $this->resolveDateRange($request);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Format de date invalide',
                'data' => [],
            ], 333);
        }
        $recentTransactionsLimit = (int) $request->get('limit', 10);
        $recentTransactionsLimit = max(1, min($recentTransactionsLimit, 50));
        $walletIds = Wallet::whereIn(
            'sid_user_id',
            SidUser::where('com_zone_id', $comZone->id)->pluck('id')
        )->pluck('id');

        $linkedServices = $this->linkedOperationServices(
            $comZone,
            $walletIds,
            $dateRange['start'],
            $dateRange['end'],
            $status,
            $recentTransactionsLimit
        );

        return response()->json([
            'success' => true,
            'message' => 'Services et opérations COM ZONE',
            'data' => [
                'com_zone_id' => $comZone->id,
                'status' => $status,
                'filters' => [
                    'date' => $request->get('date'),
                    'start_date' => $request->get('start_date', $request->get('from')),
                    'end_date' => $request->get('end_date', $request->get('to')),
                    'resolved_start_date' => $dateRange['start']->toDateTimeString(),
                    'resolved_end_date' => $dateRange['end']->toDateTimeString(),
                    'limit' => $recentTransactionsLimit,
                ],
                'linked_services_totals' => $this->linkedServicesTotals($linkedServices),
                'linked_services' => $linkedServices,
            ],
        ]);
    }
    public function transactionCounters(Request $request)
    {
        $comZone = robus();

        if (!$comZone || !$comZone->is_com_zone) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être un COM ZONE',
                'data' => [],
            ], 333);
        }

        $status = $request->get('status', 'SUCCESS');
        try {
            $dateRange = $this->resolveDateRange($request);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Format de date invalide',
                'data' => [],
            ], 333);
        }
        $recentTransactionsLimit = (int) $request->get('limit', 10);
        $recentTransactionsLimit = max(1, min($recentTransactionsLimit, 50));
        $walletIds = Wallet::whereIn(
            'sid_user_id',
            SidUser::where('com_zone_id', $comZone->id)->pluck('id')
        )->pluck('id');

        $periods = $this->transactionCounterPeriods($dateRange['base_date'], $dateRange['start'], $dateRange['end']);
        $totals = [];
        $amountTotals = [];

        foreach (array_keys($periods) as $period) {
            $totals[$period] = 0;
            $amountTotals[$period] = 0;
        }

        $services = [];
        $servicesAmounts = [];
        $servicesCommissions = [];
        $servicesCommissionCounts = [];
        $servicesCommissionAmounts = [];
        $servicesAssigned = [];
        $servicesStats = [];
        $operatorServicesByService = $this->operatorServicesByService($comZone);


        foreach ($this->transactionModels as $service => $model) {
            $serviceCounters = [];
            $serviceAmounts = [];
            $serviceCommissions = [];
            $serviceCommissionCounts = [];
            $serviceCommissionAmounts = [];
            $operatorService = $operatorServicesByService[$service] ?? null;
            $isAssigned = (bool) $operatorService;

            foreach ($periods as $period => $periodRange) {
                $stats = $this->transactionStatsByPeriod($model, $walletIds, $periodRange['start'], $periodRange['end'], $status);
                $commissionStats = $this->transactionStatsByPeriod($model, $walletIds, $periodRange['start'], $periodRange['end'], self::COMMISSION_TRANSACTION_STATUS);

                $serviceCounters[$period] = $isAssigned ? $stats['count'] : 0;
                $serviceAmounts[$period] = $isAssigned ? $stats['amount'] : 0;
                $serviceCommissionCounts[$period] = $isAssigned ? $commissionStats['count'] : 0;
                $serviceCommissionAmounts[$period] = $isAssigned ? $commissionStats['amount'] : 0;
                $serviceCommissions[$period] = $isAssigned
                    ? $operatorService->calculateComZoneCommission($commissionStats['count'], $commissionStats['amount'])
                    : 0;

                if ($isAssigned) {
                    $totals[$period] += $stats['count'];
                    $amountTotals[$period] += $stats['amount'];
                }
            }

            $services[$service] = $serviceCounters;
            $servicesAmounts[$service] = $serviceAmounts;
            $servicesCommissions[$service] = $serviceCommissions;
            $servicesCommissionCounts[$service] = $serviceCommissionCounts;
            $servicesCommissionAmounts[$service] = $serviceCommissionAmounts;
            $servicesAssigned[$service] = $isAssigned;
            $servicesStats[$service] = [
                'is_assigned' => $isAssigned,
                'counts' => $serviceCounters,
                'amounts' => $serviceAmounts,
                'commission_status' => self::COMMISSION_TRANSACTION_STATUS,
                'commission_counts' => $serviceCommissionCounts,
                'commission_amounts' => $serviceCommissionAmounts,
                'commissions' => $serviceCommissions,
            ];
        }

        $linkedUsersCounters = $this->linkedUsersCounters($comZone->id);
        $linkedServices = $this->linkedOperationServices(
            $comZone,
            $walletIds,
            $dateRange['start'],
            $dateRange['end'],
            $status,
            $recentTransactionsLimit
        );

        return response()->json([
            'success' => true,
            'message' => 'Statistiques des transactions COM ZONE',
            'data' => [
                'com_zone_id' => $comZone->id,
                'status' => $status,
                'filters' => [
                    'date' => $request->get('date'),
                    'start_date' => $request->get('start_date', $request->get('from')),
                    'end_date' => $request->get('end_date', $request->get('to')),
                    'resolved_start_date' => $dateRange['start']->toDateTimeString(),
                    'resolved_end_date' => $dateRange['end']->toDateTimeString(),
                    'limit' => $recentTransactionsLimit,
                ],
                'linked_users_count' => $linkedUsersCounters['total'],
                'linked_users_counts' => $linkedUsersCounters,
                'totals' => [
                    'counts' => $totals,
                    'amounts' => $amountTotals,
                ],
                'services' => $services,
                'services_amounts' => $servicesAmounts,
                'services_commissions' => $servicesCommissions,
                'commissions_status' => self::COMMISSION_TRANSACTION_STATUS,
                'services_commission_counts' => $servicesCommissionCounts,
                'services_commission_amounts' => $servicesCommissionAmounts,
                'services_assigned' => $servicesAssigned,
                'services_stats' => $servicesStats,
                // 'linked_services_totals' => $this->linkedServicesTotals($linkedServices),
                // 'linked_services' => $linkedServices,
            ],
        ]);
    }

    protected function resolveDateRange(Request $request): array
    {
        $date = $request->get('date');
        $startDate = $request->get('start_date', $request->get('from'));
        $endDate = $request->get('end_date', $request->get('to'));

        if ($date) {
            $baseDate = Carbon::parse($date);

            return [
                'base_date' => $baseDate,
                'start' => $baseDate->copy()->startOfDay(),
                'end' => $baseDate->copy()->endOfDay(),
            ];
        }

        if ($startDate || $endDate) {
            $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->startOfDay();
            $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

            if ($start->greaterThan($end)) {
                [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
            }

            return [
                'base_date' => $end->copy(),
                'start' => $start,
                'end' => $end,
            ];
        }

        $now = Carbon::now();

        return [
            'base_date' => $now,
            'start' => $now->copy()->startOfDay(),
            'end' => $now->copy()->endOfDay(),
        ];
    }

    protected function linkedOperationServices(SidUser $comZone, $walletIds, Carbon $start, Carbon $end, ?string $status, int $limit): array
    {
        $operatorServices = OperatorService::with('operator')
            ->where('sid_user_id', $comZone->id)
            ->get();

        $services = [];

        foreach ($operatorServices as $operatorService) {
            $serviceKey = $this->serviceKeyForOperatorName($operatorService->operator->name ?? '');
            $model = $serviceKey ? $this->transactionModels[$serviceKey] : null;

            if (!$model) {
                $services[] = [
                    'operator_id' => $operatorService->operator_id,
                    'name' => $operatorService->operator->name ?? 'Service',
                    'service' => null,
                    'transactions_count' => 0,
                    'transactions_amount' => 0,
                    'commission_status' => self::COMMISSION_TRANSACTION_STATUS,
                    'commission_transactions_count' => 0,
                    'commission_transactions_amount' => 0,
                    'commission' => 0,
                    'numbers_rule' => $operatorService->numbers,
                    'amount_rule' => $operatorService->amount,
                    'transactions' => [],
                ];

                continue;
            }

            $query = $this->transactionQuery($model, $walletIds, $start, $end, $status);
            $commissionQuery = $this->transactionQuery($model, $walletIds, $start, $end, self::COMMISSION_TRANSACTION_STATUS);
            $transactionsCount = (clone $query)->count();
            $transactionsAmount = (float) (clone $query)->sum('amount');
            $commissionTransactionsCount = (clone $commissionQuery)->count();
            $commissionTransactionsAmount = (float) (clone $commissionQuery)->sum('amount');

            $transactionsQuery = (clone $query)->orderBy('created_at', 'desc');

            if (method_exists($model, 'wallet')) {
                $transactionsQuery->with('wallet.sidUser');
            }

            if (method_exists($model, 'provider')) {
                $transactionsQuery->with('provider');
            }

            $services[] = [
                'operator_id' => $operatorService->operator_id,
                'name' => $operatorService->operator->name ?? 'Service',
                'service' => $serviceKey,
                'transactions_count' => $transactionsCount,
                'transactions_amount' => $transactionsAmount,
                'commission_status' => self::COMMISSION_TRANSACTION_STATUS,
                'commission_transactions_count' => $commissionTransactionsCount,
                'commission_transactions_amount' => $commissionTransactionsAmount,
                'commission' => $operatorService->calculateComZoneCommission($commissionTransactionsCount, $commissionTransactionsAmount),
                'numbers_rule' => $operatorService->numbers,
                'amount_rule' => $operatorService->amount,
                'transactions' => $this->formatServiceTransactions($transactionsQuery->limit($limit)->get()),
            ];
        }

        return $services;
    }

    protected function formatServiceTransactions($transactions): array
    {
        return $transactions->map(function ($transaction) {
            $wallet = $transaction->wallet ?? null;
            $user = $wallet ? $wallet->sidUser : null;
            $provider = $transaction->provider ?? null;
            $createdAt = $transaction->created_at;

            return [
                'id' => $transaction->id,
                'amount' => (float) ($transaction->amount ?? 0),
                'status' => $transaction->status,
                'description' => $transaction->description,
                'created_at' => $createdAt instanceof Carbon ? $createdAt->toDateTimeString() : $createdAt,
                'provider' => $provider ? [
                    'id' => $provider->id,
                    'name' => $provider->name,
                ] : null,
                'wallet' => $wallet ? [
                    'id' => $wallet->id,
                    'wallet_id' => $wallet->wallet_id,
                ] : null,
                'user' => $user ? [
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'phone' => $user->phone,
                ] : null,
            ];
        })->values()->all();
    }

    protected function serviceKeyForOperatorName(string $operatorName): ?string
    {
        foreach (explode(',', $operatorName) as $name) {
            $name = trim($name);

            if (isset($this->transactionServiceLabels[$name])) {
                return $this->transactionServiceLabels[$name];
            }
        }

        return null;
    }

    protected function operatorServicesByService(SidUser $comZone): array
    {
        $operatorServices = OperatorService::with('operator')
            ->where('sid_user_id', $comZone->id)
            ->get();

        $services = [];

        foreach ($operatorServices as $operatorService) {
            $serviceKey = $this->serviceKeyForOperatorName($operatorService->operator->name ?? '');

            if ($serviceKey && !isset($services[$serviceKey])) {
                $services[$serviceKey] = $operatorService;
            }
        }

        return $services;
    }

    protected function linkedServicesTotals(array $linkedServices): array
    {
        return [
            'transactions_count' => array_sum(array_column($linkedServices, 'transactions_count')),
            'transactions_amount' => array_sum(array_column($linkedServices, 'transactions_amount')),
            'commission' => array_sum(array_column($linkedServices, 'commission')),
        ];
    }

    protected function linkedUsersCounters(int $comZoneId): array
    {
        $linkedUsersQuery = SidUser::where('com_zone_id', $comZoneId);

        return [
            'total' => (clone $linkedUsersQuery)->count(),
            'clients' => (clone $linkedUsersQuery)->where('is_custumers', true)->count(),
            'masters' => (clone $linkedUsersQuery)->where('is_master', true)->count(),
            'under_distributors' => (clone $linkedUsersQuery)->where('is_under_distributer', true)->count(),
        ];
    }

    protected function transactionCounterPeriods(Carbon $baseDate, Carbon $filterStart, Carbon $filterEnd): array
    {
        return [
            'filtered' => [
                'start' => $filterStart,
                'end' => $filterEnd,
            ],
            'daily' => [
                'start' => $baseDate->copy()->startOfDay(),
                'end' => $baseDate->copy()->endOfDay(),
            ],
            'weekly' => [
                'start' => $baseDate->copy()->startOfWeek(),
                'end' => $baseDate->copy()->endOfWeek(),
            ],
            'monthly' => [
                'start' => $baseDate->copy()->startOfMonth(),
                'end' => $baseDate->copy()->endOfMonth(),
            ],
            'quarterly' => [
                'start' => $baseDate->copy()->startOfQuarter(),
                'end' => $baseDate->copy()->endOfQuarter(),
            ],
            'yearly' => [
                'start' => $baseDate->copy()->startOfYear(),
                'end' => $baseDate->copy()->endOfYear(),
            ],
        ];
    }

    protected function transactionStatsByPeriod(string $model, $walletIds, Carbon $start, Carbon $end, ?string $status): array
    {
        $query = $this->transactionQuery($model, $walletIds, $start, $end, $status);

        return [
            'count' => (clone $query)->count(),
            'amount' => (float) (clone $query)->sum('amount'),
        ];
    }

    protected function transactionQuery(string $model, $walletIds, Carbon $start, Carbon $end, ?string $status)
    {
        $query = $model::whereIn('wallet_id', $walletIds)
            ->whereBetween('created_at', [$start, $end]);

        if ($status) {
            $query->where('status', $status);
        }

        return $query;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_under_distributors(Request $request)
    {

        $validator = $this->userValidatorService->validateComZoneDataUnder($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Champ est requis',
                'data' => $validator->errors()
            ], 333);
        }

        try {

            $response = $this->createUserService->createComZoneUnderDistributor(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso'),
                $request->file('image_trade'),
                $request->file('image_agence'),
            );

            $msg = "Sous distributeur crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = ' un sous distributeur ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                return response()->json(['success' => true, 'message' =>  $msg, 'user' => $response['user']], 200);
            } else {
                return response()->json(['success' => false, 'message' => $response['message']], 333);
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }

    public function store_under_masters(Request $request)
    {

        $validator = $this->userValidatorService->validateUserComData($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Champ est requis',
                'data' => $validator->errors()
            ], 333);
        }

        try {

            $response = $this->createUserService->createUnderComMaster(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Masters crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message =  ' Un MASTER ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                return response()->json(['success' => true, 'message' =>  $msg, 'user' => $response['user']], 200);
            } else {
                return response()->json(['success' => false, 'message' => $response['message']], 333);
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }

    public function store_add_compagnie(Request $request)
    {

        $validator = $this->userValidatorService->validateComZoneDataCompanie($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Champ est requis',
                'data' => $validator->errors()
            ], 333);
        }

        try {

            $response = $this->createUserService->createComZoneCompagnie(
                 $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso'),
                $request->file('image_trade'),
                $request->file('image_agence'),
            );

            $msg = "Compagnie crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message =  ' Une Compagnie ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                return response()->json(['success' => true, 'message' =>  $msg, 'user' => $response['user']], 200);
            } else {
                return response()->json(['success' => false, 'message' => $response['message']], 333);
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }

    public function store_under_custumers(Request $request)
    {

        $validator = $this->userValidatorService->validateUserComData($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Champ est requis',
                'data' => $validator->errors()
            ], 333);
        }

        try {

            $response = $this->createUserService->createComZoneCustumer(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
            );

            $msg = "Un client crée avec succès";

            if ($response['success']) {

                $user = $response['user'];

                $valeur_message = ' un client ';
                Mail::to($user->email)->send(new NewAdmin('Vous etes désormais ' . $valeur_message . env('APP_NAME') .
                    ' Votre email : '  . $user->email . ' Votre mot de passe : ' . $request->password));

                return response()->json(['success' => true, 'message' =>  $msg, 'user' => $response['user']], 200);
            } else {
                return response()->json(['success' => false, 'message' => $response['message']], 333);
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'une erreur inattendue s\'est produite. veuillez informer Support client ROBUS',
                ],
                301
            );
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}