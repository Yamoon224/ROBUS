<?php

namespace App\Http\Livewire\Historical;

use App\Models\Offer;
use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Events\UserNotifEvent;
use App\Events\AdminNotifEvent;
use App\Models\Payments\Payment;
use App\Models\Providers\Provider;
use Illuminate\Support\Facades\DB;
use App\Events\UpdatePaiementEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Wallets\WalletPayment;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class Paymenthisto extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";
    public $searchPaymentHistorical = "";
    public $statusFilter = "";
    public  $startDate = "";
    public  $endDate =  "";

    // edit
    public $paiement_num_contrat_edit;
    public $paiement_id_invoice_edit;
    public $paiement_decoder_canal_edit;
    public $paiement_name_sodecie_edit;
    public $paiement_name_cie_edit;
    public $paiement_phone_canal_edit;
    public $paiement_amount_Canal_edit;
    public $paiement_amount_p_sodecie_edit;
    public $paiement_amount_t_sodecie_edit;
    public $paiement_amount_total_edit;
    public $paiement_amount_partiel_edit;
    public $paiementChoix_phone_edit;
    public $paiementChoix_type_edit;
    public $offer_paiement_edit;
    public $facture_choix_type_edit;
    public $phone_invoice_pre_edit;
    public $pre_id_invoice_edit;
    public $paiementId;
    public $paiementStatus;
    public $provider_payment_edit;

    // Edit Paiment

    public function editPaiementHisto($id, $state)
    {
        $check = WalletPayment::whereId($id)->first();
        $provider = Provider::whereId($check->provider_id)->first();
        $offer = Offer::whereId($check->offer_id)->first();
        $this->paiementId = $id;
        $this->provider_payment_edit = $provider->id;
        $this->paiement_num_contrat_edit = $check->number_contract ?? null;
        $this->paiement_id_invoice_edit = $check->id_invoice;
        $this->paiement_decoder_canal_edit = $check->number_decoder ?? null;
        $this->paiement_phone_canal_edit = $check->number_phone ?? null;
        $this->paiement_name_sodecie_edit = $check->name ?? null;
        $this->paiement_name_cie_edit = $check->name ?? null;
        $this->paiement_amount_t_sodecie_edit = $check->amount ?? null;
        $this->paiement_amount_total_edit = $check->amount ?? null;
        $this->paiementStatus = $check->status;
        $this->facture_choix_type_edit = $check->factureChoix_type ?? null;
        $this->paiementChoix_phone_edit = $check->paiementChoix_phone ?? null;
        $this->offer_paiement_edit = $offer->id ?? null;
        $this->phone_invoice_pre_edit = $check->phone ?? null;
        $this->pre_id_invoice_edit = $check->id_invoice_pre ?? null;
        $this->emit($state);
    }

    public function updatePaiementHisto()
    {

        $check = WalletPayment::whereId($this->paiementId)->first();
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', Auth::guard('web')->user()->country->id)->get();
        if ($check) {


            if ($check->updated_by ==  Auth::guard('web')->user()->id) {

                if ($this->paiementStatus == "REFUND") {

                    $check->update([
                        'updated_by' => Auth::guard('web')->user()->id,
                        'status'  => $this->paiementStatus,
                    ]);
                    $payment = Payment::where('wallet_payment_id', $check->id)->first();

                    $payment->update([
                        'wallet_payment_id' => $check->id,
                        'sid_user_id' => Auth::guard('web')->user()->id,
                        'operator_transaction_name' =>  $check->provider->name,
                        'pay_amount' => 0.0,
                        'fee_amount' =>  0.0,
                        'received_amount' => 0.0,
                        'status'  =>    $check->status,
                    ]);

                    $commission_transaction =  (($check->amount)  -  ($check->amount * $check->paymentCost->percent));

                    if ($commission_transaction  <=   Auth::guard('web')->user()->wallet->amount) {

                        if (Auth::guard('web')->user()->whereStatus(true)->where('is_admin', true)) {

                            Auth::guard('web')->user()->wallet->update([
                                'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                'sid_user_relod' => Auth::guard('web')->user()->id,
                                'amount' => DB::raw('amount-'  . $commission_transaction),
                            ]);
                        }

                        $commission =  (($check->amount)  +  ($check->amount * $check->paymentCost->percent));

                        $check->wallet->update([
                            'amount' => DB::raw('amount+'  . $commission),

                        ]);

                        $check->update([
                            'commissions' => 0,
                            'commissions_admin' => 0,
                        ]);

                        if ($check->wallet->sidUser->country->code == "CI") {

                            $message = 'Votre demande de paiement de ' . $check->amount .  $check->wallet->currencie->code . ' '  . 'par '
                                . $check->provider->name  .  ' a '
                                . $check->updated_at . ' a été remboursé avec success ' . 'Robus vous remercie';

                            $encode = substr($check->wallet->sidUser->phone,  -10);

                            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");

                            $msg_title = 'Paiement de facture';
                            $msg = 'Deemande de paiement a été remboursé';
                            $check->wallet->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            event(new UpdatePaiementEvent($check));
                            event(new UserNotifEvent($notifs));
                            Notification::send($admins, new AdminNotification('Demande de transfers remboursé'));
                            $admin = SidUser::where('status', true)->where('is_admin', true)->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Payment remboursé !');
                        } else {


                            $msg_title = 'Paiement de facture';
                            $msg = 'Démande de paiement a été remboursé';
                            $check->wallet->sidUser->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
                            $notifs = $check->wallet->sidUser->unreadNotifications;
                            event(new UpdatePaiementEvent($check));
                            event(new UserNotifEvent($notifs));
                            Notification::send($admins, new AdminNotification('Demande de transfers remboursé'));
                            $admin = SidUser::where('status', true)->where('is_admin', true)->first();
                            $notifs = $admin->unreadNotifications;
                            event(new AdminNotifEvent($notifs));
                            $this->resetPaiementField();
                            $this->emit('modal-hide-paiement');
                            $this->emit('error', 'Payment remboursé !');
                            $notify = new MailController;
                            $notify->newPaiement($check);
                        }
                    } else {
                        $this->resetPaiementField();
                        $this->emit('modal-hide-paiementHisto');
                        $this->emit('info', 'Verifier votre solde !');
                    }
                } else {

                    $this->resetPaiementField();
                    $this->emit('modal-hide-paiementHisto');
                    $this->emit('success', 'Payment modifié !');
                }
            } else {

                $this->resetPaiementField();
                $this->emit('modal-hide-paiementHisto');
                $this->emit('success', 'Pas vous le valideur !');
            }
        } else {
            $this->resetPaiementField();
            $this->emit('modal-hide-paiementHisto');
            $this->emit('error', 'Aucune donnée !');
        }
    }

    public function resetPaiementField()
    {

        $this->emit('modal-hide', 'hide');
    }
    public function render()
    {

        $paiements =  WalletPayment::query();
        $offers =  Offer::where('status', true)->latest()->orderBy('name', 'asc')->get();


        if (sabdy()->is_under_distributer) {
            $paiements->where('created_by', sabdy()->id);
        }


        if ($this->startDate && $this->endDate) {

            // $paiements->whereBetween('wallet_payments.created_at', [$this->startDate, $this->endDate]);

            return view(
                'livewire.historical.paymenthisto',
                [
                    'user' => $this->WalletPaymentsDays,
                    'payment_historicals' =>   $paiements->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->whereBetween('wallet_payments.created_at', [$this->startDate, $this->endDate])->orderBy('wallet_payments.created_at', 'desc')->select('wallet_payments.*')->paginate(15),
                    'usersCountry' =>  Auth::guard('web')->user()->country->currencie->name,
                    // 'sum_commissions' => $this->WalletPaymentDays,
                    'provider_paiements' => $this->PaiementTransaction,
                    'offers_canal' => $offers,


                ]
            );
        }

        if ($this->searchPaymentHistorical != "") {

            $this->resetPage();

            $paiements->orwhereHas(
                'payment',
                function ($query) {
                    $query->where('operator_transaction_name', 'like', '%' . $this->searchPaymentHistorical . '%')
                        ->orWhere('transaction_id', 'like', '%' .  $this->searchPaymentHistorical   . '%')
                        ->orWhere('trans_comp_id', 'like', '%' .  $this->searchPaymentHistorical  . '%');
                }
            )
                ->Where("wallet_payments.amount", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.number_phone", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.name", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.number_decoder", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.phone", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.id_invoice_pre", "like",  "%" . $this->searchPaymentHistorical . "%")
                ->orWhere("wallet_payments.id_invoice", "like",  "%" . $this->searchPaymentHistorical . "%");
        }

        if ($this->statusFilter != "") {

            // $paiements->where("wallet_payments.status", $this->statusFilter);


            return view(
                'livewire.historical.paymenthisto',
                [
                    'user' => $this->PaymentsAllStatus,
                    'payment_historicals' =>   $paiements->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                        ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->where("wallet_payments.status", $this->statusFilter)->orderBy('wallet_payments.created_at', 'desc')->select('wallet_payments.*')->paginate(15),
                    'usersCountry' =>  Auth::guard('web')->user()->country->currencie->name,
                    // 'sum_commissions' => $this->WalletPaymentStatus,
                    'provider_paiements' => $this->PaiementTransaction,
                    'offers_canal' => $offers,
                ]
            );
        }

        return view(
            'livewire.historical.paymenthisto',
            [
                'user' => $this->PaymentsAllDays,
                'payment_historicals' =>   $paiements->join('wallets', 'wallet_payments.wallet_id', 'wallets.id')
                    ->where('wallets.country_id', Auth::guard('web')->user()->country->id)->orderBy('wallet_payments.created_at', 'desc')->select('wallet_payments.*')->paginate(15),
                'usersCountry' =>  Auth::guard('web')->user()->country->currencie->name,
                'provider_paiements' => $this->PaiementTransaction,
                'offers_canal' => $offers,


            ]
        );
    }

    public function getWalletPaymentsDaysProperty()
    {


        $data = [];

        $wallets = WalletPayment::whereBetween('wallet_payments.created_at', [$this->startDate, $this->endDate]);

        
        $walletUser = Auth::guard('web')->user();

        if ($walletUser->is_under_distributer) {
            // Filtrer par utilisateur
            $wallets->where('created_by', $walletUser->id);
        }
        
        $wallets = $wallets->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }


    public function getPaymentsAllStatusProperty()
    {


        $data = [];

        $wallets = WalletPayment::where("wallet_payments.status", $this->statusFilter)->get();

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->is_under_distributer) {
            // Filtrer par utilisateur
            $wallets->where('created_by', $walletUser->id);
        }

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getPaymentsAllDaysProperty()
    {


        $data = [];

        $wallets = WalletPayment::orderBy('wallet_payments.created_at', 'desc');

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->is_under_distributer) {
            // Filtrer par utilisateur
            $wallets->where('created_by', $walletUser->id);
        }
        
        $wallets = $wallets->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->amount;
        }


        return array_sum($data);
    }

    public function getPaymentsDaysProperty()
    {


        $data = [];

        $wallets = WalletPayment::whereBetween('wallet_payments.created_at', [$this->startDate, $this->endDate]);

        $walletUser = Auth::guard('web')->user();

        if ($walletUser->is_under_distributer) {
            // Filtrer par utilisateur
            $wallets->where('created_by', $walletUser->id);
        }

        $wallets = $wallets->get();

        foreach ($wallets as $bank) {
            // Accédez à la propriété amount sur chaque objet
            $data[] = $bank->commissions;
        }


        return array_sum($data);
    }





    public function getPaiementTransactionProperty()
    {
        $walletUser = Auth::guard('web')->user();

        $payments = Provider::join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $walletUser->country->id)->where('providers.status', true)
            ->where('providers.provider_type_id',  2)->select('providers.*')->orderBy('providers.name', 'asc')->get();

        return $payments;
    }
}
