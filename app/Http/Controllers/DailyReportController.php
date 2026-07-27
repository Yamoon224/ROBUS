<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class DailyReportController extends Controller
{
    /**
     * Ignore les résidus d'arrondi issus des colonnes DECIMAL/DOUBLE.
     */
    private const AUDIT_TOLERANCE = 0.02;

    /**
     * Tables de mouvements incluses dans le rapport journalier.
     */
    private const SERVICES = [
        'rechargements' => 'wallet_reloads',
        'transactions' => 'wallet_transactions',
        'paiements' => 'wallet_payments',
        'commandes_cartes' => 'wallet_deposits',
        'virements' => 'wallet_banks',
        'international' => 'wallet_inters',
        'credits_telephoniques' => 'wallet_phone_credits',
        'xbet' => 'wallet_xbets',
        'wave' => 'wallet_waves',
        'push' => 'wallet_pushes',
        'djamo' => 'wallet_djamos',
        'tresor_money' => 'wallet_tresort_money',
        'versements' => 'wallet_versements',
        'envois_bancaires' => 'wallet_bank_sends',
    ];

    private const ACTORS = [
        'superviseurs',
        'clients',
        'compagnies',
        'sous_distributeurs',
        'distributeurs',
        'administrateurs',
        'autres',
    ];

    private const RECEIVER_COLUMNS = [
        'wallet_transactions' => ['num_wallet', 'account_wallet', 'number_compte'],
        'wallet_banks' => ['number_compte', 'account_wallet'],
        'wallet_inters' => ['number_compte', 'account_wallet'],
    ];

    public function index(Request $request): View
    {
        $this->authorizeReport();
        $date = $this->validatedDate($request);

        return view('pages.reports.daily', [
            'report' => $this->generateReport($date),
        ]);
    }

    public function data(Request $request): JsonResponse
    {
        $this->authorizeReport();
        $validator = Validator::make($request->query(), [
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'La date doit respecter le format AAAA-MM-JJ.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $date = Carbon::createFromFormat('Y-m-d', $validator->validated()['date'] ?? now()->toDateString());

        return response()->json($this->generateReport($date));
    }

    public function actor(Request $request, string $actor): View
    {
        $this->authorizeReport();
        abort_unless(in_array($actor, self::ACTORS, true), 404);
        $date = $this->validatedDate($request);
        $fullActivityActors = ['superviseurs', 'distributeurs', 'sous_distributeurs'];

        return view('pages.reports.actor', [
            'actor' => $actor,
            'date' => $date->toDateString(),
            'users' => in_array($actor, $fullActivityActors, true)
                ? $this->walletActorActivityUsers($actor, $date->copy()->startOfDay(), $date->copy()->endOfDay())
                : $this->receivedReloadUsers($actor, $date->copy()->startOfDay(), $date->copy()->endOfDay()),
        ]);
    }

    public function downloadActorReport(Request $request, string $actor)
    {
        $this->authorizeReport();
        abort_unless(in_array($actor, ['superviseurs', 'distributeurs', 'sous_distributeurs'], true), 404);
        $date = $this->validatedDate($request);
        $users = $this->walletActorActivityUsers(
            $actor,
            $date->copy()->startOfDay(),
            $date->copy()->endOfDay()
        );

        return Pdf::loadView('pages.reports.pdf.actor', [
            'actor' => $actor,
            'date' => $date->toDateString(),
            'users' => $users,
        ])
            ->setPaper('a4', 'landscape')
            ->download('rapport-'.$actor.'-'.$date->format('Y-m-d').'.pdf');
    }

    public function supervisor(Request $request, int $supervisor): View
    {
        $this->authorizeReport();
        $date = $this->validatedDate($request);
        $details = $this->supervisorDetails($supervisor, $date->copy()->startOfDay(), $date->copy()->endOfDay());

        abort_unless($details, 404);

        return view('pages.reports.supervisor', [
            'date' => $date->toDateString(),
            'details' => $details,
        ]);
    }

    public function downloadSupervisorReport(Request $request, int $supervisor)
    {
        $this->authorizeReport();
        $date = $this->validatedDate($request);
        $details = $this->supervisorDetails($supervisor, $date->copy()->startOfDay(), $date->copy()->endOfDay());

        abort_unless($details, 404);

        return Pdf::loadView('pages.reports.pdf.supervisor', [
            'date' => $date->toDateString(),
            'details' => $details,
        ])
            ->setPaper('a4', 'landscape')
            ->download('rapport-superviseur-'.$supervisor.'-'.$date->format('Y-m-d').'.pdf');
    }

    public function actorUser(Request $request, string $actor, int $user): View
    {
        $this->authorizeReport();
        abort_unless(in_array($actor, array_diff(self::ACTORS, ['superviseurs']), true), 404);
        $date = $this->validatedDate($request);

        if (in_array($actor, ['distributeurs', 'sous_distributeurs'], true)) {
            $details = $this->supervisorDetails(
                $user,
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay(),
                $actor
            );

            abort_unless($details, 404);

            return view('pages.reports.supervisor', [
                'actor' => $actor,
                'date' => $date->toDateString(),
                'details' => $details,
            ]);
        }

        $details = $this->actorUserDetails($actor, $user, $date->copy()->startOfDay(), $date->copy()->endOfDay());

        abort_unless($details, 404);

        return view('pages.reports.actor-user', [
            'actor' => $actor,
            'date' => $date->toDateString(),
            'details' => $details,
        ]);
    }

    public function downloadActorUserReport(Request $request, string $actor, int $user)
    {
        $this->authorizeReport();
        abort_unless(in_array($actor, ['distributeurs', 'sous_distributeurs'], true), 404);
        $date = $this->validatedDate($request);
        $details = $this->supervisorDetails(
            $user,
            $date->copy()->startOfDay(),
            $date->copy()->endOfDay(),
            $actor
        );

        abort_unless($details, 404);

        return Pdf::loadView('pages.reports.pdf.supervisor', [
            'actor' => $actor,
            'date' => $date->toDateString(),
            'details' => $details,
        ])
            ->setPaper('a4', 'landscape')
            ->download('rapport-'.$actor.'-'.$user.'-'.$date->format('Y-m-d').'.pdf');
    }

    private function authorizeReport(): void
    {
        $user = Auth::guard('web')->user();

        if (! $user || (! $user->is_admin && ! $user->hasPermissionTo('rapport-journalier'))) {
            abort(403);
        }
    }

    private function validatedDate(Request $request): Carbon
    {
        $validated = $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
        ]);

        return Carbon::createFromFormat('Y-m-d', $validated['date'] ?? now()->toDateString());
    }

    private function generateReport(Carbon $date): array
    {
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        $services = [];
        $actors = $this->emptyActorTotals();

        foreach (self::SERVICES as $service => $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            $services[$service] = $this->serviceSummary($table, $start, $end);
            $this->mergeActorTotals($actors, $this->actorSummary($table, $start, $end));
        }

        $commissions = $this->commissionSummary($start, $end);
        $supervisors = $this->supervisorSummary($start, $end);
        $recharges = $services['rechargements'] ?? $this->emptyServiceSummary();
        $transactions = collect($services)
            ->except('rechargements')
            ->reduce(fn (array $total, array $item) => $this->addServiceSummary($total, $item), $this->emptyServiceSummary());
        $allMovements = $this->addServiceSummary($recharges, $transactions);

        return [
            'date' => $date->toDateString(),
            'periode' => [
                'debut' => $start->toDateTimeString(),
                'fin' => $end->toDateTimeString(),
            ],
            'resume' => [
                'rechargements' => $recharges,
                'transactions' => $transactions,
                'tous_les_mouvements' => $allMovements,
                'commissions' => $commissions,
            ],
            'superviseurs' => $supervisors,
            'par_acteur' => $actors,
            'par_service' => $services,
        ];
    }

    private function supervisorSummary(Carbon $start, Carbon $end): array
    {
        $hasSupervisorFlag = Schema::hasColumn('sid_users', 'is_supervisor');
        $hasMasterFlag = Schema::hasColumn('sid_users', 'is_master');
        $empty = [
            'nombre_actifs' => 0,
            'operations_rechargement' => 0,
            'montant_recharge' => 0.0,
            'montant_distribue' => 0.0,
            'operations_recues' => 0,
            'montant_recu' => 0.0,
            'details' => [],
        ];

        if (
            ! Schema::hasTable('wallet_reloads')
            || ! Schema::hasTable('wallets')
            || ! Schema::hasTable('sid_users')
            || ! Schema::hasColumn('wallet_reloads', 'wallet_id')
            || ! Schema::hasColumn('wallet_reloads', 'account_wallet')
            || ! Schema::hasColumn('wallets', 'wallet_id')
            || (! $hasSupervisorFlag && ! $hasMasterFlag)
        ) {
            return $empty;
        }

        $receivedAmount = $this->reloadReceivedAmountExpression();
        $rows = [];

        $sentQuery = DB::table('wallet_reloads')
            ->join('wallets as sender_wallets', 'wallet_reloads.wallet_id', '=', 'sender_wallets.id')
            ->join('sid_users as supervisors', 'sender_wallets.sid_user_id', '=', 'supervisors.id')
            ->where(function ($query) use ($hasSupervisorFlag, $hasMasterFlag) {
                if ($hasSupervisorFlag) {
                    $query->where('supervisors.is_supervisor', true);
                }

                if ($hasMasterFlag) {
                    $method = $hasSupervisorFlag ? 'orWhere' : 'where';
                    $query->{$method}('supervisors.is_master', true);
                }
            })
            ->whereBetween('wallet_reloads.created_at', [$start, $end])
            ->whereRaw("UPPER(wallet_reloads.status) = 'SUCCESS'");

        if (Schema::hasColumn('wallet_reloads', 'deleted_at')) {
            $sentQuery->whereNull('wallet_reloads.deleted_at');
        }

        $sent = $sentQuery
            ->selectRaw(
                "supervisors.id,
                 supervisors.firstname,
                 supervisors.lastname,
                 COUNT(*) AS operations_rechargement,
                 COALESCE(SUM(wallet_reloads.amount), 0) AS montant_recharge,
                 COALESCE(SUM({$receivedAmount}), 0) AS montant_distribue"
            )
            ->groupBy('supervisors.id', 'supervisors.firstname', 'supervisors.lastname')
            ->get();

        foreach ($sent as $supervisor) {
            $rows[$supervisor->id] = $this->supervisorRow(
                $supervisor->id,
                $supervisor->firstname,
                $supervisor->lastname
            );
            $rows[$supervisor->id]['operations_rechargement'] = (int) $supervisor->operations_rechargement;
            $rows[$supervisor->id]['montant_recharge'] = (float) $supervisor->montant_recharge;
            $rows[$supervisor->id]['montant_distribue'] = (float) $supervisor->montant_distribue;
        }

        $receivedQuery = DB::table('wallet_reloads')
            ->join('wallets as receiver_wallets', 'wallet_reloads.account_wallet', '=', 'receiver_wallets.wallet_id')
            ->join('sid_users as supervisors', 'receiver_wallets.sid_user_id', '=', 'supervisors.id')
            ->where(function ($query) use ($hasSupervisorFlag, $hasMasterFlag) {
                if ($hasSupervisorFlag) {
                    $query->where('supervisors.is_supervisor', true);
                }

                if ($hasMasterFlag) {
                    $method = $hasSupervisorFlag ? 'orWhere' : 'where';
                    $query->{$method}('supervisors.is_master', true);
                }
            })
            ->whereBetween('wallet_reloads.created_at', [$start, $end])
            ->whereRaw("UPPER(wallet_reloads.status) = 'SUCCESS'");

        if (Schema::hasColumn('wallet_reloads', 'deleted_at')) {
            $receivedQuery->whereNull('wallet_reloads.deleted_at');
        }

        $received = $receivedQuery
            ->selectRaw(
                "supervisors.id,
                 supervisors.firstname,
                 supervisors.lastname,
                 COUNT(*) AS operations_recues,
                 COALESCE(SUM({$receivedAmount}), 0) AS montant_recu"
            )
            ->groupBy('supervisors.id', 'supervisors.firstname', 'supervisors.lastname')
            ->get();

        foreach ($received as $supervisor) {
            $rows[$supervisor->id] ??= $this->supervisorRow(
                $supervisor->id,
                $supervisor->firstname,
                $supervisor->lastname
            );
            $rows[$supervisor->id]['operations_recues'] = (int) $supervisor->operations_recues;
            $rows[$supervisor->id]['montant_recu'] = (float) $supervisor->montant_recu;
        }

        $details = collect($rows)->sortBy('nom')->values();

        return [
            'nombre_actifs' => $details->count(),
            'operations_rechargement' => $details->sum('operations_rechargement'),
            'montant_recharge' => (float) $details->sum('montant_recharge'),
            'montant_distribue' => (float) $details->sum('montant_distribue'),
            'operations_recues' => $details->sum('operations_recues'),
            'montant_recu' => (float) $details->sum('montant_recu'),
            'details' => $details->all(),
        ];
    }

    private function supervisorRow(int $id, ?string $firstname, ?string $lastname): array
    {
        $name = trim($firstname.' '.$lastname);

        return [
            'id' => $id,
            'nom' => $name !== '' ? $name : 'Superviseur #'.$id,
            'operations_rechargement' => 0,
            'montant_recharge' => 0.0,
            'montant_distribue' => 0.0,
            'operations_recues' => 0,
            'montant_recu' => 0.0,
        ];
    }

    private function receivedReloadUsers(string $actor, Carbon $start, Carbon $end): array
    {
        if (
            ! Schema::hasTable('wallet_reloads')
            || ! Schema::hasTable('wallets')
            || ! Schema::hasTable('sid_users')
            || ! Schema::hasColumn('wallet_reloads', 'account_wallet')
        ) {
            return [];
        }

        $receivedAmount = $this->reloadReceivedAmountExpression();

        $query = DB::table('wallet_reloads')
            ->join('wallets as receiver_wallets', 'wallet_reloads.account_wallet', '=', 'receiver_wallets.wallet_id')
            ->join('sid_users as recipients', 'receiver_wallets.sid_user_id', '=', 'recipients.id')
            ->whereBetween('wallet_reloads.created_at', [$start, $end])
            ->whereRaw("UPPER(wallet_reloads.status) = 'SUCCESS'")
            ->whereRaw($this->actorCondition($actor, 'recipients'));

        if (Schema::hasColumn('wallet_reloads', 'deleted_at')) {
            $query->whereNull('wallet_reloads.deleted_at');
        }

        $users = $query
            ->selectRaw(
                "recipients.id,
                 recipients.firstname,
                 recipients.lastname,
                 COUNT(*) AS rechargements_recus,
                 COALESCE(SUM({$receivedAmount}), 0) AS montant_recu,
                 MAX(wallet_reloads.created_at) AS derniere_reception"
            )
            ->groupBy('recipients.id', 'recipients.firstname', 'recipients.lastname')
            ->get()
            ->mapWithKeys(fn ($user) => [(int) $user->id => [
                'id' => (int) $user->id,
                'nom' => trim($user->firstname.' '.$user->lastname),
                'rechargements_recus' => (int) $user->rechargements_recus,
                'montant_recu' => (float) $user->montant_recu,
                'montant_transactions_recu' => 0.0,
                'derniere_reception' => $user->derniere_reception,
                'transactions_recues' => 0,
            ]]);

        foreach ($this->receivedTransactionUsers($actor, $start, $end) as $recipient) {
            $user = $users->get($recipient['id'], [
                'id' => $recipient['id'],
                'nom' => $recipient['nom'],
                'rechargements_recus' => 0,
                'montant_recu' => 0.0,
                'montant_transactions_recu' => 0.0,
                'derniere_reception' => null,
                'transactions_recues' => 0,
            ]);
            $user['transactions_recues'] += $recipient['transactions_recues'];
            $user['montant_transactions_recu'] += $recipient['montant_transactions_recu'];
            $user['derniere_reception'] = collect([
                $user['derniere_reception'],
                $recipient['derniere_transaction'],
            ])->filter()->max();
            $users->put($recipient['id'], $user);
        }

        return $users
            ->sortByDesc(fn (array $user) => $user['rechargements_recus'] + $user['transactions_recues'])
            ->values()
            ->map(function (array $user, int $index) {
                $user['rang'] = $index + 1;

                return $user;
            })
            ->all();
    }

    private function receivedTransactionUsers(string $actor, Carbon $start, Carbon $end): array
    {
        $users = collect();

        foreach (self::RECEIVER_COLUMNS as $table => $candidates) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'wallet_id')) {
                continue;
            }

            $receiverColumn = collect($candidates)
                ->first(fn (string $column) => Schema::hasColumn($table, $column));

            if (! $receiverColumn) {
                continue;
            }

            $query = DB::table($table)
                ->join('wallets as receiver_wallets', $table.'.'.$receiverColumn, '=', 'receiver_wallets.wallet_id')
                ->join('sid_users as recipients', function ($join) {
                    $join->on('receiver_wallets.sid_user_id', '=', 'recipients.id')
                        ->orOn('receiver_wallets.wallet_id', '=', 'recipients.wallet_id');
                })
                ->whereBetween($table.'.created_at', [$start, $end])
                ->whereRaw($this->actorCondition($actor, 'recipients'));

            if (Schema::hasColumn($table, 'deleted_at')) {
                $query->whereNull($table.'.deleted_at');
            }

            if (Schema::hasColumn($table, 'status')) {
                $query->whereRaw("UPPER({$table}.status) = 'SUCCESS'");
            }

            $amount = Schema::hasColumn($table, 'amount') ? $table.'.amount' : '0';
            $amountReceived = Schema::hasColumn($table, 'amount_received')
                ? "COALESCE(NULLIF({$table}.amount_received, 0), {$amount})"
                : $amount;

            $rows = $query
                ->selectRaw(
                    "recipients.id,
                     recipients.firstname,
                     recipients.lastname,
                     COUNT(*) AS transactions_recues,
                     COALESCE(SUM({$amountReceived}), 0) AS montant_transactions_recu,
                     MAX({$table}.created_at) AS derniere_transaction"
                )
                ->groupBy('recipients.id', 'recipients.firstname', 'recipients.lastname')
                ->get();

            foreach ($rows as $row) {
                $id = (int) $row->id;
                $user = $users->get($id, [
                    'id' => $id,
                    'nom' => trim($row->firstname.' '.$row->lastname),
                    'transactions_recues' => 0,
                    'montant_transactions_recu' => 0.0,
                    'derniere_transaction' => null,
                ]);
                $user['transactions_recues'] += (int) $row->transactions_recues;
                $user['montant_transactions_recu'] += (float) $row->montant_transactions_recu;
                $user['derniere_transaction'] = collect([
                    $user['derniere_transaction'],
                    $row->derniere_transaction,
                ])->filter()->max();
                $users->put($id, $user);
            }
        }

        return $users->values()->all();
    }

    private function walletActorActivityUsers(string $actor, Carbon $start, Carbon $end): array
    {
        if (! Schema::hasTable('sid_users') || ! Schema::hasTable('wallets')) {
            return [];
        }

        // Une seule requête pour récupérer tous les utilisateurs + leurs wallets
        $rows = DB::table('sid_users as actors')
            ->whereRaw($this->actorCondition($actor, 'actors'))
            ->leftJoin('wallets', 'wallets.sid_user_id', '=', 'actors.id')
            ->select([
                'actors.id as user_id',
                'actors.firstname',
                'actors.lastname',
                'wallets.id as wallet_id',
                'wallets.wallet_id as wallet_ref',
            ])
            ->get();

        if ($rows->isEmpty()) {
            return [];
        }

        $userMeta = [];
        $walletIdToUser = [];  // (int) wallets.id → user_id
        $walletRefToUser = []; // (string) wallets.wallet_id → user_id

        foreach ($rows as $row) {
            $userId = (int) $row->user_id;

            if (! isset($userMeta[$userId])) {
                $userMeta[$userId] = [
                    'id' => $userId,
                    'nom' => trim(($row->firstname ?? '').' '.($row->lastname ?? '')) ?: 'Utilisateur #'.$userId,
                    'rechargements_envoyes' => 0,
                    'montant_recharge' => 0.0,
                    'montant_recu' => 0.0,
                    'rechargements_recus' => 0,
                    'transactions' => 0,
                    'transactions_recues' => 0,
                    'total_operations' => 0,
                    'derniere_activite' => null,
                    'operations_suspectes' => 0,
                    'ruptures_continuite' => 0,
                ];
            }

            if ($row->wallet_id !== null) {
                $walletIdToUser[(int) $row->wallet_id] = $userId;
            }

            if ($row->wallet_ref !== null && $row->wallet_ref !== '') {
                $walletRefToUser[(string) $row->wallet_ref] = $userId;
            }
        }

        $allWalletIds = array_keys($walletIdToUser);
        // account_wallet peut stocker soit le wallet_id (string) soit l'id entier (string)
        $allWalletRefs = array_values(array_unique(array_merge(
            array_map('strval', $allWalletIds),
            array_keys($walletRefToUser)
        )));

        if ($allWalletIds === []) {
            return [];
        }

        $updateActivity = function (array &$user, ?string $date): void {
            if ($date !== null && $date > ($user['derniere_activite'] ?? '')) {
                $user['derniere_activite'] = $date;
            }
        };

        // Rechargements envoyés — GROUP BY wallet_id (une seule requête)
        if (Schema::hasTable('wallet_reloads')) {
            $receivedAmount = $this->reloadReceivedAmountExpression();

            $sentQ = DB::table('wallet_reloads')
                ->whereIn('wallet_id', $allWalletIds)
                ->whereBetween('created_at', [$start, $end])
                ->whereRaw("UPPER(status) = 'SUCCESS'");

            if (Schema::hasColumn('wallet_reloads', 'deleted_at')) {
                $sentQ->whereNull('deleted_at');
            }

            $sentQ->selectRaw(
                "wallet_id,
                 COUNT(*) AS cnt,
                 COALESCE(SUM(amount), 0) AS montant,
                 MAX(created_at) AS derniere"
            )->groupBy('wallet_id')
                ->get()
                ->each(function ($row) use (&$userMeta, $walletIdToUser, $updateActivity): void {
                    $uid = $walletIdToUser[(int) $row->wallet_id] ?? null;
                    if ($uid === null || ! isset($userMeta[$uid])) {
                        return;
                    }
                    $userMeta[$uid]['rechargements_envoyes'] += (int) $row->cnt;
                    $userMeta[$uid]['montant_recharge'] += (float) $row->montant;
                    $updateActivity($userMeta[$uid], $row->derniere);
                });

            // Rechargements reçus — GROUP BY account_wallet (une seule requête)
            $rcvQ = DB::table('wallet_reloads')
                ->whereIn('account_wallet', $allWalletRefs)
                ->whereBetween('created_at', [$start, $end])
                ->whereRaw("UPPER(status) = 'SUCCESS'");

            if (Schema::hasColumn('wallet_reloads', 'deleted_at')) {
                $rcvQ->whereNull('deleted_at');
            }

            $rcvQ->selectRaw(
                "account_wallet,
                 COUNT(*) AS cnt,
                 COALESCE(SUM({$receivedAmount}), 0) AS montant_recu,
                 MAX(created_at) AS derniere"
            )->groupBy('account_wallet')
                ->get()
                ->each(function ($row) use (&$userMeta, $walletIdToUser, $walletRefToUser, $updateActivity): void {
                    $key = (string) $row->account_wallet;
                    $uid = $walletRefToUser[$key] ?? $walletIdToUser[(int) $row->account_wallet] ?? null;
                    if ($uid === null || ! isset($userMeta[$uid])) {
                        return;
                    }
                    $userMeta[$uid]['rechargements_recus'] += (int) $row->cnt;
                    $userMeta[$uid]['montant_recu'] += (float) $row->montant_recu;
                    $updateActivity($userMeta[$uid], $row->derniere);
                });
        }

        // Transactions envoyées — une requête GROUP BY par table de service
        foreach (collect(self::SERVICES)->except('rechargements') as $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'wallet_id')) {
                continue;
            }

            $q = DB::table($table)
                ->whereIn($table.'.wallet_id', $allWalletIds)
                ->whereBetween($table.'.created_at', [$start, $end]);

            if (Schema::hasColumn($table, 'deleted_at')) {
                $q->whereNull($table.'.deleted_at');
            }

            if (Schema::hasColumn($table, 'status')) {
                $q->whereRaw("UPPER({$table}.status) = 'SUCCESS'");
            }

            $q->selectRaw("{$table}.wallet_id, COUNT(*) AS cnt, MAX({$table}.created_at) AS derniere")
                ->groupBy($table.'.wallet_id')
                ->get()
                ->each(function ($row) use (&$userMeta, $walletIdToUser, $updateActivity): void {
                    $uid = $walletIdToUser[(int) $row->wallet_id] ?? null;
                    if ($uid === null || ! isset($userMeta[$uid])) {
                        return;
                    }
                    $userMeta[$uid]['transactions'] += (int) $row->cnt;
                    $updateActivity($userMeta[$uid], $row->derniere);
                });
        }

        // Transactions reçues — une requête GROUP BY par table réceptrice
        foreach (self::RECEIVER_COLUMNS as $table => $candidates) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'wallet_id')) {
                continue;
            }

            $receiverColumn = collect($candidates)
                ->first(fn (string $col) => Schema::hasColumn($table, $col));

            if (! $receiverColumn) {
                continue;
            }

            $q = DB::table($table)
                ->whereIn($table.'.'.$receiverColumn, $allWalletRefs)
                ->whereBetween($table.'.created_at', [$start, $end]);

            if (Schema::hasColumn($table, 'deleted_at')) {
                $q->whereNull($table.'.deleted_at');
            }

            if (Schema::hasColumn($table, 'status')) {
                $q->whereRaw("UPPER({$table}.status) = 'SUCCESS'");
            }

            $q->selectRaw(
                "{$table}.{$receiverColumn} AS ref_col,
                 COUNT(*) AS cnt,
                 MAX({$table}.created_at) AS derniere"
            )->groupBy($table.'.'.$receiverColumn)
                ->get()
                ->each(function ($row) use (&$userMeta, $walletIdToUser, $walletRefToUser, $updateActivity): void {
                    $key = (string) $row->ref_col;
                    $uid = $walletRefToUser[$key] ?? $walletIdToUser[(int) $row->ref_col] ?? null;
                    if ($uid === null || ! isset($userMeta[$uid])) {
                        return;
                    }
                    $userMeta[$uid]['transactions_recues'] += (int) $row->cnt;
                    $updateActivity($userMeta[$uid], $row->derniere);
                });
        }

        // Transactions validées — spécifique distributeurs / sous_distributeurs
        if (in_array($actor, ['distributeurs', 'sous_distributeurs'], true)) {
            $actorIds = array_keys($userMeta);

            foreach (['wallet_transactions'] as $table) {
                if (
                    ! Schema::hasTable($table)
                    || ! Schema::hasColumn($table, 'updated_by')
                    || ! Schema::hasColumn($table, 'updated_at')
                    || ! Schema::hasColumn($table, 'wallet_id')
                ) {
                    continue;
                }

                $q = DB::table($table)
                    ->whereIn($table.'.updated_by', $actorIds)
                    ->whereBetween($table.'.updated_at', [$start, $end])
                    ->whereNotIn($table.'.wallet_id', $allWalletIds);

                if (Schema::hasColumn($table, 'status')) {
                    $q->whereRaw("UPPER({$table}.status) = 'SUCCESS'");
                }

                if (Schema::hasColumn($table, 'deleted_at')) {
                    $q->whereNull($table.'.deleted_at');
                }

                $q->selectRaw("updated_by, COUNT(*) AS cnt, MAX(updated_at) AS derniere")
                    ->groupBy('updated_by')
                    ->get()
                    ->each(function ($row) use (&$userMeta, $updateActivity): void {
                        $uid = (int) $row->updated_by;
                        if (! isset($userMeta[$uid])) {
                            return;
                        }
                        $userMeta[$uid]['transactions'] += (int) $row->cnt;
                        $updateActivity($userMeta[$uid], $row->derniere);
                    });
            }
        }

        return collect($userMeta)
            ->map(function (array $user) {
                $user['total_operations'] = $user['rechargements_envoyes']
                    + $user['rechargements_recus']
                    + $user['transactions']
                    + $user['transactions_recues'];

                return $user;
            })
            ->filter(fn (array $user) => $user['total_operations'] > 0)
            ->sortByDesc('total_operations')
            ->values()
            ->map(function (array $user, int $index) {
                $user['rang'] = $index + 1;

                return $user;
            })
            ->all();
    }

    private function actorUserDetails(string $actor, int $userId, Carbon $start, Carbon $end): ?array
    {
        $user = DB::table('sid_users as recipients')
            ->where('recipients.id', $userId)
            ->whereRaw($this->actorCondition($actor, 'recipients'))
            ->select(['recipients.id', 'recipients.firstname', 'recipients.lastname', 'recipients.wallet_id'])
            ->first();

        if (! $user) {
            return null;
        }

        $wallets = DB::table('wallets')
            ->where(function ($query) use ($user) {
                $query->where('sid_user_id', $user->id);

                if ($user->wallet_id) {
                    $query->orWhere('wallet_id', $user->wallet_id);
                }
            })
            ->get(['id', 'wallet_id']);
        $walletReferences = $wallets
            ->pluck('wallet_id')
            ->merge($wallets->pluck('id'))
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (string) $value)
            ->unique()
            ->values()
            ->all();

        $rechargements = $walletReferences === []
            ? []
            : $this->receivedReloadDetails($walletReferences, $start, $end);

        return [
            'id' => (int) $user->id,
            'nom' => trim($user->firstname.' '.$user->lastname) ?: 'Utilisateur #'.$user->id,
            'wallet' => $wallets->pluck('wallet_id')->filter()->implode(', '),
            'rechargements' => $rechargements,
            'nombre_rechargements' => count($rechargements),
            'montant_recu' => (float) collect($rechargements)->sum('montant_recu'),
        ];
    }

    private function receivedReloadDetails(array $walletReferences, Carbon $start, Carbon $end): array
    {
        $receivedAmount = $this->reloadReceivedAmountExpression();
        $receiverBalanceBefore = $this->reloadBalanceExpression('amount_solde_before_drawal');
        $receiverBalanceAfter = $this->reloadBalanceExpression('amount_solde_drawal');

        return DB::table('wallet_reloads')
            ->leftJoin('wallets as sender_wallets', 'wallet_reloads.wallet_id', '=', 'sender_wallets.id')
            ->leftJoin('sid_users as senders', 'sender_wallets.sid_user_id', '=', 'senders.id')
            ->leftJoin('wallets as receiver_wallets', 'wallet_reloads.account_wallet', '=', 'receiver_wallets.wallet_id')
            ->leftJoin('currencies as receiver_currencies', 'receiver_wallets.currencie_id', '=', 'receiver_currencies.id')
            ->whereIn('wallet_reloads.account_wallet', $walletReferences)
            ->whereBetween('wallet_reloads.created_at', [$start, $end])
            ->when(Schema::hasColumn('wallet_reloads', 'deleted_at'), fn ($query) => $query->whereNull('wallet_reloads.deleted_at'))
            ->selectRaw(
                "wallet_reloads.id,
                 wallet_reloads.created_at,
                 wallet_reloads.status,
                 wallet_reloads.amount AS montant,
                 {$receivedAmount} AS montant_recu,
                 {$receiverBalanceBefore} AS solde_avant,
                 {$receiverBalanceAfter} AS solde_apres,
                 senders.firstname,
                 senders.lastname,
                 COALESCE(receiver_currencies.code, receiver_currencies.name, '—') AS devise_receveur"
            )
            ->orderByDesc('wallet_reloads.created_at')
            ->get()
            ->map(fn ($reload) => [
                'id' => (int) $reload->id,
                'date' => $reload->created_at,
                'statut' => $reload->status,
                'montant' => (float) $reload->montant,
                'montant_recu' => (float) $reload->montant_recu,
                'solde_avant' => $reload->solde_avant !== null ? (float) $reload->solde_avant : null,
                'solde_apres' => $reload->solde_apres !== null ? (float) $reload->solde_apres : null,
                'devise_receveur' => $reload->devise_receveur,
                'expediteur' => trim(($reload->firstname ?? '').' '.($reload->lastname ?? '')) ?: 'Non identifié',
            ])
            ->all();
    }

    private function actorCondition(string $actor, string $alias): string
    {
        $admin = $this->actorFlagExpression('is_admin', $alias);
        $supervisor = $this->actorFlagExpression('is_supervisor', $alias);
        $master = $this->actorFlagExpression('is_master', $alias);
        $company = $this->actorFlagExpression('is_company', $alias);
        $underDistributor = $this->actorFlagExpression('is_under_distributer', $alias);
        $customer = $this->actorFlagExpression('is_custumers', $alias);
        $distributor = $this->actorFlagExpression('is_distributer', $alias);
        $supervisorGroup = "({$supervisor} OR {$master})";

        return match ($actor) {
            'administrateurs' => $admin,
            'superviseurs' => "NOT ({$admin}) AND {$supervisorGroup}",
            'compagnies' => "NOT ({$admin} OR {$supervisorGroup}) AND {$company}",
            'sous_distributeurs' => "NOT ({$admin} OR {$supervisorGroup} OR {$company}) AND {$underDistributor}",
            'clients' => "NOT ({$admin} OR {$supervisorGroup} OR {$company} OR {$underDistributor}) AND {$customer}",
            'distributeurs' => "NOT ({$admin} OR {$supervisorGroup} OR {$company} OR {$underDistributor} OR {$customer}) AND {$distributor}",
            default => "NOT ({$admin} OR {$supervisorGroup} OR {$company} OR {$underDistributor} OR {$customer} OR {$distributor})",
        };
    }

    private function supervisorDetails(
        int $supervisorId,
        Carbon $start,
        Carbon $end,
        string $actor = 'superviseurs'
    ): ?array {
        if (
            ! Schema::hasTable('wallet_reloads')
            || ! Schema::hasTable('wallets')
            || ! Schema::hasTable('sid_users')
        ) {
            return null;
        }

        $supervisor = DB::table('sid_users as supervisors')
            ->where('supervisors.id', $supervisorId)
            ->whereRaw($this->actorCondition($actor, 'supervisors'))
            ->select([
                'supervisors.id',
                'supervisors.firstname',
                'supervisors.lastname',
                'supervisors.wallet_id as linked_wallet_id',
            ])
            ->first();

        if (! $supervisor) {
            return null;
        }

        $wallets = DB::table('wallets')
            ->leftJoin('currencies', 'wallets.currencie_id', '=', 'currencies.id')
            ->where(function ($query) use ($supervisor) {
                $query->where('wallets.sid_user_id', $supervisor->id);

                if ($supervisor->linked_wallet_id) {
                    $query->orWhere('wallets.wallet_id', $supervisor->linked_wallet_id);
                }
            })
            ->get([
                'wallets.id',
                'wallets.wallet_id',
                'wallets.amount',
                DB::raw("COALESCE(currencies.code, currencies.name, '—') AS devise"),
            ]);

        if ($wallets->isEmpty()) {
            return null;
        }

        $walletIds = $wallets->pluck('id')->map(fn ($id) => (int) $id)->unique()->values()->all();
        $walletReferences = $wallets
            ->pluck('wallet_id')
            ->merge($walletIds)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->map(fn ($value) => (string) $value)
            ->unique()
            ->values()
            ->all();
        $sentReloads = $this->supervisorSentReloads($walletIds, $start, $end);
        $receivedReloads = $this->supervisorReceivedReloads($walletReferences, $start, $end);
        $transactions = $this->supervisorTransactions($walletIds, $start, $end);
        $receivedTransactions = $this->supervisorReceivedTransactions($walletReferences, $start, $end);
        $receiverDebits = collect($receivedTransactions)
            ->where('direction_compte', 'sortie')
            ->map(fn (array $row) => [
                ...$row,
                'solde_superviseur_avant' => $row['solde_receveur_avant'],
                'solde_superviseur_apres' => $row['solde_receveur_apres'],
                'solde_receveur_avant' => null,
                'solde_receveur_apres' => null,
                'beneficiaire' => $row['expediteur'] ?: 'Non identifié',
            ]);
        $transactions = collect($transactions)
            ->merge($receiverDebits)
            ->unique(fn (array $row) => $row['service'].' #'.$row['id'])
            ->values()
            ->all();
        $receivedTransactions = collect($receivedTransactions)
            ->where('direction_compte', '!=', 'sortie')
            ->values()
            ->all();
        $accountCredits = collect($transactions)
            ->where('direction_compte', 'entree')
            ->map(fn (array $row) => [
                ...$row,
                'solde_receveur_avant' => $row['solde_superviseur_avant'],
                'solde_receveur_apres' => $row['solde_superviseur_apres'],
                'expediteur' => $row['type_operation'] ?: 'Crédit / rééquilibrage',
            ]);
        $transactions = collect($transactions)
            ->where('direction_compte', '!=', 'entree')
            ->values()
            ->all();
        $receivedTransactions = collect($receivedTransactions)
            ->merge($accountCredits)
            ->unique(fn (array $row) => $row['service'].' #'.$row['id'])
            ->sortByDesc('date')
            ->values()
            ->all();
        $validatedTransactions = in_array($actor, ['distributeurs', 'sous_distributeurs'], true)
            ? $this->actorValidatedTransactions(
                (int) $supervisor->id,
                $walletIds,
                $start,
                $end
            )
            : [];
        $transactions = collect($transactions)
            ->merge(collect($validatedTransactions)->where('direction_compte', 'sortie'))
            ->sortByDesc('date')
            ->values()
            ->all();
        $receivedTransactions = collect($receivedTransactions)
            ->merge(collect($validatedTransactions)->where('direction_compte', 'entree'))
            ->sortByDesc('date')
            ->values()
            ->all();
        $balancesAtDate = $this->supervisorBalancesAtDate($wallets, $end);
        $reconciliation = $this->supervisorReconciliation(
            $wallets,
            $sentReloads,
            $receivedReloads,
            $transactions,
            $receivedTransactions,
            $balancesAtDate
        );
        $transactions = $this->applyAuditedTransactionBalances(
            $transactions,
            $reconciliation['mouvements_audites']
        );
        $receivedTransactions = $this->applyAuditedBalances(
            $receivedTransactions,
            $reconciliation['mouvements_audites']
        );
        unset($reconciliation['mouvements_audites']);
        $suspicions = collect($reconciliation['anomalies'])->groupBy('reference');
        $sentReloads = $this->markSuspiciousRows($sentReloads, 'wallet_reloads', $suspicions);
        $receivedReloads = $this->markSuspiciousRows($receivedReloads, 'wallet_reloads', $suspicions);
        $transactions = $this->markSuspiciousRows($transactions, null, $suspicions);
        $receivedTransactions = $this->markSuspiciousRows($receivedTransactions, null, $suspicions);
        $auxiliaryWallets = $this->supervisorAuxiliaryWallets((int) $supervisor->id);
        $sentReloadsCollection = collect($sentReloads);
        $receivedReloadsCollection = collect($receivedReloads);
        $transactionsCollection = collect($transactions);
        $receivedTransactionsCollection = collect($receivedTransactions);
        $montantRecharge = (float) $sentReloadsCollection->sum('montant');
        $montantDistribue = (float) $sentReloadsCollection->sum('montant_recu');
        $montantRechargementsRecus = (float) $receivedReloadsCollection->sum('montant');
        $montantRecu = (float) $receivedReloadsCollection->sum('montant_recu');
        $volumeTransactions = (float) $transactionsCollection->sum('montant');
        $volumeTransactionsCredite = (float) $transactionsCollection->sum('montant_recu');
        $volumeTransactionsRecuesEnvoye = (float) $receivedTransactionsCollection->sum('montant');
        $volumeTransactionsRecues = (float) $receivedTransactionsCollection->sum('montant_recu');

        return [
            'id' => (int) $supervisor->id,
            'actor' => $actor,
            'nom' => trim($supervisor->firstname.' '.$supervisor->lastname),
            'wallet' => $wallets->pluck('wallet_id')->filter()->implode(', '),
            'devise' => $wallets->pluck('devise')->filter()->unique()->implode(', '),
            'resume' => [
                'rechargements_envoyes' => count($sentReloads),
                'montant_recharge' => $montantRecharge,
                'montant_distribue' => $montantDistribue,
                'difference_rechargements_envoyes' => round($montantRecharge - $montantDistribue, 2),
                'rechargements_recus' => count($receivedReloads),
                'montant_rechargements_recus' => $montantRechargementsRecus,
                'montant_recu' => $montantRecu,
                'difference_rechargements_recus' => round($montantRechargementsRecus - $montantRecu, 2),
                'transactions' => count($transactions),
                'volume_transactions' => $volumeTransactions,
                'volume_transactions_credite' => $volumeTransactionsCredite,
                'difference_transactions' => round($volumeTransactions - $volumeTransactionsCredite, 2),
                'transactions_recues' => count($receivedTransactions),
                'volume_transactions_recues_envoye' => $volumeTransactionsRecuesEnvoye,
                'volume_transactions_recues' => $volumeTransactionsRecues,
                'difference_transactions_recues' => round($volumeTransactionsRecuesEnvoye - $volumeTransactionsRecues, 2),
            ],
            'rechargements_envoyes' => $sentReloads,
            'rechargements_recus' => $receivedReloads,
            'transactions' => $transactions,
            'transactions_recues' => $receivedTransactions,
            'reconciliation' => $reconciliation,
            'comptes_separes' => $auxiliaryWallets,
        ];
    }

    private function supervisorReconciliation(
        $wallets,
        array $sentReloads,
        array $receivedReloads,
        array $transactions,
        array $receivedTransactions,
        array $balancesAtDate
    ): array {
        $successful = fn (array $row) => strtoupper((string) ($row['statut'] ?? 'SUCCESS')) === 'SUCCESS';
        $successfulSentReloads = collect($sentReloads)->filter($successful);
        $successfulReceivedReloads = collect($receivedReloads)->filter($successful);
        $successfulTransactions = collect($transactions)->filter($successful);
        $successfulReceivedTransactions = collect($receivedTransactions)->filter($successful);
        $audit = $this->supervisorWalletAudit(
            $wallets,
            $successfulSentReloads,
            $successfulReceivedReloads,
            $successfulTransactions,
            $successfulReceivedTransactions,
            $balancesAtDate
        );
        $walletAudits = collect($audit['wallets']);
        $openingBalance = $walletAudits->isNotEmpty()
            && $walletAudits->every(fn (array $wallet) => $wallet['solde_ouverture'] !== null)
                ? (float) $walletAudits->sum('solde_ouverture')
                : null;
        $recordedClosingBalance = $walletAudits->isNotEmpty()
            && $walletAudits->every(fn (array $wallet) => $wallet['solde_cloture'] !== null)
                ? (float) $walletAudits->sum('solde_cloture')
                : null;
        $reloadInputs = (float) $successfulReceivedReloads->sum('montant_recu');
        $receivedTransactionReferences = $successfulReceivedTransactions
            ->map(fn (array $row) => $row['service'].' #'.$row['id'])
            ->flip();
        $incomingTransactions = $successfulTransactions
            ->where('direction_compte', 'entree')
            ->reject(fn (array $row) => $receivedTransactionReferences->has($row['service'].' #'.$row['id']));
        $transactionInputs = (float) $successfulReceivedTransactions->sum('montant_recu')
            + (float) $incomingTransactions->sum('montant_recu');
        $reloadOutputs = (float) $successfulSentReloads->sum('montant');
        $transactionOutputs = (float) $successfulTransactions
            ->where('direction_compte', '!=', 'entree')
            ->sum('montant');
        $totalInputs = $reloadInputs + $transactionInputs;
        $totalOutputs = $reloadOutputs + $transactionOutputs;
        $calculatedClosingBalance = $openingBalance !== null
            ? round($openingBalance + $totalInputs - $totalOutputs, 2)
            : null;

        return [
            'solde_ouverture' => $openingBalance,
            'rechargements_recus' => $reloadInputs,
            'transactions_recues' => $transactionInputs,
            'total_entrees' => $totalInputs,
            'rechargements_envoyes' => $reloadOutputs,
            'transactions_effectuees' => $transactionOutputs,
            'total_sorties' => $totalOutputs,
            'solde_calcule' => $calculatedClosingBalance,
            'solde_cloture_enregistre' => $recordedClosingBalance,
            'ecart' => $calculatedClosingBalance !== null && $recordedClosingBalance !== null
                ? round($recordedClosingBalance - $calculatedClosingBalance, 2)
                : null,
            'solde_a_la_date' => collect($balancesAtDate)->every(fn (array $balance) => $balance['solde'] !== null)
                ? (float) collect($balancesAtDate)->sum('solde')
                : null,
            'audit_wallets' => $audit['wallets'],
            'anomalies' => $audit['anomalies'],
            'mouvements_audites' => $audit['mouvements'],
            'operations_suspectes' => collect($audit['anomalies'])
                ->where('affecte_operation', true)
                ->pluck('reference')
                ->unique()
                ->count(),
            'ruptures_continuite' => collect($audit['anomalies'])
                ->where('affecte_operation', false)
                ->count(),
            'mouvements_non_rattaches' => $audit['mouvements_non_rattaches'],
        ];
    }

    private function supervisorWalletAudit(
        $wallets,
        $sentReloads,
        $receivedReloads,
        $transactions,
        $receivedTransactions,
        array $balancesAtDate
    ): array {
        $events = collect();

        foreach ($sentReloads as $row) {
            $events->push($this->auditEvent(
                $row['supervisor_wallet_id'] ?? null,
                $row['date'],
                'Rechargement envoyé',
                'wallet_reloads',
                $row['id'],
                'sortie',
                $row['montant'],
                $row['solde_expediteur_avant'],
                $row['solde_expediteur_apres'],
                $row['type_operation'] ?? 'Rechargement',
                $row['type_received'] ?? 'Envoi UV'
            ));
        }

        foreach ($receivedReloads as $row) {
            $events->push($this->auditEvent(
                $row['supervisor_wallet_id'] ?? null,
                $row['date'],
                'Rechargement reçu',
                'wallet_reloads',
                $row['id'],
                'entree',
                $row['montant_recu'],
                $row['solde_receveur_avant'],
                $row['solde_receveur_apres'],
                $row['type_operation'] ?? 'Rechargement',
                $row['type_received'] ?? 'Réception UV'
            ));
        }

        foreach ($transactions as $row) {
            $direction = $row['direction_compte'] ?? 'sortie';
            $events->push($this->auditEvent(
                $row['supervisor_wallet_id'] ?? null,
                $row['date'],
                $direction === 'entree' ? 'Crédit / rééquilibrage' : 'Transaction effectuée',
                $row['service'],
                $row['id'],
                $direction,
                $direction === 'entree' ? $row['montant_recu'] : $row['montant'],
                $row['solde_superviseur_avant'],
                $row['solde_superviseur_apres'],
                $row['type_operation'] ?? null,
                $row['type_received'] ?? null
            ));
        }

        foreach ($receivedTransactions as $row) {
            $events->push($this->auditEvent(
                $row['supervisor_wallet_id'] ?? null,
                $row['date'],
                'Transaction reçue',
                $row['service'],
                $row['id'],
                'entree',
                $row['montant_recu'],
                $row['solde_receveur_avant'],
                $row['solde_receveur_apres'],
                $row['type_operation'] ?? null,
                $row['type_received'] ?? null
            ));
        }

        $walletGroups = collect($wallets)->groupBy(
            fn ($wallet) => (string) ($wallet->wallet_id ?: 'internal-'.$wallet->id)
        );
        $canonicalWalletIds = $walletGroups
            ->reduce(function ($canonicalIds, $group) {
                $canonicalId = (int) $group->first()->id;

                foreach ($group as $wallet) {
                    $canonicalIds->put((int) $wallet->id, $canonicalId);
                }

                return $canonicalIds;
            }, collect());
        $auditWallets = $walletGroups->map->first()->values();

        $unattachedCount = $events->whereNull('wallet_id')->count();
        $events = $events
            ->whereNotNull('wallet_id')
            ->map(function (array $event) use ($canonicalWalletIds) {
                $event['wallet_id'] = (int) $canonicalWalletIds->get(
                    (int) $event['wallet_id'],
                    (int) $event['wallet_id']
                );

                return $event;
            })
            ->unique(fn (array $event) => implode('|', [
                $event['wallet_id'],
                $event['reference'],
                $event['direction'],
                $event['avant'],
                $event['apres'],
            ]))
            ->values();
        $walletRows = collect();
        $anomalies = collect();
        $auditedMovements = collect();

        foreach ($auditWallets as $wallet) {
            $walletEvents = $events
                ->where('wallet_id', (int) $wallet->id)
                ->sortBy(fn (array $event) => $event['date'].'-'.$event['reference'])
                ->values();

            if ($walletEvents->isEmpty()) {
                continue;
            }

            $runningBalance = null;
            $walletEvents = $walletEvents
                ->map(function (array $event) use (&$runningBalance) {
                    $normalizedType = mb_strtolower(trim((string) ($event['type_transaction'] ?? '')));
                    $isCompensation = $normalizedType === 'compense';
                    $isDistributorValidation = str_starts_with(
                        $normalizedType,
                        'validation distributeur'
                    ) || str_starts_with(
                        $normalizedType,
                        'validation retrait distributeur'
                    );
                    $canReconstructBalance = (
                        $isCompensation
                        && $event['direction'] === 'entree'
                    ) || $isDistributorValidation;

                    if (
                        $canReconstructBalance
                        && $runningBalance !== null
                        && ($event['avant'] === null || $event['apres'] === null)
                    ) {
                        $event['avant'] = $runningBalance;
                        $event['apres'] = round(
                            $runningBalance + (float) $event['variation_attendue'],
                            2
                        );
                        $event['solde_reconstitue'] = true;
                    }

                    if ($event['apres'] !== null) {
                        $runningBalance = (float) $event['apres'];
                    }

                    return $event;
                })
                ->values();
            $auditedMovements->push(...$walletEvents->all());

            $previous = null;

            foreach ($walletEvents as $event) {
                if ($event['avant'] !== null && $event['apres'] !== null) {
                    $observedVariation = round($event['apres'] - $event['avant'], 2);
                    $operationGap = round($observedVariation - $event['variation_attendue'], 2);

                    if (abs($operationGap) >= self::AUDIT_TOLERANCE) {
                        $expectedBalance = round($event['avant'] + $event['variation_attendue'], 2);
                        $anomalies->push([
                            ...$event,
                            'type_anomalie' => 'Écart dans l’opération',
                            'ecart' => $operationGap,
                            'attendu' => $expectedBalance,
                            'observe' => $event['apres'],
                            'variation_observee' => $observedVariation,
                            'formule' => $event['direction'] === 'entree'
                                ? 'Solde avant + montant reçu = solde après attendu'
                                : 'Solde avant − montant envoyé = solde après attendu',
                            'cause_probable' => $operationGap < 0
                                ? 'Le solde après est inférieur au solde attendu : une sortie supplémentaire ou un débit incorrect doit être vérifié.'
                                : 'Le solde après est supérieur au solde attendu : une entrée supplémentaire ou un débit incomplet doit être vérifié.',
                            'operation_precedente' => null,
                            'affecte_operation' => true,
                        ]);
                    }
                }

                if ($previous && $previous['apres'] !== null && $event['avant'] !== null) {
                    $continuityGap = round($event['avant'] - $previous['apres'], 2);

                    if (abs($continuityGap) >= self::AUDIT_TOLERANCE) {
                        $anomalies->push([
                            ...$event,
                            'type_anomalie' => 'Mouvement manquant entre deux opérations',
                            'ecart' => $continuityGap,
                            'attendu' => $previous['apres'],
                            'observe' => $event['avant'],
                            'variation_observee' => $continuityGap,
                            'formule' => 'Solde après de l’opération précédente = solde avant de cette opération',
                            'cause_probable' => $continuityGap < 0
                                ? 'La transaction affichée peut être correcte : une diminution non expliquée existe entre le solde après de l’opération précédente et son solde avant.'
                                : 'La transaction affichée peut être correcte : une augmentation non expliquée existe entre le solde après de l’opération précédente et son solde avant.',
                            'operation_precedente' => [
                                'date' => $previous['date'],
                                'reference' => $previous['reference'],
                                'operation' => $previous['operation'],
                                'solde_apres' => $previous['apres'],
                            ],
                            'affecte_operation' => false,
                        ]);
                    }
                }

                $previous = $event;
            }

            $first = $walletEvents->first();
            $last = $walletEvents->last();
            $inputs = (float) $walletEvents->where('direction', 'entree')->sum('montant');
            $outputs = (float) $walletEvents->where('direction', 'sortie')->sum('montant');
            $calculated = $first['avant'] !== null
                ? round($first['avant'] + $inputs - $outputs, 2)
                : null;

            $walletRows->push([
                'wallet_id' => (int) $wallet->id,
                'wallet' => $wallet->wallet_id,
                'devise' => $wallet->devise,
                'operations' => $walletEvents->count(),
                'solde_ouverture' => $first['avant'],
                'entrees' => $inputs,
                'sorties' => $outputs,
                'solde_calcule' => $calculated,
                'solde_cloture' => $last['apres'],
                'ecart' => $calculated !== null && $last['apres'] !== null
                    ? round($last['apres'] - $calculated, 2)
                    : null,
                'solde_a_la_date' => $balancesAtDate[$wallet->id]['solde'] ?? null,
                'date_solde' => $balancesAtDate[$wallet->id]['date'] ?? null,
                'source_solde' => $balancesAtDate[$wallet->id]['source'] ?? null,
            ]);
        }

        foreach ($sentReloads as $row) {
            $recipientAnomaly = $this->balanceOperationAnomaly(
                $row,
                'wallet_reloads',
                'Rechargement envoyé — bénéficiaire',
                'montant_recu',
                'solde_receveur_avant',
                'solde_receveur_apres'
            );

            if ($recipientAnomaly) {
                $anomalies->push($recipientAnomaly);
            }
        }

        foreach ($transactions as $row) {
            $recipientAnomaly = $this->balanceOperationAnomaly(
                $row,
                $row['service'],
                'Transaction effectuée — bénéficiaire',
                'montant_recu',
                'solde_receveur_avant',
                'solde_receveur_apres'
            );

            if ($recipientAnomaly) {
                $anomalies->push($recipientAnomaly);
            }
        }

        return [
            'wallets' => $walletRows->all(),
            'anomalies' => $anomalies->sortBy('date')->values()->all(),
            'mouvements' => $auditedMovements->values()->all(),
            'mouvements_non_rattaches' => $unattachedCount,
        ];
    }

    private function applyAuditedBalances(array $rows, array $auditedMovements): array
    {
        $movements = collect($auditedMovements)
            ->where('direction', 'entree')
            ->keyBy('reference');

        return collect($rows)->map(function (array $row) use ($movements) {
            $reference = $row['service'].' #'.$row['id'];
            $movement = $movements->get($reference);

            if (! $movement) {
                return $row;
            }

            return [
                ...$row,
                'solde_receveur_avant' => $movement['avant'],
                'solde_receveur_apres' => $movement['apres'],
                'solde_reconstitue' => (bool) ($movement['solde_reconstitue'] ?? false),
            ];
        })->all();
    }

    private function applyAuditedTransactionBalances(array $rows, array $auditedMovements): array
    {
        $movements = collect($auditedMovements)
            ->where('direction', 'sortie')
            ->keyBy('reference');

        return collect($rows)->map(function (array $row) use ($movements) {
            $reference = $row['service'].' #'.$row['id'];
            $movement = $movements->get($reference);

            if (! $movement) {
                return $row;
            }

            return [
                ...$row,
                'solde_superviseur_avant' => $movement['avant'],
                'solde_superviseur_apres' => $movement['apres'],
                'solde_reconstitue' => (bool) ($movement['solde_reconstitue'] ?? false),
            ];
        })->all();
    }

    private function balanceOperationAnomaly(
        array $row,
        string $service,
        string $operation,
        string $amountKey,
        string $beforeKey,
        string $afterKey
    ): ?array {
        $before = $row[$beforeKey] ?? null;
        $after = $row[$afterKey] ?? null;

        if ($before === null || $after === null) {
            return null;
        }

        $amount = (float) ($row[$amountKey] ?? 0);
        $expected = round((float) $before + $amount, 2);
        $gap = round((float) $after - $expected, 2);

        if (abs($gap) < self::AUDIT_TOLERANCE) {
            return null;
        }

        return [
            'wallet_id' => $row['supervisor_wallet_id'] ?? null,
            'date' => $row['date'],
            'operation' => $operation,
            'service' => $service,
            'reference' => $service.' #'.$row['id'],
            'direction' => 'entree',
            'montant' => $amount,
            'avant' => (float) $before,
            'apres' => (float) $after,
            'type_transaction' => $row['type_operation']
                ?? ($service === 'wallet_reloads' ? 'Rechargement' : null),
            'type_received' => $row['type_received']
                ?? ($service === 'wallet_reloads' ? 'Réception UV' : null),
            'type_anomalie' => 'Écart sur le solde du bénéficiaire',
            'ecart' => $gap,
            'attendu' => $expected,
            'observe' => (float) $after,
            'variation_observee' => round((float) $after - (float) $before, 2),
            'formule' => 'Solde bénéficiaire avant + montant crédité = solde bénéficiaire après attendu',
            'cause_probable' => $gap < 0
                ? 'Le bénéficiaire a reçu moins que le solde attendu ou une autre sortie a été enregistrée au même moment.'
                : 'Le bénéficiaire a reçu plus que le solde attendu ou une autre entrée a été enregistrée au même moment.',
            'operation_precedente' => null,
            'affecte_operation' => true,
        ];
    }

    private function markSuspiciousRows(array $rows, ?string $fixedService, $suspicions): array
    {
        return collect($rows)->map(function (array $row) use ($fixedService, $suspicions) {
            $reference = ($fixedService ?: $row['service']).' #'.$row['id'];
            $anomalies = collect($suspicions->get($reference, []))
                ->where('affecte_operation', true);
            $largestGap = $anomalies
                ->sortByDesc(fn (array $anomaly) => abs((float) $anomaly['ecart']))
                ->first();

            return [
                ...$row,
                'suspecte' => $anomalies->isNotEmpty(),
                'ecart_suspect' => $largestGap['ecart'] ?? null,
                'raison_suspecte' => $anomalies->pluck('type_anomalie')->unique()->implode(' · '),
            ];
        })->all();
    }

    private function supervisorBalancesAtDate($wallets, Carbon $end): array
    {
        $balances = [];

        foreach ($wallets as $wallet) {
            if ($end->isToday()) {
                $balances[(int) $wallet->id] = [
                    'solde' => (float) $wallet->amount,
                    'date' => now()->toDateTimeString(),
                    'source' => 'Solde actuel du wallet',
                    'mouvements_posterieurs' => 0,
                    'entrees_posterieures' => 0.0,
                    'sorties_posterieures' => 0.0,
                ];

                continue;
            }

            $references = [(string) $wallet->wallet_id, (string) $wallet->id];
            $laterInputs = 0.0;
            $laterOutputs = 0.0;
            $laterMovements = 0;
            $addMovements = function (
                string $table,
                string $filterColumn,
                array $filterValues,
                string $direction
            ) use ($end, &$laterInputs, &$laterOutputs, &$laterMovements) {
                if (
                    ! Schema::hasTable($table)
                    || ! Schema::hasColumn($table, $filterColumn)
                    || ! Schema::hasColumn($table, 'created_at')
                ) {
                    return;
                }

                $query = DB::table($table)
                    ->whereIn($table.'.'.$filterColumn, $filterValues)
                    ->where($table.'.created_at', '>', $end)
                    ->where($table.'.created_at', '<=', now());

                if (
                    $table === 'wallet_transactions'
                    && $filterColumn === 'wallet_id'
                    && Schema::hasColumn($table, 'balance_before')
                    && Schema::hasColumn($table, 'amount_solde')
                ) {
                    if ($direction === 'entree') {
                        $query->where(function ($directionQuery) use ($table) {
                            $directionQuery
                                ->whereRaw("LOWER(TRIM({$table}.type_transaction)) = 'compense'")
                                ->orWhereColumn($table.'.amount_solde', '>', $table.'.balance_before');
                        });
                    } else {
                        $query
                            ->whereRaw(
                                "(LOWER(TRIM(COALESCE({$table}.type_transaction, ''))) <> 'compense')"
                            )
                            ->where(function ($directionQuery) use ($table) {
                                $directionQuery
                                    ->whereColumn($table.'.amount_solde', '<', $table.'.balance_before')
                                    ->orWhereNull($table.'.balance_before')
                                    ->orWhereNull($table.'.amount_solde');
                            });
                    }
                }

                if (Schema::hasColumn($table, 'status')) {
                    $query->whereRaw("UPPER({$table}.status) = 'SUCCESS'");
                }

                if (Schema::hasColumn($table, 'deleted_at')) {
                    $query->whereNull($table.'.deleted_at');
                }

                $amount = Schema::hasColumn($table, 'amount') ? $table.'.amount' : '0';
                $movementAmount = $direction === 'entree' && Schema::hasColumn($table, 'amount_received')
                    ? "COALESCE(NULLIF({$table}.amount_received, 0), {$amount})"
                    : $amount;
                $summary = $query
                    ->selectRaw("COUNT(*) AS total, COALESCE(SUM({$movementAmount}), 0) AS amount")
                    ->first();
                $laterMovements += (int) ($summary->total ?? 0);

                if ($direction === 'entree') {
                    $laterInputs += (float) ($summary->amount ?? 0);
                } else {
                    $laterOutputs += (float) ($summary->amount ?? 0);
                }
            };

            $addMovements(
                'wallet_reloads',
                'wallet_id',
                [(int) $wallet->id],
                'sortie'
            );
            $addMovements(
                'wallet_reloads',
                'account_wallet',
                $references,
                'entree'
            );

            foreach (collect(self::SERVICES)->except('rechargements') as $table) {
                $addMovements(
                    $table,
                    'wallet_id',
                    [(int) $wallet->id],
                    'sortie'
                );
            }

            $addMovements(
                'wallet_transactions',
                'wallet_id',
                [(int) $wallet->id],
                'entree'
            );

            foreach (self::RECEIVER_COLUMNS as $table => $receiverColumns) {
                $receiverColumn = collect($receiverColumns)
                    ->first(fn (string $column) => Schema::hasColumn($table, $column));

                if ($receiverColumn) {
                    $addMovements(
                        $table,
                        $receiverColumn,
                        $references,
                        'entree'
                    );
                }
            }

            $balances[(int) $wallet->id] = [
                'solde' => round((float) $wallet->amount - $laterInputs + $laterOutputs, 2),
                'date' => $end->toDateTimeString(),
                'source' => $laterMovements === 0
                    ? 'Solde actuel inchangé depuis la date sélectionnée'
                    : "Reconstitué depuis le solde actuel ({$laterMovements} mouvement(s) postérieur(s))",
                'mouvements_posterieurs' => $laterMovements,
                'entrees_posterieures' => $laterInputs,
                'sorties_posterieures' => $laterOutputs,
            ];
        }

        return $balances;
    }

    private function auditEvent(
        ?int $walletId,
        $date,
        string $operation,
        string $service,
        int $id,
        string $direction,
        float $amount,
        ?float $before,
        ?float $after,
        ?string $typeTransaction = null,
        ?string $typeReceived = null
    ): array {
        return [
            'wallet_id' => $walletId,
            'date' => $date,
            'operation' => $operation,
            'service' => $service,
            'reference' => $service.' #'.$id,
            'direction' => $direction,
            'montant' => (float) $amount,
            'avant' => $before,
            'apres' => $after,
            'type_transaction' => $typeTransaction,
            'type_received' => $typeReceived,
            'variation_attendue' => $direction === 'entree' ? (float) $amount : -(float) $amount,
        ];
    }

    private function supervisorAuxiliaryWallets(int $supervisorId): array
    {
        $wallets = [];

        foreach ([
            'wallet_admin' => 'wallet_admins',
            'wallet_commission' => 'wallet_commissions',
        ] as $key => $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'sid_user_id')) {
                $wallets[$key] = [
                    'rattache' => false,
                    'solde' => 0.0,
                    'devise' => '—',
                ];

                continue;
            }

            $row = DB::table($table)
                ->leftJoin('currencies', $table.'.currencie_id', '=', 'currencies.id')
                ->where($table.'.sid_user_id', $supervisorId)
                ->whereNull($table.'.deleted_at')
                ->selectRaw(
                    "COALESCE(SUM({$table}.amount), 0) AS solde,
                     COALESCE(MAX(currencies.code), MAX(currencies.name), '—') AS devise"
                )
                ->first();

            $wallets[$key] = [
                'rattache' => $row && (float) $row->solde !== 0.0
                    ? true
                    : DB::table($table)->where('sid_user_id', $supervisorId)->exists(),
                'solde' => (float) ($row->solde ?? 0),
                'devise' => $row->devise ?? '—',
            ];
        }

        return $wallets;
    }

    private function supervisorSentReloads(array $walletIds, Carbon $start, Carbon $end): array
    {
        $receivedAmount = $this->reloadReceivedAmountExpression();
        $senderBalanceBefore = $this->reloadBalanceExpression('balance_before');
        $senderBalanceAfter = $this->reloadBalanceExpression('amount_solde');
        $receiverBalanceBefore = $this->reloadBalanceExpression('amount_solde_before_drawal');
        $receiverBalanceAfter = $this->reloadBalanceExpression('amount_solde_drawal');
        $operationType = $this->reloadBalanceExpression('type_transaction');
        $receivedType = $this->reloadBalanceExpression('type_received');

        return DB::table('wallet_reloads')
            ->leftJoin('wallets as receiver_wallets', 'wallet_reloads.account_wallet', '=', 'receiver_wallets.wallet_id')
            ->leftJoin('sid_users as recipients', 'receiver_wallets.sid_user_id', '=', 'recipients.id')
            ->leftJoin('currencies as receiver_currencies', 'receiver_wallets.currencie_id', '=', 'receiver_currencies.id')
            ->whereIn('wallet_reloads.wallet_id', $walletIds)
            ->whereBetween('wallet_reloads.created_at', [$start, $end])
            ->when(Schema::hasColumn('wallet_reloads', 'deleted_at'), fn ($query) => $query->whereNull('wallet_reloads.deleted_at'))
            ->selectRaw(
                "wallet_reloads.id,
                 wallet_reloads.wallet_id AS supervisor_wallet_id,
                 wallet_reloads.created_at,
                 wallet_reloads.status,
                 wallet_reloads.amount AS montant,
                 {$receivedAmount} AS montant_recu,
                 {$senderBalanceBefore} AS solde_expediteur_avant,
                 {$senderBalanceAfter} AS solde_expediteur_apres,
                 {$receiverBalanceBefore} AS solde_receveur_avant,
                 {$receiverBalanceAfter} AS solde_receveur_apres,
                 {$operationType} AS type_operation,
                 {$receivedType} AS type_received,
                 wallet_reloads.account_wallet,
                 recipients.firstname,
                 recipients.lastname,
                 COALESCE(receiver_currencies.code, receiver_currencies.name, '—') AS devise_receveur"
            )
            ->orderByDesc('wallet_reloads.created_at')
            ->get()
            ->map(fn ($reload) => [
                'id' => (int) $reload->id,
                'supervisor_wallet_id' => (int) $reload->supervisor_wallet_id,
                'date' => $reload->created_at,
                'statut' => $reload->status,
                'type_operation' => $reload->type_operation,
                'type_received' => $reload->type_received,
                'montant' => (float) $reload->montant,
                'montant_recu' => (float) $reload->montant_recu,
                'solde_expediteur_avant' => $reload->solde_expediteur_avant !== null ? (float) $reload->solde_expediteur_avant : null,
                'solde_expediteur_apres' => $reload->solde_expediteur_apres !== null ? (float) $reload->solde_expediteur_apres : null,
                'solde_receveur_avant' => $reload->solde_receveur_avant !== null ? (float) $reload->solde_receveur_avant : null,
                'solde_receveur_apres' => $reload->solde_receveur_apres !== null ? (float) $reload->solde_receveur_apres : null,
                'beneficiaire' => trim(($reload->firstname ?? '').' '.($reload->lastname ?? '')) ?: $reload->account_wallet,
                'devise_receveur' => $reload->devise_receveur,
            ])
            ->all();
    }

    private function supervisorReceivedReloads(array $walletReferences, Carbon $start, Carbon $end): array
    {
        $receivedAmount = $this->reloadReceivedAmountExpression();
        $receiverBalanceBefore = $this->reloadBalanceExpression('amount_solde_before_drawal');
        $receiverBalanceAfter = $this->reloadBalanceExpression('amount_solde_drawal');
        $operationType = $this->reloadBalanceExpression('type_transaction');
        $receivedType = $this->reloadBalanceExpression('type_received');

        return DB::table('wallet_reloads')
            ->leftJoin('wallets as sender_wallets', 'wallet_reloads.wallet_id', '=', 'sender_wallets.id')
            ->leftJoin('sid_users as senders', 'sender_wallets.sid_user_id', '=', 'senders.id')
            ->leftJoin('wallets as receiver_wallets', function ($join) {
                $join->on('wallet_reloads.account_wallet', '=', 'receiver_wallets.wallet_id')
                    ->orOn('wallet_reloads.account_wallet', '=', 'receiver_wallets.id');
            })
            ->leftJoin('currencies as receiver_currencies', 'receiver_wallets.currencie_id', '=', 'receiver_currencies.id')
            ->whereIn('wallet_reloads.account_wallet', $walletReferences)
            ->whereBetween('wallet_reloads.created_at', [$start, $end])
            ->when(Schema::hasColumn('wallet_reloads', 'deleted_at'), fn ($query) => $query->whereNull('wallet_reloads.deleted_at'))
            ->selectRaw(
                "wallet_reloads.id,
                 receiver_wallets.id AS supervisor_wallet_id,
                 wallet_reloads.created_at,
                 wallet_reloads.status,
                 wallet_reloads.amount AS montant,
                 {$receivedAmount} AS montant_recu,
                 {$receiverBalanceBefore} AS solde_receveur_avant,
                 {$receiverBalanceAfter} AS solde_receveur_apres,
                 {$operationType} AS type_operation,
                 {$receivedType} AS type_received,
                 senders.firstname,
                 senders.lastname,
                 COALESCE(receiver_currencies.code, receiver_currencies.name, '—') AS devise_receveur"
            )
            ->orderByDesc('wallet_reloads.created_at')
            ->get()
            ->map(fn ($reload) => [
                'id' => (int) $reload->id,
                'supervisor_wallet_id' => $reload->supervisor_wallet_id !== null ? (int) $reload->supervisor_wallet_id : null,
                'date' => $reload->created_at,
                'statut' => $reload->status,
                'type_operation' => $reload->type_operation,
                'type_received' => $reload->type_received,
                'montant' => (float) $reload->montant,
                'montant_recu' => (float) $reload->montant_recu,
                'solde_receveur_avant' => $reload->solde_receveur_avant !== null ? (float) $reload->solde_receveur_avant : null,
                'solde_receveur_apres' => $reload->solde_receveur_apres !== null ? (float) $reload->solde_receveur_apres : null,
                'expediteur' => trim(($reload->firstname ?? '').' '.($reload->lastname ?? '')) ?: 'Non identifié',
                'devise_receveur' => $reload->devise_receveur,
            ])
            ->all();
    }

    private function supervisorTransactions(array $walletIds, Carbon $start, Carbon $end): array
    {
        $transactions = collect();

        foreach (collect(self::SERVICES)->except('rechargements') as $service => $table) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'wallet_id')) {
                continue;
            }

            $query = DB::table($table)
                ->whereIn($table.'.wallet_id', $walletIds)
                ->whereBetween($table.'.created_at', [$start, $end]);

            $receiverColumn = collect(self::RECEIVER_COLUMNS[$table] ?? [])
                ->first(fn (string $column) => Schema::hasColumn($table, $column));
            $receiverCurrency = "'—'";
            $receiverAccount = 'NULL';
            $recipientFirstname = 'NULL';
            $recipientLastname = 'NULL';

            if ($receiverColumn) {
                $query
                    ->leftJoin('wallets as receiver_wallets', function ($join) use ($table, $receiverColumn) {
                        $join->on($table.'.'.$receiverColumn, '=', 'receiver_wallets.wallet_id')
                            ->orOn($table.'.'.$receiverColumn, '=', 'receiver_wallets.id');
                    })
                    ->leftJoin('sid_users as recipients', function ($join) {
                        $join->on('receiver_wallets.sid_user_id', '=', 'recipients.id')
                            ->orOn('receiver_wallets.wallet_id', '=', 'recipients.wallet_id');
                    })
                    ->leftJoin('currencies as receiver_currencies', 'receiver_wallets.currencie_id', '=', 'receiver_currencies.id');
                $receiverCurrency = "COALESCE(receiver_currencies.code, receiver_currencies.name, '—')";
                $receiverAccount = $table.'.'.$receiverColumn;
                $recipientFirstname = 'recipients.firstname';
                $recipientLastname = 'recipients.lastname';
            } elseif (Schema::hasColumn($table, 'currencie_id')) {
                $query->leftJoin('currencies as receiver_currencies', $table.'.currencie_id', '=', 'receiver_currencies.id');
                $receiverCurrency = "COALESCE(receiver_currencies.code, receiver_currencies.name, '—')";
            }

            if (Schema::hasColumn($table, 'deleted_at')) {
                $query->whereNull($table.'.deleted_at');
            }

            $amount = Schema::hasColumn($table, 'amount') ? $table.'.amount' : '0';
            $amountReceived = Schema::hasColumn($table, 'amount_received')
                ? "COALESCE({$table}.amount_received, {$amount})"
                : $amount;
            $senderBalanceBefore = $this->tableColumnExpression($table, 'balance_before');
            $senderBalanceAfter = $this->tableColumnExpression($table, 'amount_solde');
            $receiverBalanceBefore = $this->tableColumnExpression($table, 'amount_solde_before_drawal');
            $receiverBalanceAfter = $this->tableColumnExpression($table, 'amount_solde_drawal');

            // wallet_transactions: Retrait PENDING ne stocke pas amount_solde (reste à 0 par défaut).
            // On expose NULL pour éviter les fausses anomalies "Solde Observé = 0".
            if ($table === 'wallet_transactions'
                && Schema::hasColumn($table, 'status')
                && Schema::hasColumn($table, 'amount_solde')) {
                $senderBalanceAfter = "CASE WHEN UPPER(TRIM({$table}.status)) = 'PENDING'
                    THEN NULL
                    ELSE {$table}.amount_solde END";
            }

            // Certains chemins de création Depot n'enregistrent pas balance_before (reste 0).
            // On expose NULL pour éviter les ruptures de continuité fictives.
            if ($table === 'wallet_transactions'
                && Schema::hasColumn($table, 'balance_before')
                && Schema::hasColumn($table, 'status')) {
                $senderBalanceBefore = "CASE WHEN {$table}.balance_before = 0
                    AND UPPER(TRIM({$table}.status)) = 'SUCCESS'
                    AND {$table}.amount_solde > 0
                    THEN NULL
                    ELSE {$table}.balance_before END";
            }

            $operationType = Schema::hasColumn($table, 'type_transaction')
                ? $table.'.type_transaction'
                : 'NULL';
            $receivedType = Schema::hasColumn($table, 'type_received')
                ? $table.'.type_received'
                : 'NULL';
            $operationReference = Schema::hasColumn($table, 'reference')
                ? $table.'.reference'
                : 'NULL';

            $rows = $query->select([
                $table.'.id',
                $table.'.wallet_id',
                $table.'.created_at',
                DB::raw($amount.' AS amount'),
                DB::raw($amountReceived.' AS amount_received'),
                DB::raw((Schema::hasColumn($table, 'status') ? $table.'.status' : "'SUCCESS'").' AS status'),
                DB::raw((Schema::hasColumn($table, 'description') ? $table.'.description' : 'NULL').' AS description'),
                DB::raw($receiverCurrency.' AS devise_receveur'),
                DB::raw($receiverAccount.' AS compte_receveur'),
                DB::raw($recipientFirstname.' AS recipient_firstname'),
                DB::raw($recipientLastname.' AS recipient_lastname'),
                DB::raw($senderBalanceBefore.' AS solde_superviseur_avant'),
                DB::raw($senderBalanceAfter.' AS solde_superviseur_apres'),
                DB::raw($receiverBalanceBefore.' AS solde_receveur_avant'),
                DB::raw($receiverBalanceAfter.' AS solde_receveur_apres'),
                DB::raw($operationType.' AS type_operation'),
                DB::raw($receivedType.' AS type_received'),
                DB::raw($operationReference.' AS operation_reference'),
            ])->get();

            foreach ($rows as $row) {
                $direction = $this->transactionAccountDirection(
                    $row->type_operation,
                    $row->solde_superviseur_avant,
                    $row->solde_superviseur_apres
                );
                $isCompensation = mb_strtolower(trim((string) $row->type_operation)) === 'compense';
                $transactions->push([
                    'id' => (int) $row->id,
                    'supervisor_wallet_id' => (int) $row->wallet_id,
                    'service' => $service,
                    'date' => $row->created_at,
                    'montant' => (float) $row->amount,
                    'montant_recu' => (float) $row->amount_received,
                    'statut' => $row->status,
                    'type_operation' => $row->type_operation,
                    'type_received' => $row->type_received,
                    'reference_operation' => $row->operation_reference ?: $service.' #'.$row->id,
                    'direction_compte' => $direction,
                    'description' => $row->description,
                    'devise_receveur' => $row->devise_receveur,
                    'beneficiaire' => trim(($row->recipient_firstname ?? '').' '.($row->recipient_lastname ?? ''))
                        ?: ($row->compte_receveur ?: 'Non identifié'),
                    'solde_superviseur_avant' => ! $isCompensation && $row->solde_superviseur_avant !== null
                        ? (float) $row->solde_superviseur_avant
                        : null,
                    'solde_superviseur_apres' => ! $isCompensation && $row->solde_superviseur_apres !== null
                        ? (float) $row->solde_superviseur_apres
                        : null,
                    'solde_receveur_avant' => $row->solde_receveur_avant !== null ? (float) $row->solde_receveur_avant : null,
                    'solde_receveur_apres' => $row->solde_receveur_apres !== null ? (float) $row->solde_receveur_apres : null,
                ]);
            }
        }

        return $transactions->sortByDesc('date')->values()->all();
    }

    private function actorValidatedTransactions(
        int $actorId,
        array $actorWalletIds,
        Carbon $start,
        Carbon $end
    ): array {
        if ($actorWalletIds === []) {
            return [];
        }

        $movements = collect();
        $actorWalletId = (int) $actorWalletIds[0];

        // Transfers::updateTransaction() modifie le wallet du validateur alors que
        // la transaction reste rattachée au wallet du demandeur.
        foreach (['transactions' => 'wallet_transactions'] as $service => $table) {
            if (
                ! Schema::hasTable($table)
                || ! Schema::hasColumn($table, 'updated_by')
                || ! Schema::hasColumn($table, 'updated_at')
            ) {
                continue;
            }

            $query = DB::table($table)
                ->where($table.'.updated_by', $actorId)
                ->whereBetween($table.'.updated_at', [$start, $end]);

            if (Schema::hasColumn($table, 'wallet_id')) {
                $query
                    ->leftJoin(
                        'wallets as validation_source_wallets',
                        $table.'.wallet_id',
                        '=',
                        'validation_source_wallets.id'
                    )
                    ->leftJoin(
                        'sid_users as validation_source_users',
                        'validation_source_wallets.sid_user_id',
                        '=',
                        'validation_source_users.id'
                    )
                    ->whereNotIn($table.'.wallet_id', $actorWalletIds)
                    ->select($table.'.*')
                    ->addSelect(DB::raw(
                        'COALESCE(validation_source_users.is_under_distributer, 0) '
                        .'AS source_is_under_distributor'
                    ));
            } else {
                $query->select($table.'.*');
            }

            if (Schema::hasColumn($table, 'status')) {
                $query->whereRaw("UPPER({$table}.status) = 'SUCCESS'");
            }

            if (Schema::hasColumn($table, 'deleted_at')) {
                $query->whereNull($table.'.deleted_at');
            }

            foreach ($query->get() as $row) {
                $operationType = $row->type_transaction
                    ?? $row->choose_transaction
                    ?? null;
                $normalizedType = mb_strtolower(trim((string) $operationType));
                $isWithdrawal = $normalizedType === 'retrait'
                    || (string) ($row->choose_transaction ?? '') === '2';
                $amountReceived = (float) ($row->amount_received ?? $row->amount ?? 0);
                $costsAmount = (float) ($row->costs_amount ?? 0);
                $sourceIsUnderDistributor = (bool) ($row->source_is_under_distributor ?? false);
                $walletVariation = $isWithdrawal
                    ? $amountReceived + ($sourceIsUnderDistributor ? 0.0 : $costsAmount)
                    : $amountReceived;

                if (abs($walletVariation) < self::AUDIT_TOLERANCE) {
                    continue;
                }

                $movements->push([
                    'id' => (int) $row->id,
                    'supervisor_wallet_id' => $actorWalletId,
                    'service' => $service.'_validation',
                    'date' => $row->updated_at,
                    'montant' => $walletVariation,
                    'montant_recu' => $walletVariation,
                    'statut' => $row->status ?? 'SUCCESS',
                    'type_operation' => $isWithdrawal
                        ? 'Validation retrait distributeur'
                        : 'Validation distributeur',
                    'type_received' => $operationType,
                    'reference_operation' => $service.' #'.$row->id,
                    'direction_compte' => $isWithdrawal ? 'sortie' : 'entree',
                    'description' => $row->description ?? null,
                    'devise_receveur' => '—',
                    'beneficiaire' => $isWithdrawal
                        ? 'Retrait validé par le distributeur'
                        : 'Transaction validée par le distributeur',
                    'expediteur' => 'Validation distributeur',
                    'solde_superviseur_avant' => null,
                    'solde_superviseur_apres' => null,
                    'solde_receveur_avant' => null,
                    'solde_receveur_apres' => null,
                ]);
            }
        }

        return $movements->sortByDesc('date')->values()->all();
    }

    private function transactionAccountDirection($operationType, $balanceBefore = null, $balanceAfter = null): string
    {
        $normalizedType = mb_strtolower(trim((string) $operationType));

        if ($normalizedType === 'compense') {
            return 'entree';
        }

        if ($balanceBefore !== null && $balanceAfter !== null) {
            $variation = round((float) $balanceAfter - (float) $balanceBefore, 2);

            if ($variation > 0) {
                return 'entree';
            }

            if ($variation < 0) {
                return 'sortie';
            }
        }

        return 'sortie';
    }

    private function supervisorReceivedTransactions(array $walletReferences, Carbon $start, Carbon $end): array
    {
        $transactions = collect();

        foreach (self::RECEIVER_COLUMNS as $table => $candidates) {
            if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'wallet_id')) {
                continue;
            }

            $receiverColumn = collect($candidates)
                ->first(fn (string $column) => Schema::hasColumn($table, $column));

            if (! $receiverColumn) {
                continue;
            }

            $query = DB::table($table)
                ->leftJoin('wallets as sender_wallets', $table.'.wallet_id', '=', 'sender_wallets.id')
                ->leftJoin('sid_users as senders', 'sender_wallets.sid_user_id', '=', 'senders.id')
                ->leftJoin('wallets as receiver_wallets', function ($join) use ($table, $receiverColumn) {
                    $join->on($table.'.'.$receiverColumn, '=', 'receiver_wallets.wallet_id')
                        ->orOn($table.'.'.$receiverColumn, '=', 'receiver_wallets.id');
                })
                ->leftJoin('currencies as receiver_currencies', 'receiver_wallets.currencie_id', '=', 'receiver_currencies.id')
                ->whereIn($table.'.'.$receiverColumn, $walletReferences)
                ->whereBetween($table.'.created_at', [$start, $end]);

            if (Schema::hasColumn($table, 'deleted_at')) {
                $query->whereNull($table.'.deleted_at');
            }

            $amount = Schema::hasColumn($table, 'amount') ? $table.'.amount' : '0';
            $amountReceived = Schema::hasColumn($table, 'amount_received')
                ? "COALESCE({$table}.amount_received, {$amount})"
                : $amount;
            $status = Schema::hasColumn($table, 'status') ? $table.'.status' : "'SUCCESS'";
            $description = Schema::hasColumn($table, 'description') ? $table.'.description' : 'NULL';
            $operationType = Schema::hasColumn($table, 'type_transaction')
                ? $table.'.type_transaction'
                : 'NULL';
            $receivedType = Schema::hasColumn($table, 'type_received')
                ? $table.'.type_received'
                : 'NULL';
            $operationReference = Schema::hasColumn($table, 'reference')
                ? $table.'.reference'
                : 'NULL';
            $receiverBalanceBefore = $this->tableColumnExpression($table, 'amount_solde_before_drawal');
            $receiverBalanceAfter = $this->tableColumnExpression($table, 'amount_solde_drawal');

            if (
                $table === 'wallet_transactions'
                && Schema::hasColumn($table, 'type_transaction')
                && Schema::hasColumn($table, 'amount_solde')
            ) {
                $isWithdrawal = "LOWER(TRIM({$table}.type_transaction)) = 'retrait'";
                $receiverBalanceBefore = "CASE WHEN {$isWithdrawal}
                    THEN {$table}.amount_solde - {$amountReceived}
                    ELSE {$receiverBalanceBefore} END";
                $receiverBalanceAfter = "CASE WHEN {$isWithdrawal}
                    THEN {$table}.amount_solde
                    ELSE {$receiverBalanceAfter} END";
            }

            $rows = $query->selectRaw(
                "{$table}.id,
                 receiver_wallets.id AS supervisor_wallet_id,
                 {$table}.created_at,
                 {$amount} AS amount,
                 {$amountReceived} AS amount_received,
                 {$receiverBalanceBefore} AS solde_receveur_avant,
                 {$receiverBalanceAfter} AS solde_receveur_apres,
                 {$status} AS status,
                 {$description} AS description,
                 {$operationType} AS type_operation,
                 {$receivedType} AS type_received,
                 {$operationReference} AS operation_reference,
                 senders.firstname,
                 senders.lastname,
                 COALESCE(receiver_currencies.code, receiver_currencies.name, '—') AS devise_receveur"
            )->get();

            foreach ($rows as $row) {
                $direction = $this->receivedTransactionDirection(
                    $row->solde_receveur_avant,
                    $row->solde_receveur_apres
                );
                $transactions->push([
                    'id' => (int) $row->id,
                    'supervisor_wallet_id' => $row->supervisor_wallet_id !== null ? (int) $row->supervisor_wallet_id : null,
                    'service' => array_search($table, self::SERVICES, true) ?: $table,
                    'date' => $row->created_at,
                    'montant' => (float) $row->amount,
                    'montant_recu' => (float) $row->amount_received,
                    'solde_receveur_avant' => $row->solde_receveur_avant !== null ? (float) $row->solde_receveur_avant : null,
                    'solde_receveur_apres' => $row->solde_receveur_apres !== null ? (float) $row->solde_receveur_apres : null,
                    'statut' => $row->status,
                    'type_operation' => $row->type_operation,
                    'type_received' => $row->type_received,
                    'direction_compte' => $direction,
                    'reference_operation' => $row->operation_reference
                        ?: (array_search($table, self::SERVICES, true) ?: $table).' #'.$row->id,
                    'description' => $row->description,
                    'expediteur' => trim(($row->firstname ?? '').' '.($row->lastname ?? '')) ?: 'Non identifié',
                    'devise_receveur' => $row->devise_receveur,
                ]);
            }
        }

        return $transactions->sortByDesc('date')->values()->all();
    }

    private function receivedTransactionDirection($balanceBefore, $balanceAfter): string
    {
        if ($balanceBefore !== null && $balanceAfter !== null) {
            $variation = round((float) $balanceAfter - (float) $balanceBefore, 2);

            if ($variation <= -self::AUDIT_TOLERANCE) {
                return 'sortie';
            }

            if ($variation >= self::AUDIT_TOLERANCE) {
                return 'entree';
            }
        }

        return 'entree';
    }

    private function reloadReceivedAmountExpression(): string
    {
        return Schema::hasColumn('wallet_reloads', 'amount_received')
            ? 'COALESCE(wallet_reloads.amount_received, wallet_reloads.amount)'
            : 'wallet_reloads.amount';
    }

    private function reloadBalanceExpression(string $column): string
    {
        return Schema::hasColumn('wallet_reloads', $column)
            ? 'wallet_reloads.'.$column
            : 'NULL';
    }

    private function tableColumnExpression(string $table, string $column): string
    {
        return Schema::hasColumn($table, $column)
            ? $table.'.'.$column
            : 'NULL';
    }

    private function serviceSummary(string $table, Carbon $start, Carbon $end): array
    {
        $query = DB::table($table)->whereBetween($table.'.created_at', [$start, $end]);

        if (Schema::hasColumn($table, 'deleted_at')) {
            $query->whereNull($table.'.deleted_at');
        }

        $amountExpression = Schema::hasColumn($table, 'amount') ? 'amount' : '0';
        $statusExpression = Schema::hasColumn($table, 'status') ? 'UPPER(status)' : "'SUCCESS'";

        $summary = $query->selectRaw(
            "COUNT(*) AS total,
             SUM(CASE WHEN {$statusExpression} = 'SUCCESS' THEN 1 ELSE 0 END) AS success,
             SUM(CASE WHEN {$statusExpression} = 'PENDING' THEN 1 ELSE 0 END) AS pending,
             SUM(CASE WHEN {$statusExpression} IN ('ERROR', 'FAILED') THEN 1 ELSE 0 END) AS failed,
             COALESCE(SUM(CASE WHEN {$statusExpression} = 'SUCCESS' THEN {$amountExpression} ELSE 0 END), 0) AS volume"
        )->first();

        return [
            'nombre' => (int) ($summary->total ?? 0),
            'reussies' => (int) ($summary->success ?? 0),
            'en_attente' => (int) ($summary->pending ?? 0),
            'echouees' => (int) ($summary->failed ?? 0),
            'volume_reussi' => (float) ($summary->volume ?? 0),
        ];
    }

    private function actorSummary(string $table, Carbon $start, Carbon $end): array
    {
        if (
            ! Schema::hasColumn($table, 'wallet_id')
            || ! Schema::hasTable('wallets')
            || ! Schema::hasTable('sid_users')
        ) {
            return [];
        }

        $amountExpression = Schema::hasColumn($table, 'amount') ? $table.'.amount' : '0';
        $query = DB::table($table)
            ->join('wallets', $table.'.wallet_id', '=', 'wallets.id')
            ->join('sid_users', 'wallets.sid_user_id', '=', 'sid_users.id')
            ->whereBetween($table.'.created_at', [$start, $end]);

        if (Schema::hasColumn($table, 'deleted_at')) {
            $query->whereNull($table.'.deleted_at');
        }

        if (Schema::hasColumn($table, 'status')) {
            $query->whereRaw('UPPER('.$table.".status) = 'SUCCESS'");
        }

        $isAdmin = $this->actorFlagExpression('is_admin');
        $isSupervisor = $this->actorFlagExpression('is_supervisor');
        $isMaster = $this->actorFlagExpression('is_master');
        $isCompany = $this->actorFlagExpression('is_company');
        $isUnderDistributor = $this->actorFlagExpression('is_under_distributer');
        $isCustomer = $this->actorFlagExpression('is_custumers');
        $isDistributor = $this->actorFlagExpression('is_distributer');

        return $query
            ->selectRaw(
                "CASE
                    WHEN {$isAdmin} THEN 'administrateurs'
                    WHEN {$isSupervisor} OR {$isMaster} THEN 'superviseurs'
                    WHEN {$isCompany} THEN 'compagnies'
                    WHEN {$isUnderDistributor} THEN 'sous_distributeurs'
                    WHEN {$isCustomer} THEN 'clients'
                    WHEN {$isDistributor} THEN 'distributeurs'
                    ELSE 'autres'
                 END AS actor_type,
                 COUNT(*) AS total,
                 COALESCE(SUM({$amountExpression}), 0) AS volume"
            )
            ->groupBy('actor_type')
            ->get()
            ->mapWithKeys(fn ($row) => [
                $row->actor_type => [
                    'nombre' => (int) $row->total,
                    'volume_reussi' => (float) $row->volume,
                ],
            ])
            ->all();
    }

    private function commissionSummary(Carbon $start, Carbon $end): array
    {
        if (! Schema::hasTable('admins_coms')) {
            return $this->emptyCommissionSummary();
        }

        $query = DB::table('admins_coms')->whereBetween('created_at', [$start, $end]);

        if (Schema::hasColumn('admins_coms', 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if (Schema::hasColumn('admins_coms', 'status')) {
            $query->whereRaw("UPPER(status) = 'SUCCESS'");
        }

        $admin = Schema::hasColumn('admins_coms', 'amount') ? 'amount' : '0';
        $company = Schema::hasColumn('admins_coms', 'amount_compagnie') ? 'amount_compagnie' : '0';
        $underDistributor = Schema::hasColumn('admins_coms', 'amount_under') ? 'amount_under' : '0';

        $summary = $query->selectRaw(
            "COUNT(*) AS transactions,
             COALESCE(SUM({$underDistributor}), 0) AS under_distributors,
             COALESCE(SUM({$company}), 0) AS companies,
             COALESCE(SUM({$admin}), 0) AS admins"
        )->first();

        $underDistributorTotal = (float) ($summary->under_distributors ?? 0);
        $companyTotal = (float) ($summary->companies ?? 0);
        $adminTotal = (float) ($summary->admins ?? 0);

        return [
            'transactions_commissionnees' => (int) ($summary->transactions ?? 0),
            'sous_distributeurs' => $underDistributorTotal,
            'compagnies' => $companyTotal,
            'admin' => $adminTotal,
            'total' => $underDistributorTotal + $companyTotal + $adminTotal,
        ];
    }

    private function emptyActorTotals(): array
    {
        return collect([
            'superviseurs',
            'clients',
            'compagnies',
            'sous_distributeurs',
            'distributeurs',
            'administrateurs',
            'autres',
        ])->mapWithKeys(fn (string $actor) => [
            $actor => ['nombre' => 0, 'volume_reussi' => 0.0],
        ])->all();
    }

    private function actorFlagExpression(string $column, string $alias = 'sid_users'): string
    {
        return Schema::hasColumn('sid_users', $column)
            ? $alias.'.'.$column.' = 1'
            : '0 = 1';
    }

    private function mergeActorTotals(array &$totals, array $items): void
    {
        foreach ($items as $actor => $item) {
            $totals[$actor] ??= ['nombre' => 0, 'volume_reussi' => 0.0];
            $totals[$actor]['nombre'] += $item['nombre'];
            $totals[$actor]['volume_reussi'] += $item['volume_reussi'];
        }
    }

    private function emptyServiceSummary(): array
    {
        return [
            'nombre' => 0,
            'reussies' => 0,
            'en_attente' => 0,
            'echouees' => 0,
            'volume_reussi' => 0.0,
        ];
    }

    private function addServiceSummary(array $left, array $right): array
    {
        foreach (array_keys($left) as $key) {
            $left[$key] += $right[$key];
        }

        return $left;
    }

    private function emptyCommissionSummary(): array
    {
        return [
            'transactions_commissionnees' => 0,
            'sous_distributeurs' => 0.0,
            'compagnies' => 0.0,
            'admin' => 0.0,
            'total' => 0.0,
        ];
    }
}
