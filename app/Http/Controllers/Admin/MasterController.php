<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Tools\Image;
use App\Models\Sid\SidUser;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use App\Models\Custumers\Country;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Admin\MailController;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletBank;
use App\Models\Banks\BankCard;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletReload;
use App\Http\Services\TransactionsAccountService;

class MasterController extends Controller
{

    protected $transactionAccountService;

    public function __construct(
        TransactionsAccountService $transactionAccountService,
    ) {
        $this->transactionAccountService = $transactionAccountService;
    }

    public function browse_underdistributors()
    {

        if (master()) {
            return view('masters.pages.browse_underdistributors');
        } else {
            abort(403);
        }
    }

    public function browse_categories()
    {
        if (master()) {
            return view('masters.pages.browse_categories');
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show__underdistributors(Request $request, int $id)
    {
        if (master()) {


            $user = SidUser::whereId($id)->first();

            $user_wallet =  $user->wallet->id;

            $master_wallet =  master()->wallet->id;

            // commissions transactions
            $transaction_com = $this->transactionAccountService->walletSumCommission(WalletTransaction::class, $user_wallet);

            $paiement_com = $this->transactionAccountService->walletSumCommission(WalletPayment::class, $user_wallet);

            $virement_com = $this->transactionAccountService->walletSumCommission(WalletBank::class, $user_wallet);

            $phoneCredit_com = $this->transactionAccountService->walletSumCommission(WalletPhoneCredit::class, $user_wallet);

            $reloadCard_com = $this->transactionAccountService->walletSumCommission(WalletDeposit::class, $user_wallet);

            $International_com = $this->transactionAccountService->walletSumCommission(WalletInter::class, $user_wallet);

            $walletOrderCarte_com = $this->transactionAccountService->walletSumCommission(BankCard::class, $user_wallet);

            // amounts valeurs transactions
            $transactionAmounts = $this->transactionAccountService->getTransactionAmount(WalletTransaction::class, $user_wallet);

            $paiementAmounts = $this->transactionAccountService->getTransactionAmount(WalletPayment::class, $user_wallet);

            $virementAmounts = $this->transactionAccountService->getTransactionAmount(WalletBank::class, $user_wallet);

            $phoneCreditAmounts = $this->transactionAccountService->getTransactionAmount(WalletPhoneCredit::class, $user_wallet);

            $reloadCardAmounts = $this->transactionAccountService->getTransactionAmount(WalletDeposit::class, $user_wallet);

            $InternationalAmounts = $this->transactionAccountService->getTransactionAmount(WalletInter::class, $user_wallet);

            $walletOrderCarteAmounts = $this->transactionAccountService->getTransactionAmount(BankCard::class, $user_wallet);

            // sum commissions masters

            $sum_wallet_transaction = $transaction_com + $paiement_com + $virement_com  + $phoneCredit_com + $reloadCard_com + $International_com + $walletOrderCarte_com;

            // All types transactions listes
            $transactions = $this->transactionAccountService->getWalletTransactions(WalletTransaction::class, $user_wallet);

            $paiements = $this->transactionAccountService->getWalletTransactions(WalletPayment::class, $user_wallet);

            $virements = $this->transactionAccountService->getWalletTransactions(WalletBank::class, $user_wallet);

            $phoneCredits = $this->transactionAccountService->getWalletTransactions(WalletPhoneCredit::class, $user_wallet);

            $realoadUvs = $this->transactionAccountService->getWalletTransactions(WalletReload::class, $master_wallet);

            $reloadCards = $this->transactionAccountService->getWalletTransactions(WalletDeposit::class, $user_wallet);

            $internationals = $this->transactionAccountService->getWalletTransactions(WalletInter::class, $user_wallet);

            $walletOrderCarte = $this->transactionAccountService->getWalletTransactions(BankCard::class, $user_wallet);

            // Utilisation de la fonction pour les transactions de compte
            $accountTransactionCounts = $this->getTransactionCounts(WalletTransaction::class, $user_wallet);
            // Utilisation de la fonction pour les virements de compte bancaire
            $accountVirementCounts = $this->getTransactionCounts(WalletBank::class, $user_wallet);
            $accountPaymentCounts = $this->getTransactionCounts(WalletPayment::class, $user_wallet);
            $accountBankCounts = $this->getTransactionCounts(BankCard::class, $user_wallet);
            $accountInterCounts = $this->getTransactionCounts(WalletInter::class, $user_wallet);
            $accountDepositCounts = $this->getTransactionCounts(WalletDeposit::class, $user_wallet);
            $accountPhoneCreditCounts = $this->getTransactionCounts(WalletPhoneCredit::class, $user_wallet);

            // Accéder aux compteurs spécifiques
            $accountTransactionPendings = $accountTransactionCounts['PENDING'];
            $accountTransactionSuccess = $accountTransactionCounts['SUCCESS'];
            $accountTransactionErrors = $accountTransactionCounts['ERROR'];

            $accountVirementPendings = $accountVirementCounts['PENDING'];
            $accountVirementSuccess = $accountVirementCounts['SUCCESS'];
            $accountVirementErrors = $accountVirementCounts['ERROR'];

            $accountPaymentPendings = $accountPaymentCounts['PENDING'];
            $accountPaymentSuccess = $accountPaymentCounts['SUCCESS'];
            $accountPaymentErrors = $accountPaymentCounts['ERROR'];

            $accountBankPendings = $accountBankCounts['PENDING'];
            $accountBankSuccess = $accountBankCounts['SUCCESS'];
            $accountBankErrors = $accountBankCounts['ERROR'];

            $accountInterPendings = $accountInterCounts['PENDING'];
            $accountInterSuccess = $accountInterCounts['SUCCESS'];
            $accountInterErrors = $accountInterCounts['ERROR'];

            $accountDepositPendings = $accountDepositCounts['PENDING'];
            $accountDepositSuccess = $accountDepositCounts['SUCCESS'];
            $accountDepositErrors = $accountDepositCounts['ERROR'];

            $accountPhonePendings = $accountPhoneCreditCounts['PENDING'];
            $accountPhoneSuccess = $accountPhoneCreditCounts['SUCCESS'];
            $accountPhoneErrors = $accountPhoneCreditCounts['ERROR'];


            return view('masters.pages.show_underdistributors', compact(
                'user',
                'transaction_com',
                'paiement_com',
                'virement_com',
                'phoneCredit_com',
                'reloadCard_com',
                'International_com',
                'walletOrderCarte_com',
                'transactionAmounts',
                'paiementAmounts',
                'virementAmounts',
                'phoneCreditAmounts',
                'reloadCardAmounts',
                'InternationalAmounts',
                'walletOrderCarteAmounts',
                'sum_wallet_transaction',
                'transactions',
                'paiements',
                'virements',
                'phoneCredits',
                'realoadUvs',
                'reloadCards',
                'internationals',
                'walletOrderCarte',
                'accountTransactionPendings',
                'accountTransactionSuccess',
                'accountTransactionErrors',
                'accountVirementPendings',
                'accountVirementSuccess',
                'accountVirementErrors',
                'accountPaymentPendings',
                'accountPaymentSuccess',
                'accountPaymentErrors',
                'accountBankPendings',
                'accountBankSuccess',
                'accountBankErrors',
                'accountInterPendings',
                'accountInterSuccess',
                'accountInterErrors',
                'accountDepositPendings',
                'accountDepositSuccess',
                'accountDepositErrors',
                'accountPhonePendings',
                'accountPhoneSuccess',
                'accountPhoneErrors',
            ));
        } else {
            abort(403);
        }
    }


    private function getTransactionCounts($modelClass, $userWallet)
    {
        $statuses = ['PENDING', 'SUCCESS', 'ERROR'];

        $counts = [];

        foreach ($statuses as $status) {
            $counts[$status] = $this->transactionAccountService->getTransactionCount($modelClass, $userWallet, $status);
        }

        return $counts;
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect('account')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
        try {

            $tool = new Image;

            DB::beginTransaction();
            $user = SidUser::find(Auth::user()->id);
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->phone = $request->phone;
            $user->country_id = $request->country_id ?? '157';
            if ($request->image) {
                $tool = new Image;
                $user->image = $tool->saveInDisk($request, 'admins/' . Auth::user()->company_id . '/admin');
            }
            $user->save();

            DB::commit();
            return redirect(route('account.profile'))->with('status', 'Mise a jour effectuée avec succès');
        } catch (\Exception $exception) {
            return redirect('account')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
    }


    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        if ($validator->fails()) {
            return redirect('account/security')
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }

        SidUser::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);


        // $notify = new MailController;
        // $notify->modifyPassword($request);

        return redirect('account/security')
            ->with('status', 'Mise a jour effectuée avec succès');
    }
}
