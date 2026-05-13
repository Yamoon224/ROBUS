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

class ComZoneController extends Controller
{


    protected $userValidatorService;
    protected $createUserService;
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

    public function __construct(
        ValidatorService $userValidatorService,
        UserCreateService $createUserService
    ) {
        $this->userValidatorService = $userValidatorService;
        $this->createUserService = $createUserService;
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
        $walletIds = Wallet::whereIn(
            'sid_user_id',
            SidUser::where('com_zone_id', $comZone->id)->pluck('id')
        )->pluck('id');

        $periods = $this->transactionCounterPeriods();
        $totals = array_fill_keys(array_keys($periods), 0);
        $services = [];

        foreach ($this->transactionModels as $service => $model) {
            $serviceCounters = [];

            foreach ($periods as $period => $range) {
                $count = $this->countTransactionsByPeriod(
                    $model,
                    $walletIds,
                    $range['start'],
                    $range['end'],
                    $status
                );

                $serviceCounters[$period] = $count;
                $totals[$period] += $count;
            }

            $services[$service] = $serviceCounters;
        }

        return response()->json([
            'success' => true,
            'message' => 'Compteurs des transactions COM ZONE',
            'data' => [
                'com_zone_id' => $comZone->id,
                'status' => $status,
                'linked_users_count' => SidUser::where('com_zone_id', $comZone->id)->count(),
                'totals' => $totals,
                'services' => $services,
            ],
        ]);
    }

    protected function transactionCounterPeriods(): array
    {
        $now = Carbon::now();

        return [
            'daily' => [
                'start' => $now->copy()->startOfDay(),
                'end' => $now->copy()->endOfDay(),
            ],
            'weekly' => [
                'start' => $now->copy()->startOfWeek(),
                'end' => $now->copy()->endOfWeek(),
            ],
            'monthly' => [
                'start' => $now->copy()->startOfMonth(),
                'end' => $now->copy()->endOfMonth(),
            ],
            'quarterly' => [
                'start' => $now->copy()->startOfQuarter(),
                'end' => $now->copy()->endOfQuarter(),
            ],
            'yearly' => [
                'start' => $now->copy()->startOfYear(),
                'end' => $now->copy()->endOfYear(),
            ],
        ];
    }

    protected function countTransactionsByPeriod(string $model, $walletIds, Carbon $start, Carbon $end, ?string $status): int
    {
        $query = $model::whereIn('wallet_id', $walletIds)
            ->whereBetween('created_at', [$start, $end]);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->count();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store_under_distributors(Request $request)
    {

        $validator = $this->userValidatorService->validateUserData($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Champ est requis', 'data' => $validator->errors()
            ], 333);
        }

        try {

            $response = $this->createUserService->createUnderDistributor(
                $request->all(),
                $request->file('image'),
                $request->file('image_recto'),
                $request->file('image_verso')
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

        $validator = $this->userValidatorService->validateUserData($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Champ est requis', 'data' => $validator->errors()
            ], 333);
        }

        try {

            $response = $this->createUserService->createUnderMaster(
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

    public function store_under_custumers(Request $request)
    {

        $validator = $this->userValidatorService->validateUserData($request->all());

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Champ est requis', 'data' => $validator->errors()
            ], 333);
        }

        try {

            $response = $this->createUserService->createCustumer(
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
