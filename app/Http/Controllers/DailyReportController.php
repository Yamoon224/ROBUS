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

        return view('pages.reports.actor', [
            'actor' => $actor,
            'date' => $date->toDateString(),
            'users' => $actor === 'superviseurs'
                ? $this->supervisorActivityUsers($date->copy()->startOfDay(), $date->copy()->endOfDay())
                : $this->receivedReloadUsers($actor, $date->copy()->startOfDay(), $date->copy()->endOfDay()),
        ]);
    }

    public function downloadActorReport(Request $request, string $actor)
    {
        $this->authorizeReport();
        abort_unless(in_array($actor, ['superviseurs', 'sous_distributeurs'], true), 404);
        $date = $this->validatedDate($request);
        $users = $actor === 'superviseurs'
            ? $this->supervisorActivityUsers($date->copy()->startOfDay(), $date->copy()->endOfDay())
            : $this->receivedReloadUsers($actor, $date->copy()->startOfDay(), $date->copy()->endOfDay());

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
        $details = $this->actorUserDetails($actor, $user, $date->copy()->startOfDay(), $date->copy()->endOfDay());

        abort_unless($details, 404);

        return view('pages.reports.actor-user', [
            'actor' => $actor,
            'date' => $date->toDateString(),
            'details' => $details,
        ]);
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

    private function supervisorActivityUsers(Carbon $start, Carbon $end): array
    {
        if (! Schema::hasTable('sid_users')) {
            return [];
        }

        return DB::table('sid_users as supervisors')
            ->whereRaw($this->actorCondition('superviseurs', 'supervisors'))
            ->pluck('supervisors.id')
            ->map(fn ($id) => $this->supervisorDetails((int) $id, $start, $end))
            ->filter()
            ->map(function (array $details) {
                $summary = $details['resume'];
                $totalOperations = (int) (
                    $summary['rechargements_envoyes']
                    + $summary['rechargements_recus']
                    + $summary['transactions']
                    + $summary['transactions_recues']
                );
                $activityDates = collect([
                    ...$details['rechargements_envoyes'],
                    ...$details['rechargements_recus'],
                    ...$details['transactions'],
                    ...$details['transactions_recues'],
                ])->pluck('date')->filter();

                return [
                    'id' => $details['id'],
                    'nom' => $details['nom'],
                    'rechargements_envoyes' => (int) $summary['rechargements_envoyes'],
                    'montant_recharge' => (float) $summary['montant_recharge'],
                    'rechargements_recus' => (int) $summary['rechargements_recus'],
                    'montant_recu' => (float) $summary['montant_recu'],
                    'transactions' => (int) $summary['transactions'],
                    'transactions_recues' => (int) $summary['transactions_recues'],
                    'total_operations' => $totalOperations,
                    'derniere_activite' => $activityDates->max(),
                ];
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

    private function supervisorDetails(int $supervisorId, Carbon $start, Carbon $end): ?array
    {
        if (
            ! Schema::hasTable('wallet_reloads')
            || ! Schema::hasTable('wallets')
            || ! Schema::hasTable('sid_users')
        ) {
            return null;
        }

        $supervisor = DB::table('sid_users as supervisors')
            ->where('supervisors.id', $supervisorId)
            ->whereRaw($this->actorCondition('superviseurs', 'supervisors'))
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
            ->where(function ($query) use ($supervisor) {
                $query->where('sid_user_id', $supervisor->id);

                if ($supervisor->linked_wallet_id) {
                    $query->orWhere('wallet_id', $supervisor->linked_wallet_id);
                }
            })
            ->get(['id', 'wallet_id']);

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
            'nom' => trim($supervisor->firstname.' '.$supervisor->lastname),
            'wallet' => $wallets->pluck('wallet_id')->filter()->implode(', '),
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
        ];
    }

    private function supervisorSentReloads(array $walletIds, Carbon $start, Carbon $end): array
    {
        $receivedAmount = $this->reloadReceivedAmountExpression();
        $senderBalanceBefore = $this->reloadBalanceExpression('balance_before');
        $senderBalanceAfter = $this->reloadBalanceExpression('amount_solde');
        $receiverBalanceBefore = $this->reloadBalanceExpression('amount_solde_before_drawal');
        $receiverBalanceAfter = $this->reloadBalanceExpression('amount_solde_drawal');

        return DB::table('wallet_reloads')
            ->leftJoin('wallets as receiver_wallets', 'wallet_reloads.account_wallet', '=', 'receiver_wallets.wallet_id')
            ->leftJoin('sid_users as recipients', 'receiver_wallets.sid_user_id', '=', 'recipients.id')
            ->leftJoin('currencies as receiver_currencies', 'receiver_wallets.currencie_id', '=', 'receiver_currencies.id')
            ->whereIn('wallet_reloads.wallet_id', $walletIds)
            ->whereBetween('wallet_reloads.created_at', [$start, $end])
            ->when(Schema::hasColumn('wallet_reloads', 'deleted_at'), fn ($query) => $query->whereNull('wallet_reloads.deleted_at'))
            ->selectRaw(
                "wallet_reloads.id,
                 wallet_reloads.created_at,
                 wallet_reloads.status,
                 wallet_reloads.amount AS montant,
                 {$receivedAmount} AS montant_recu,
                 {$senderBalanceBefore} AS solde_expediteur_avant,
                 {$senderBalanceAfter} AS solde_expediteur_apres,
                 {$receiverBalanceBefore} AS solde_receveur_avant,
                 {$receiverBalanceAfter} AS solde_receveur_apres,
                 wallet_reloads.account_wallet,
                 recipients.firstname,
                 recipients.lastname,
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
                 {$receiverBalanceBefore} AS solde_receveur_avant,
                 {$receiverBalanceAfter} AS solde_receveur_apres,
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
                    ->leftJoin('wallets as receiver_wallets', $table.'.'.$receiverColumn, '=', 'receiver_wallets.wallet_id')
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

            $rows = $query->select([
                $table.'.id',
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
            ])->get();

            foreach ($rows as $row) {
                $transactions->push([
                    'id' => (int) $row->id,
                    'service' => $service,
                    'date' => $row->created_at,
                    'montant' => (float) $row->amount,
                    'montant_recu' => (float) $row->amount_received,
                    'statut' => $row->status,
                    'description' => $row->description,
                    'devise_receveur' => $row->devise_receveur,
                    'beneficiaire' => trim(($row->recipient_firstname ?? '').' '.($row->recipient_lastname ?? ''))
                        ?: ($row->compte_receveur ?: 'Non identifié'),
                    'solde_superviseur_avant' => $row->solde_superviseur_avant !== null ? (float) $row->solde_superviseur_avant : null,
                    'solde_superviseur_apres' => $row->solde_superviseur_apres !== null ? (float) $row->solde_superviseur_apres : null,
                    'solde_receveur_avant' => $row->solde_receveur_avant !== null ? (float) $row->solde_receveur_avant : null,
                    'solde_receveur_apres' => $row->solde_receveur_apres !== null ? (float) $row->solde_receveur_apres : null,
                ]);
            }
        }

        return $transactions->sortByDesc('date')->values()->all();
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
                ->leftJoin('wallets as receiver_wallets', $table.'.'.$receiverColumn, '=', 'receiver_wallets.wallet_id')
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

            $rows = $query->selectRaw(
                "{$table}.id,
                 {$table}.created_at,
                 {$amount} AS amount,
                 {$amountReceived} AS amount_received,
                 {$status} AS status,
                 {$description} AS description,
                 senders.firstname,
                 senders.lastname,
                 COALESCE(receiver_currencies.code, receiver_currencies.name, '—') AS devise_receveur"
            )->get();

            foreach ($rows as $row) {
                $transactions->push([
                    'id' => (int) $row->id,
                    'service' => array_search($table, self::SERVICES, true) ?: $table,
                    'date' => $row->created_at,
                    'montant' => (float) $row->amount,
                    'montant_recu' => (float) $row->amount_received,
                    'statut' => $row->status,
                    'description' => $row->description,
                    'expediteur' => trim(($row->firstname ?? '').' '.($row->lastname ?? '')) ?: 'Non identifié',
                    'devise_receveur' => $row->devise_receveur,
                ]);
            }
        }

        return $transactions->sortByDesc('date')->values()->all();
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
