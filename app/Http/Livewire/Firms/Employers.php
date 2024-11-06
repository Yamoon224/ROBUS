<?php

namespace App\Http\Livewire\Firms;

use App\Events\AdminNotifEvent;
use App\Events\CreateWalletTransfersEvent;
use App\Http\Controllers\Admin\MailController;
use App\Models\AdminsCom;
use App\Models\Categorie;
use App\Models\Company;
use App\Models\Costs\TransactionCost;
use App\Models\Custumers\Country;
use App\Models\Invoices\InvoiceTransaction;
use App\Models\Payments\Transaction;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class Employers extends Component
{

    use WithPagination;

    public $search = "";
    public $countriesFilter = "";
    public $statusFilter_piece = "";
    public $statusFilter_active = "";
    public $step = 1;
    public $wallet_id;
    public $user_id;
    public $categorieId;
    public $employerDesabled;
    public $amount_payment;
    public $choose_type_payement_emp;
    public $wallet_id_employer;
    public $amount_payment_wallet;
    public $userdetails;
    public $userLocation;
    public $userPhone;
    public $wallet_id_add;
    public $categorieId_add;
    public $transaction_description_robus;
    public $isPasswordValid = false;
    public $showPasswordField = 1;
    public $commission_transaction_val_admin_receive;
    public $commission_transaction_val_nat_receive;
    public $amount_transaction_nat_val_pay;
    public $amount_transaction_nat_val_percent;
    public $password_validated;

    public function updatedAmountPayment()
    {
        $this->calculateAmounts();
    }


    public function calculateAmounts()
    {

        $this->commission_transaction_val_nat_receive = 0;
        $this->commission_transaction_val_admin_receive = 0;
        $this->amount_transaction_nat_val_pay = 0;
        $this->amount_transaction_nat_val_percent = 0;


        if ($this->amount_payment) {

            $reel_amount = $this->search_amount($this->amount_payment);

            if ($reel_amount != '') {

                $commission_transaction = $this->getRobusPercentageCompanies();
                $this->commission_transaction_val_admin_receive = (float) $commission_transaction->percent->percent * $reel_amount;
                $this->commission_transaction_val_nat_receive = (float) $reel_amount - $this->commission_transaction_val_admin_receive;
                $this->amount_transaction_nat_val_pay = (float) $this->commission_transaction_val_admin_receive * $commission_transaction->percent->percent_deposit;
                $this->amount_transaction_nat_val_percent = (float) $reel_amount - $this->commission_transaction_val_admin_receive;
            }
        }
    }

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }

    public function add_employers()
    {

        $this->validate([
            'wallet_id_add' => 'required',
            'categorieId_add' => 'required',
        ], [
            'wallet_id_add.required' => 'Votre wallet est obligatoire',
            'categorieId_add.required' => 'la categorie est obligatoire',

        ]);

        $check = Wallet::where('wallet_id', $this->wallet_id_add)->first();

        if ($check) {


            if ($check->sidUser->id == firm()->id || $check->sidUser->is_company == true) {

                $this->emit('modal-hide', 'hide');
                $this->cancelEmployesField();
                $this->emit('info', 'Desolé Ce wallet est pour vous !!!');
            } else {

                // Update Users
                $check->sidUser->update(
                    [
                        'company_id' => firm()->company->id,
                        'categorie_id'  => $this->categorieId_add,
                    ]
                );

                $this->emit('modal-hide', 'hide');
                $this->cancelEmployesField();
                $this->emit('success', 'Employés ajouté !');
            }
        } else {
            $this->emit('modal-hide', 'hide');
            $this->cancelEmployesField();
            $this->emit('error', 'Numero Inconnu chez ROBUS !');
        }
    }

    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletTransaction::join(
                'wallets',
                'wallet_transactions.wallet_id',
                'wallets.id'
            )
                ->where(
                    'wallets.country_id',
                    firm()->country->id
                )
                ->where(
                    'wallet_transactions.created_by',
                    firm()->id
                )
                ->whereDate('wallet_transactions.created_at', Carbon::today())
                ->select('wallet_transactions.*')
                ->where('wallet_transactions.status', 'SUCCESS')
                ->get()
            as $transaction
        ) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }


    public function checkPassword()
    {

        if (empty($this->password_validated)) {
            return;
        }

        if (Hash::check($this->password_validated, firm()->password)) {
            $this->isPasswordValid = true;
        } else {
            $this->isPasswordValid = false;
        }
    }

    public function addTransaction()
    {
        // $som_transfers =  $this->getSumValTransactionProperty();
        // $valeur_ceiling = sabdy()->silver_ceiling;
        $check = Wallet::where('wallet_id', $this->wallet_id)->first();

        try {


            if (!$check) {



                if ($this->choose_type_payement_emp == 1) {

                    $this->validate([
                        'amount_payment' => 'required',
                        'categorieId' => 'required',
                        'transaction_description_robus' => 'required',
                    ], [
                        'amount_payment.required' => 'Votre montant est obligatoire',
                        'categorieId.required' => 'la categorie est obligatoire',
                        'transaction_description_robus.required' => 'la description est obligatoire',
                    ]);

                    $this->amount_payment = htmlspecialchars($this->amount_payment, ENT_QUOTES, 'UTF-8');

                    $this->step = 2;
                } elseif ($this->choose_type_payement_emp == 2) {

                    $this->validate([
                        'amount_payment' => 'required',
                        'wallet_id_employer' => 'required',
                    ], [
                        'amount_payment.required' => 'Votre montant est obligatoire',
                        'wallet_id_employer.required' => 'le wallet est obligatoire',

                    ]);

                    $this->amount_payment = htmlspecialchars($this->amount_payment, ENT_QUOTES, 'UTF-8');

                    $this->step = 2;
                } else {
                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('info', 'Ce Provider n\'existe pas dans chez vous !');
                }


                if (!$this->validateAmount()) {
                    return;
                }

            } else {
                $this->cancelEmployesField();
                $this->emit('modal-hide');
                $this->emit('error', 'Erreur de choix !');
            }
        } catch (\Exception $exception) {
            $this->cancelEmployesField();
            $this->emit('modal-hide');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function validatedPassword()
    {


        $check = Wallet::where('wallet_id', $this->wallet_id)->first();
        $som_transfers =  $this->getWalletTransactionsCeilingProperty();
        $valeur_ceiling = firm()->silver_ceiling;
        $walletUser = firm();
        $admin_civ = $this->getAdminCIVProperty();
        $companyId = firm()->company->id;
        $currentUserId = firm()->id;

        $this->validate(
            [
                'password_validated' => 'required',
            ],
            [
                'password_validated.required' =>
                'Mot de passe est obligatoire',
            ]
        );

        $percentRobus = $this->getRobusPercentageCompanies();

        $reel_amount =  $this->search_amount($this->amount_payment);

        if (Hash::check($this->password_validated, firm()->password)) {

            if ($this->choose_type_payement_emp == 1) {


                $this->validate([
                    'amount_payment' => 'required',
                    'categorieId' => 'required',
                    'transaction_description_robus' => 'required',
                ], [
                    'amount_payment.required' => 'Votre montant est obligatoire',
                    'categorieId.required' => 'la categorie est obligatoire',
                    'transaction_description_robus.required' => 'la description est obligatoire',
                ]);

                $accountEmployers =  SidUser::where('is_custumers', true)
                    ->where('company_id', $companyId)
                    ->where('id', '!=', $currentUserId)
                    ->where('categorie_id', $this->categorieId)->count();

                if ($accountEmployers === 0) {
                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('warning', 'Vous n\'avez pas de catégorie disponible !!');
                    return;
                }

                $amount_payment_alls = (float)$accountEmployers  * (float) $reel_amount;

                if ($som_transfers >= $valeur_ceiling) {
                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                    return;
                }

                if ($amount_payment_alls > $walletUser->wallet->amount) {
                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('success', 'Verifier votre montant ! !');
                    return;
                }

                $amountEmployers =  SidUser::where('is_custumers', true)
                    ->where('company_id', $companyId)
                    ->where('id', '!=', $currentUserId)
                    ->where('categorie_id', $this->categorieId)->get();

                $amountSolde =  $walletUser->wallet->amount -  $amount_payment_alls;

                $amountReceived = $reel_amount - ($percentRobus->percent->percent * $reel_amount);

                $this->updateWalletFirmAmount($walletUser, $amount_payment_alls);

                if ($amountEmployers->isNotEmpty()) {

                    foreach ($amountEmployers  as  $key => $wallet_user) {

                        $this->updateWalletUserEmployer($wallet_user, $amountReceived, $reel_amount, $amountSolde, $percentRobus, $accountEmployers);
                    }

                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('success', 'Votre transfert a été effectué avec success !');
                } else {
                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('error', 'Vous n\'avez pas d\'employer a payer !');
                }
            } elseif ($this->choose_type_payement_emp == 2) {

                $this->validate([
                    'amount_payment' => 'required',
                    'wallet_id_employer' => 'required',
                ], [
                    'amount_payment.required' => 'Votre montant est obligatoire',
                    'wallet_id_employer.required' => 'le wallet est obligatoire',

                ]);

                $wallet_search  =  wallet::where('wallet_id', $this->wallet_id_employer)->first();

                if ($wallet_search == null && $wallet_search->sidUser->company_id != firm()->company->id) {

                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('info', 'En tant qu\'employeur, ce numéro n\'est pas présent chez vous !!!');
                    return;
                }

                if ($som_transfers >= $valeur_ceiling) {

                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('warning', 'Votre montant journier est atteint !');
                    return;
                }

                if ($reel_amount > $walletUser->wallet->amount) {
                    $this->cancelEmployesField();
                    $this->emit('modal-hide-transfers');
                    $this->emit('success', 'Verifier votre montant ! !');
                }

                $amountSolde =  $walletUser->wallet->amount -  $reel_amount;

                $walletAmountSend = Wallet::where('wallet_id',  $this->wallet_id_employer)->first();

                $valuer_receive = $reel_amount - ($percentRobus->percent->percent * $reel_amount);

                $amountDrawal = (float)$walletAmountSend->amount +  (float) $valuer_receive;


                $walletTransaction = $this->createWalletTransaction2($reel_amount, $this->transaction_description_robus, $percentRobus, $this->wallet_id_employer, $this->choose_type_payement_emp, $amountSolde, $amountDrawal);


                if ($walletTransaction != null) {

                    $this->updateWalletFirmAmount($walletUser, $reel_amount);

                    $this->updateWalletRecevedAmount($wallet_search, $valuer_receive);
                    $this->sendSmsNotification($walletUser, $walletTransaction);
                    $this->companyCommissionToAdmins($reel_amount, $percentRobus, $walletTransaction, $admin_civ);
                    $this->generateInvoiceTransaction($walletTransaction, $valuer_receive);
                    $this->sendNotifications($walletTransaction, $walletUser);

                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('success', 'Votre transfert a été effectué avec success !');
                } else {

                    $this->cancelEmployesField();
                    $this->emit('modal-hide');
                    $this->emit('info', 'Ce Provider n\'existe pas dans chez vous !');
                }
            } else {
                $this->cancelEmployesField();
                $this->emit('modal-hide');
                $this->emit('info', 'Ce Provider n\'existe pas dans chez vous !');
            }
        } else {
            $this->cancelEmployesField();
            $this->emit('modal-hide');
            $this->emit('info', 'Votre mot de passe est incorrect !');
        }
    }

    private function updateWalletUserEmployer($walletUserEmployer, $amountReceived, $amountPayment, $amountSolde, $percentRobus, $account_employers)
    {
        $walletAmountSend = Wallet::where('wallet_id', $walletUserEmployer->wallet->wallet_id)->first();
        $commission = $amountPayment;
        $amountReceivedPerUser = $commission / $account_employers;
        $finalAmountReceived = $amountReceivedPerUser - ($percentRobus->percent->percent * $amountReceivedPerUser);
        $amountSoldeDrawal = (float)$walletAmountSend->amount + (float)$finalAmountReceived;
        $amountDrawal = $amountSoldeDrawal;
        $convertedAmountReceived = $this->convertAdminCommission($amountReceived, $walletAmountSend);
        $adminCiv = $this->getAdminValeurProperty();
        $walletTransaction = $this->createWalletTransaction($walletUserEmployer, $convertedAmountReceived, $percentRobus, $amountSolde, $amountDrawal);

        if ($walletTransaction != null) {

            $this->updateWalletAmount($walletUserEmployer, $convertedAmountReceived);
            $this->sendSmsNotification($walletUserEmployer, $walletTransaction);
            $this->companyCommissionToAdmins($commission, $percentRobus, $walletTransaction, $adminCiv);
            $this->generateInvoiceTransaction($walletTransaction, $finalAmountReceived);
            $this->sendNotifications($walletTransaction, $walletUserEmployer);
        } else {

            $this->cancelEmployesField();
            $this->emit('modal-hide');
            $this->emit('info', 'Ce Provider n\'existe pas dans chez vous !');
        }
    }

    private function createWalletTransaction($walletUserEmployer, $convertedAmountReceived, $percentRobus, $amountSolde, $amountDrawal)
    {

        return WalletTransaction::create([
            'wallet_id' => firm()->wallet->id,
            'amount' => $this->amount_payment,
            'description' => $this->transaction_description_robus ?? null,
            // 'transaction_cost_id' => $percentRobus->percent->id,
            'phone' => $this->transaction_phone ?? null,
            'costs_amount' => (float)$percentRobus->percent->percent * (float)$this->amount_payment,
            'amount_received' => $convertedAmountReceived,
            'num_wallet' => $walletUserEmployer->wallet->wallet_id,
            'created_by' => firm()->id,
            'provider_id' => 9,
            'choose_type_payement_emp' => $this->choose_type_payement_emp,
            'type_transaction' => 'Depot',
            'type_received' => 'Transfert',
            'status' => 'SUCCESS',
            'amount_solde' => $amountSolde,
            'amount_solde_drawal' => $amountDrawal
        ]);
    }

    private function createWalletTransaction2($amount_payment, $transaction_description_robus, $percentRobus, $wallet_id_employer, $choose_type_payement_emp, $amountSolde, $amountDrawal)
    {

        return WalletTransaction::create([
            'wallet_id' => firm()->wallet->id,
            'amount' => $amount_payment,
            'description' => $transaction_description_robus ?? null,
            // 'transaction_cost_id' => $percentRobus->percent->id,
            // 'phone' => $transaction_phone ?? null,
            'costs_amount' => (float)$percentRobus->percent->percent * (float)$amount_payment,
            'amount_received' => $amount_payment - ($percentRobus->percent->percent * $amount_payment),
            'num_wallet' => $wallet_id_employer,
            'created_by' => firm()->id,
            'provider_id' => 9,
            'choose_type_payement_emp' => $choose_type_payement_emp,
            'type_transaction' => 'Depot',
            'type_received' => 'Transfert',
            'status' => 'SUCCESS',
            'amount_solde' => $amountSolde,
            'amount_solde_drawal' => $amountDrawal
        ]);
    }

    private function updateWalletAmount($walletUserEmployer, $finalAmountReceived)
    {
        $walletUserEmployer->wallet->update([
            'reload_by' => firm()->firstname . ' ' . firm()->lastname,
            'sid_user_relod' => firm()->id,
            'amount' => DB::raw('amount+' . $finalAmountReceived),
        ]);
    }

    private function updateWalletRecevedAmount($walletUserEmployer, $finalAmountReceived)
    {
        $walletUserEmployer->update([
            'reload_by' => firm()->firstname . ' ' . firm()->lastname,
            'sid_user_relod' => firm()->id,
            'amount' => DB::raw('amount+' . $finalAmountReceived),
        ]);
    }


    private function updateWalletFirmAmount($walletUserEmployer, $amount_payment_alls)
    {
        $walletUserEmployer->wallet->update([
            'reload_by' => firm()->firstname . ' ' . firm()->lastname,
            'sid_user_relod' => firm()->id,
            'amount' => DB::raw('amount-' . $amount_payment_alls),
        ]);
    }

    private function sendSmsNotification($walletUserEmployer, $walletTransaction)
    {
        $firm = firm();
        if ($firm->country->code === 'CI') {
            $message = 'Vous avez reçu un transfert de ' . $walletTransaction->amount .
                $firm->wallet->currencie->code . ' de ' . ($firm->company->name ?? 'Robus') .
                ' par ' . $walletTransaction->provider->name .
                ' - Numero Transaction ' . $walletUserEmployer->phone .
                ' à ' . $walletTransaction->created_at . ' - Robus vous remercie';

            // $encodedPhone = substr($walletUserEmployer->phone, -10);
            // Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encodedPhone&from=ROBUS&sms=$message");
        }
    }

    private function distributeCommissionToAdmins($walletTransaction, $commissionValue)
    {
        $adminCiv = $this->getAdminValeurProperty();

        if ($adminCiv->count() > 0) {
            $singleCommissionValue = $commissionValue / $adminCiv->count();
            foreach ($adminCiv as $admin) {
                AdminsCom::create([
                    'amount' => $singleCommissionValue,
                    'sid_user_id' => $admin->id,
                    'wallet_transaction_id' => $walletTransaction->id,
                    'description' => 'Commission société',
                ]);
                $admin->wallet->update([
                    'reload_by' => firm()->firstname . ' ' . firm()->lastname,
                    'sid_user_relod' => firm()->id,
                    'amount' => DB::raw('amount+' . $singleCommissionValue),
                ]);
            }
        }
    }

    public function companyCommissionToAdmins($transaction_amount_robus, $percent_robus, $walletTransaction, $admin_civ)
    {

        $country_civ = Country::find(107);
        // Gestion des commissions d'intermédiaires

        $valeur_percent_admin = $percent_robus->percent->percent * $transaction_amount_robus;
        $valeur_admin = $percent_robus->percent->percent_admin * $valeur_percent_admin;
        $valuer_deposit = $percent_robus->percent->percent_deposit * $valeur_percent_admin;
        $valuer_with_drawal = $percent_robus->percent->percent_with_drawal * $valeur_percent_admin;

        if ($country_civ->currencie->value > firm()->country->currencie->value) {
            $admin_valeur_convertion_drawal = $valuer_with_drawal * (float)firm()->country->currencie->value;
            $admin_valeur_convertion = (float)$valeur_admin * (float)firm()->country->currencie->value;
        } elseif ($country_civ->currencie->value == firm()->country->currencie->value) {
            $admin_valeur_convertion_drawal = $valuer_with_drawal;
            $admin_valeur_convertion = (float)$valeur_admin;
        } else {
            $admin_valeur_convertion_drawal = $valuer_with_drawal * (float)firm()->country->currencie->value;
            $admin_valeur_convertion = (float)$valeur_admin * (float)firm()->country->currencie->value;
        }

        $walletTransaction->increment('commission_companie', $valuer_deposit);

        if ($admin_civ->count() > 0) {
            $nmbre_admin = $admin_civ->count();
            $valeur_single = ($admin_valeur_convertion + $admin_valeur_convertion_drawal) / $nmbre_admin;

            foreach ($admin_civ as $user) {
                $this->processAdminCommissionSDistributer($user->id, $walletTransaction->id, $valeur_single, $valuer_deposit);
            }
        }

        return true;
    }

    private function processAdminCommissionSDistributer($userId, $walletTransactionId, $amount, $valuer_deposit)
    {
        $admin_single = SidUser::find($userId);

        AdminsCom::create([
            'amount' => $amount,
            'sid_user_id' => $admin_single->id,
            'wallet_transaction_id' => $walletTransactionId,
            'description' => 'Commission administrateur',
            'status'  => "SUCCESS",
            'amount_under' =>  firm()->is_under_distributer ? $valuer_deposit : 0,
            'description_underdistributer' =>  firm()->is_under_distributer ? 'Paiement commission sous-distributer' : null
        ]);

        $admin_single->wallet->increment('amount', $amount);
    }



    private function generateInvoiceTransaction($walletTransaction, $amountReceived)
    {

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));

        $transaction = Transaction::create([
            'wallet_transaction_id' => $walletTransaction->id,
            'sid_user_id' => firm()->id,
            'transaction_id' => 'RB_' . $walletTransaction->provider->name . '_' . $transactionCode,
            'operator_transaction_name' => $walletTransaction->provider->name,
            'trans_comp_id' => 'RB_' . $transactionCompiCode,
            'pay_amount' => $amountReceived,
            'fee_amount' => $walletTransaction->costs_amount,
            'received_amount' => $amountReceived,
            'status' => $walletTransaction->status,
        ]);

        InvoiceTransaction::create([
            'title' => "Transfert d'argent",
            'wallet_transaction_id' => $walletTransaction->id,
            'transactions_id' => $transaction->id,
            'company_id' => firm()->company->id,
            'description' => $walletTransaction->description,
            'status' => $walletTransaction->status,
            'created_by' => firm()->id,
        ]);
    }

    private function sendNotifications($walletTransaction, $walletUserEmployer)
    {
        $msgTitle = 'Transactions';
        $msg = 'Transfert validé';
        $walletUserEmployer->notify(new UserNotification(['title' => $msgTitle, 'message' => $msg]));
        $walletTransaction->wallet->sidUser->notify(new UserNotification(['title' => $msgTitle, 'message' => $msg]));
        $admins = $this->getAdminValeurProperty();
        event(new CreateWalletTransfersEvent($walletTransaction));
        Notification::send($admins, new AdminNotification('Transfert de ' . $walletTransaction->wallet->sidUser->firstname));
        if ($walletUserEmployer->email != null) {
            $notify = new MailController();
            $notify->newTransaction($walletTransaction);
        }
    }
    private function validateAmount()
    {

        $val_amount = $this->amount_payment;

        if ($val_amount > firm()->wallet->amount) {
            $this->cancelEmployesField();
            $this->emit('modal-hide');
            $this->emit('error', 'Vérifiez votre solde !');
            return false;
        }

        return true;
    }

    private function convertAdminCommissionReceived($amount)
    {
        $country_civ = Country::find(107);

        $robus_currency = firm()->country->currencie->value;

        if ($country_civ->currencie->value > firm()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == firm()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }

    private function convertAdminCommission($amount, $wallet)
    {

        $country_civ = Country::find(107);
        // $robus_currency = sabdy()->country->currencie->value;
        if ($country_civ->currencie->value >  $wallet->country->currencie->value) {
            return $amount / $wallet->country->currencie->value;
        } elseif ($country_civ->currencie->value ==  $wallet->country->currencie->value) {
            return $amount;
        } else {
            return $amount / $wallet->country->currencie->value;
        }
    }


    public function getTransactionCount($walletModel, $status, $wallet_transactions, $transaction_days, $walletMy, $wallet_status)
    {

        $query = $walletModel::join('wallets', $wallet_transactions, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($wallet_status, $status);

        if (sabdy()->is_company) {
            $query->where(function ($query) use ($wallet_transactions) {
                $query->where($wallet_transactions, firm()->wallet->id);
            });
        }

        return $query->count();
    }

    public function getTodayTransactionAmountTotal($walletModel, $wallet_transaction, $transaction_days, $walletMy, $transaction_status, $wallet_amount)
    {

        $query = $walletModel::join('wallets', $wallet_transaction, 'wallets.id')
            ->whereDate($transaction_days, Carbon::today())
            ->where($transaction_status, 'SUCCESS');

        if (sabdy()->is_under_distributer) {
            $query->where(function ($query) use ($wallet_transaction) {
                $query->where($wallet_transaction, firm()->wallet->id);
            });
        }


        return $query->sum($wallet_amount);
    }

    public function getDaysTransaction($walletModel)
    {


        $totalAmount = 0;

        $amount = $walletModel::where('wallet_id', firm()->wallet->id)
            ->whereStatus('SUCCESS')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        if ($amount !== null) {
            $totalAmount += $amount;
        }


        return $totalAmount;
    }


    public function getAdminValeurProperty()
    {

        $admins = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', firm()->country->id)
            ->get();

        return  $admins;
    }
    public function getAdminCIVProperty()
    {

        $admin_civ =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        return  $admin_civ;
    }
    public function edit($id, $state)
    {
        $check = Wallet::whereId($id)->first();
        $this->user_id = $check->sidUser->id;
        $this->userdetails = $check->sidUser->firstname . ' ' . $check->sidUser->lastname;
        $this->userLocation = $check->sidUser->country->name . ' - ' . $check->sidUser->city->name .  ' - ' . $check->sidUser->zone;
        $this->wallet_id_add = $check->wallet_id;
        $this->userPhone = $check->sidUser->phone;
        $this->employerDesabled = $check->company_id;
        $this->categorieId_add = $check->sidUser->categorie_id;
        $this->emit($state);
    }

    public function update()
    {

        $this->validate([
            'wallet_id_add' => 'required',
            'categorieId_add' => 'required',
        ], [
            'wallet_id_add.required' => 'Votre wallet est obligatoire',
            'categorieId_add.required' => 'la categorie est obligatoire',

        ]);
        $check =  Wallet::where('wallet_id', $this->wallet_id_add)->first();

        $check->sidUser->update([
            'categorie_id'  => $this->categorieId_add,
        ]);

        $this->cancelEmployesField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Categorie modifié !');
    }


    public function disable()
    {

        $check = SidUser::find($this->user_id);

        if (!empty($check)) {

            if ($check->company_id  == firm()->id && $check->is_accepted_companie) {
                $msg = 'Vous avez été rétiré de ' . firm()->firstname;
                $msg_title = 'Déliaison a  ' . firm()->firstname;
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => 1, 'is_accepted_companie' => false, 'categorie_id' => null]);
            } else {
                $msg = 'Vous avez été rétiré de ' . firm()->firstname;
                $msg_title = 'Déliaison a  ' . firm()->firstname;
                $check->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                $check->update(['company_id' => 1, 'is_accepted_companie' => false, 'categorie_id' => null]);
            }
            $this->cancelEmployesField();
            $this->emit('modal-hide', 'hide');
            $this->emit('refreshPage');
            $this->emit('success', $check->company_id  == firm()->id ? 'Client lié !' : 'Client détaché !');
        }
    }

    public function cancelEmployesField()
    {

        $this->wallet_id_add = '';
        $this->amount_payment = '';
        $this->amount_payment_wallet = '';
        $this->wallet_id_employer = '';
        $this->categorieId  = null;
        $this->step = 1;
        $this->choose_type_payement_emp = null;
        $this->transaction_description_robus = '';
        $this->emit('modal-hide', 'hide');
    }

    public function getRobusPercentageCompanies()
    {
        return Company::whereId(firm()->company->id)->first();
    }

    public function render()
    {
        $users = SidUser::query();
        $account_employers = null;
        $amount_categories = null;

        $walletUser = firm();

        if ($this->search != "") {

            $this->resetPage();

            $users->where('is_company', firm()->id)->where("firstname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("lastname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("email", "LIKE",  "%" . $this->search . "%")
                ->orWhere("phone", "LIKE",  "%" . $this->search . "%");
        }

        $transaction_error = $this->getTransactionCount(WalletTransaction::class, 'ERROR', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_padding = $this->getTransactionCount(WalletTransaction::class, 'PENDING', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_success = $this->getTransactionCount(WalletTransaction::class, 'SUCCESS', 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by', 'wallet_transactions.status');
        $transaction_amount = $this->getTodayTransactionAmountTotal(WalletTransaction::class, 'wallet_transactions.wallet_id', 'wallet_transactions.created_at', 'wallet_transactions.updated_by',  'wallet_transactions.status',  'wallet_transactions.amount');
        $transaction_amount_month = $this->getDaysTransaction(WalletTransaction::class);
        $companyId = firm()->company->id;
        $currentUserId = firm()->id;

        if ($this->categorieId != '') {

            $account_employers =  SidUser::where('is_custumers', true)
                ->where('company_id', $companyId)
                ->where('id', '!=', $currentUserId)
                ->where('categorie_id', $this->categorieId)->count();

            $amount_categories = (float)$account_employers  * (float) $this->amount_payment;
        }

        return view('livewire.firms.employers', [
            "users" => SidUser::where('is_custumers', true)
                ->where('company_id', $companyId)
                ->where('id', '!=', $currentUserId)
                ->orderByDesc('created_at')
                ->paginate(30),
            "countries" => Country::where('status', true)->orderBy("name", 'desc')->get(),
            "categories" => Categorie::where('sid_user_id', $currentUserId)->orderBy("name", 'desc')->get(),
            "wallet_amount" => Wallet::where('sid_user_id', $currentUserId)->first(),
            "account_valeurs" => $account_employers,
            "amount_valeurs" => $amount_categories,
            'amount_transaction_nat_val_percent' => $this->commission_transaction_val_admin_receive,
            'amount_transaction_nat_val_receive' => $this->commission_transaction_val_nat_receive,
            'sum_error' =>  $transaction_error,
            'sum_success' =>  $transaction_success,
            'pending_success' => $transaction_padding,
            'sum_amount' => $transaction_amount,
            'usersCountry' => $walletUser->country->currencie->name,
            'sum_transaction_month' => $transaction_amount_month,
        ]);
    }
}
