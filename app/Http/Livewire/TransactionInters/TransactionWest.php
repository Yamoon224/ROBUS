<?php

namespace App\Http\Livewire\TransactionInters;

use Livewire\Component;
use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use App\Models\LevelCountry;
use App\Models\Banks\BankCard;
use App\Models\Wallets\Wallet;
use Illuminate\Support\Carbon;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use App\Models\Moneys\LevelMoney;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletInter;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Http;
use App\Models\Invoices\InvoiceInter;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Events\CreateWalletInterEvent;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Models\Payments\TransactionInter;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class TransactionWest extends Component
{

    public $amount_inter_out_civ;
    public $provider_inter_out_civ;
    public $country_inter_out_civ;
    public $phone_inter_out_civ;
    public $interId;
    public $phone_inter_robus_civ;

    public function resetValeurReload()
    {

        $this->amount_inter_out_civ = '';
        $this->provider_inter_out_civ = null;
        $this->country_inter_out_civ = null;
        $this->phone_inter_out_civ = '';
        $this->phone_inter_robus_civ = '';
        // $this->emit('modal-hide-transfers-validated');
        // $this->emit('modal-hide-transfers');
    }

    public function resetInterField()
    {
        $this->amount_inter_out_civ = '';
        $this->provider_inter_out_civ = null;
        $this->country_inter_out_civ = null;
        $this->phone_inter_robus_civ = '';
        $this->phone_inter_out_civ = '';
        $this->emit('modal-hide-inters-validated');
        $this->emit('menu-inters-active');
        $this->emit('modal-hide-inters');
        $this->emit('transaction_inters');
    }
    public function mount()
    {
        $this->resetValeurReload();
        // $this->emit('monEvenement', $resetValeurReload);
    }

    public function loadChoose3()
    {
        $this->provider_inter_out_civ = null;
    }

    public function cancelInterOutCivField()
    {
        $this->amount_inter_out_civ = '';
        $this->provider_inter_out_civ = null;
        $this->country_inter_out_civ = null;
        $this->phone_inter_out_civ = '';
        $this->phone_inter_robus_civ = '';
        $this->emit('modal-hide-inters-validated');
        $this->emit('menu-inters-active');
        $this->emit('transaction_inters');
    }

    public function addInterOutCiv()
    {

        $check = WalletInter::whereId($this->interId)->first();
        $country_robus = Country::whereId($this->country_inter_out_civ)
            // ->whereStatus(true)
            ->first();

        $admin_civ = $this->getAdminCIVProperty();
        $som_transfers =  $this->getSumValTransactionProperty();

        $provider_payment = Provider::whereId($this->provider_inter_out_civ)->first();

        $valeur_ceiling = sabdy()->silver_ceiling;

        try {

            if (!$check) {

                if (in_array($this->provider_inter_out_civ, [48, 49, 50, 55])) {

                    $this->validate(
                        [
                            'amount_inter_out_civ' => 'required',
                            'phone_inter_out_civ' => 'required',
                            'country_inter_out_civ' => 'required',
                        ],
                        [
                            'amount_inter_out_civ.required' =>
                            'Votre montant est obligatoire',
                            'country_inter_out_civ.required' => 'Pays  est obligatoire',
                            'phone_inter_out_civ.required' => 'Numero est obligatoire',
                        ]
                    );

                    if ($this->validatePhone()) {

                        $percent_robus =  $this->getRobusPercentageInter($country_robus, $this->provider_inter_out_civ);

                        if ($percent_robus !== null) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount()) {

                                    $valeur_received = $this->amount_inter_out_civ - ($percent_robus->percent * $this->amount_inter_out_civ);

                                    $walletInter = $this->createMobileWalletInter($percent_robus, $country_robus, $provider_payment);

                                    if ($walletInter != null) {

                                        if ($this->updateAmountReceived($walletInter, $country_robus, $valeur_received)) {

                                            if ($this->processCommissions($percent_robus, $country_robus, $walletInter, $admin_civ)) {
                                                $this->sendNotification($walletInter);
                                            } else {
                                                $this->resetInterField();
                                                $this->emit('modal-hide-inters');
                                                $this->emit('error', 'Error de transferts du montant!');
                                            }
                                        }
                                    } else {
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('error', 'Error de createtion transferts !');
                                    }
                                }
                            }
                        } else {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Niveau de pourcentage introuvable !');
                        }
                    }
                } elseif ($this->provider_inter_out_civ == 51) {

                    $this->validate(
                        [
                            'amount_inter_out_civ' => 'required',
                            'phone_inter_robus_civ' => 'required',
                            'country_inter_out_civ' => 'required',
                        ],
                        [
                            'amount_inter_out_civ.required' =>
                            'Votre montant est obligatoire',
                            'country_inter_out_civ.required' => 'Pays  est obligatoire',
                            'phone_inter_robus_civ.required' => 'Numero est obligatoire',
                        ]
                    );
                    if ($this->validateWallet($country_robus)) {

                        $percent_robus =  $this->getRobusPercentageInter($country_robus, $this->provider_inter_out_civ);

                        if ($percent_robus !== null) {

                            if ($this->validateDailyLimit($som_transfers, $valeur_ceiling)) {

                                if ($this->validateAmount()) {

                                    $valeur_received = $this->amount_inter_out_civ - ($percent_robus->percent * $this->amount_inter_out_civ);

                                    $walletInter = $this->createRobusWalletInter($percent_robus, $country_robus, $provider_payment);

                                    if ($walletInter != null) {

                                        if ($this->updateAmountRobusReceived($walletInter, $country_robus, $valeur_received)) {

                                            if ($this->processCommissionsRobus($percent_robus, $country_robus, $walletInter, $admin_civ)) {
                                                $this->sendNotificationRobus($walletInter);
                                            } else {
                                                $this->resetInterField();
                                                $this->emit('modal-hide-inters');
                                                $this->emit('error', 'Error de transferts du montant!');
                                            }
                                        }
                                    } else {
                                        $this->resetInterField();
                                        $this->emit('modal-hide-inters');
                                        $this->emit('error', 'Error de createtion transferts !');
                                    }
                                }
                            }
                        } else {
                            $this->resetInterField();
                            $this->emit('modal-hide-inters');
                            $this->emit('error', 'Niveau de pourcentage introuvable !');
                        }
                    }
                } else {
                    $this->resetInterField();
                    $this->emit('modal-hide-inters');
                    $this->emit('error', 'Ce providers n\'est pas encore disponible chez Robus !');
                }
            } else {
                $this->emit('error', 'Internationnal existante !');
            }
        } catch (\Exception $exception) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }
    public function render()
    {
        $default_valeur = 2000000;
        $commission_transaction_val_inter_pay = null;
        $commission_transaction_val_inter_receive = null;

        $commission_transaction_val_percent_pay = null;

        $country_robus = Country::whereId($this->country_inter_out_civ)
            // ->whereStatus(true)
            ->first();
        $transaction = WalletTransaction::query();

        $country_civ = Country::whereId(107)
            // ->whereStatus(true)
            ->first();
        // Nationnal commission transaction

        if ($this->amount_inter_out_civ != '') {

            if (sabdy()->wallet->sidUser->is_under_distributer == true) {


                $commission_inter =  $this->getRobusPercentageInter($country_robus, $this->provider_inter_out_civ);

                if ($commission_inter) {

                    if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                        $commission_transaction_val_percent_pay =  (float)$commission_inter->percent_under_distributer  * (float) $this->amount_inter_out_civ;
                    } else {

                        $commission_transaction_val_percent_pay =  (float)$commission_inter->percent  * (float) $this->amount_inter_out_civ;
                    }


                    if (sabdy()->country->currencie->value > $country_robus->currencie->value) {


                        if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                            $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                        }

                        $valeur_verify =  (float) $this->amount_inter_out_civ - $commission_transaction_val_percent_pay;

                        //   Convertion  in CFA
                        $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) sabdy()->country->currencie->value;

                        //   Convertion in Country send
                        $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;


                        if ($valeur_verify < 0) {

                            $commission_transaction_val_inter_receive =  "Montant negatif";
                        } else {

                            $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                        }
                    } elseif (sabdy()->country->currencie->value == $country_robus->currencie->value) {

                        if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                            $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                        }

                        $valeur_verify =  (float) $this->amount_inter_out_civ - $commission_transaction_val_percent_pay;

                        //   Convertion  in CFA
                        $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify;

                        //   Convertion in Country send
                        $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa;


                        if ($valeur_verify < 0) {

                            $commission_transaction_val_inter_receive =  "Montant negatif";
                        } else {

                            $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                        }
                    } elseif (sabdy()->country->currencie->value < $country_robus->currencie->value) {

                        if (sabdy()->is_under_distributer == 1 ||  sabdy()->is_distributer == 1) {

                            $commission_transaction_val_inter_pay =  (float)$commission_inter->percent_deposit_under_distributer  * (float) $commission_transaction_val_percent_pay;
                        }

                        $valeur_verify =  (float) $this->amount_inter_out_civ - $commission_transaction_val_percent_pay;

                        //   Convertion  in CFA
                        $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_verify *   (float) sabdy()->country->currencie->value;

                        //   Convertion in Country send
                        $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;


                        if ($valeur_verify < 0) {

                            $commission_transaction_val_inter_receive =  "Montant negatif";
                        } else {

                            $commission_transaction_val_inter_receive =  $commission_transaction_val_inter_convertion_send;
                        }
                    }
                } else {
                    $commission_transaction_val_inter_pay  = "Pas Sous-distributeur";
                    $commission_transaction_val_inter_receive =  "Pas Sous-distributeur";
                }
            } else {
                $commission_transaction_val_inter_pay  = "Pas Sous-distributeur";
                $commission_transaction_val_inter_receive =  "Pas Sous-distributeur";
            }
        }
        return view('livewire.transaction-inters.transaction-west', [
            'walletSolde' => Wallet::where(
                'sid_user_id',
                sabdy()->id
            )->sum('amount'),
            'transactions' => $transaction
                ->join('wallets', 'wallet_transactions.wallet_id', 'wallets.id')
                ->where('wallet_transactions.status', 'PENDING')
                ->where('wallet_transactions.created_by', sabdy()->id)
                // ->where('wallet_transactions.type_transaction' , 'Depot')
                ->select('wallet_transactions.*')
                ->orderBy('wallet_transactions.created_at', 'desc')
                ->paginate(15),
            'amount_transaction_nat_val_pay' => $commission_transaction_val_inter_pay,
            'amount_transaction_nat_commission_val_pay' => $commission_transaction_val_percent_pay,
            'amount_transaction_nat_val_receive' => $commission_transaction_val_inter_receive,
            'countrie_west_send_centre' => Country::whereRobus(true)
                ->whereIn('name_groupe', array('AFC', 'CIV', 'AFL'))
                ->where('id', '!=', sabdy()->country->id)
                ->orderBy('name', 'asc')
                ->get(),
            'provide_civ' => Provider::whereIn('id', array(49, 50, 55, 48))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_civ_out' => Provider::whereIn('id', array(49, 50, 48))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_west' => Provider::whereIn('id', array(51))
                ->orderBy('name', 'asc')
                ->get(),
            'usersCountry' => sabdy()->wallet->currencie->code,
            'countrie_number_local' => sabdy()->country
                ->indecative,
        ]);
    }

    private function getRobusPercentageInter($countrie, $provider_valeur)
    {

        $levelValeur = 0;

        if (sabdy()->country->name_groupe == 'AFL') {

            $countriesLevels = [
                // Is Benin
                25 => [
                    36 => ($provider_valeur != 51) ? 2 : 37, // BF
                    133 => ($provider_valeur != 51) ? 3 : 38, // Mali
                    214 => ($provider_valeur != 51) ? 4 : 39, // Togo
                    188 => ($provider_valeur != 51) ? 5 : 40, // Senegal
                    83 => ($provider_valeur != 51) ? 6 : 41, // Ghana
                    157 => ($provider_valeur != 51) ? 7 : 42, // Niger
                    91 => ($provider_valeur != 51) ? 8 : 43, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 1 : 44, // CIV
                    1 => ($provider_valeur != 51) ? 248 : 241, // USA
                    218 => ($provider_valeur != 51) ? 264 : 292, // Tunisia
                    146 => ($provider_valeur != 51) ? 278 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 435 : 312, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 467 : 355, // France
                    179 => ($provider_valeur != 51) ? 531 : 419, // Russie
                    226 => ($provider_valeur != 51) ? 499 : 387, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 515 : 403, // Italie
                    82 => ($provider_valeur != 51) ? 483 : 371, // Allemagne
                    39 => ($provider_valeur != 51) ? 129 : 178, // Cameroun
                    79 => ($provider_valeur != 51) ? 130 : 179, // Gabon
                    50 => ($provider_valeur != 51) ? 131 : 180, // COngo Braza
                    57 => ($provider_valeur != 51) ? 132 : 181, // RDC
                    122 => ($provider_valeur != 51) ? 133 : 182, // Liberia
                    43 => ($provider_valeur != 51) ? 134 : 183, // Thacd
                    42 => ($provider_valeur != 51) ? 135 : 184, // Afrique centrale
                ],
                // Is BF
                36 =>  [
                    25 => ($provider_valeur != 51) ? 2 : 37, // Benin
                    133 => ($provider_valeur != 51) ? 9 : 45, // Mali
                    214 => ($provider_valeur != 51) ? 10 : 46, // Togo
                    188 => ($provider_valeur != 51) ? 11 : 47, // Senegal
                    83 => ($provider_valeur != 51) ? 12 : 48, // Ghana
                    157 => ($provider_valeur != 51) ? 13 : 49, // Niger
                    91 => ($provider_valeur != 51) ? 14 : 50, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 15 : 51, // CIV
                    1 => ($provider_valeur != 51) ? 249 : 242, // USA
                    218 => ($provider_valeur != 51) ? 265 : 293, // Tunisia
                    146 => ($provider_valeur != 51) ? 279 : 302, // Maroc
                    199 => ($provider_valeur != 51) ? 436 : 313, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 468 : 356, // France
                    179 => ($provider_valeur != 51) ? 532 : 420, // Russie
                    226 => ($provider_valeur != 51) ? 500 : 388, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 516 : 404, // Italie
                    82 => ($provider_valeur != 51) ? 484 : 372, // Allemagne
                    39 => ($provider_valeur != 51) ? 136 : 185, // Cameroun
                    79 => ($provider_valeur != 51) ? 137 : 186, // Gabon
                    50 => ($provider_valeur != 51) ? 138 : 187, // COngo Braza
                    57 => ($provider_valeur != 51) ? 139 : 188, // RDC
                    122 => ($provider_valeur != 51) ? 140 : 189, // Liberia
                    43 => ($provider_valeur != 51) ? 141 : 190, // Thacd
                    42 => ($provider_valeur != 51) ? 142 : 191, // Afrique centrale
                ],
                // Mali
                133 =>  [
                    25 => ($provider_valeur != 51) ? 3 : 38, // Benin
                    214 => ($provider_valeur != 51) ? 16 : 52, // Togo
                    36 => ($provider_valeur != 51) ? 9 : 45, // BF
                    188 => ($provider_valeur != 51) ? 17 : 53, // Senegal
                    83 => ($provider_valeur != 51) ? 18 : 54, // Ghana
                    157 => ($provider_valeur != 51) ? 19 : 55, // Niger
                    91 => ($provider_valeur != 51) ? 20 : 56, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 21 : 57, // CIV
                    1 => ($provider_valeur != 51) ? 570 : 243, // USA
                    218 => ($provider_valeur != 51) ? 574 : 294, // Tunisia
                    146 => ($provider_valeur != 51) ? 575 : 303, // Maroc
                    199 => ($provider_valeur != 51) ? 437 : 314, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 469 : 357, // France
                    179 => ($provider_valeur != 51) ? 533 : 421, // Russie
                    226 => ($provider_valeur != 51) ? 501 : 389, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 517 : 405, // Italie
                    82 => ($provider_valeur != 51) ? 485 : 373, // Allemagne
                    39 => ($provider_valeur != 51) ? 143 : 192, // Cameroun
                    79 => ($provider_valeur != 51) ? 144 : 193, // Gabon
                    50 => ($provider_valeur != 51) ? 145 : 194, // COngo Braza
                    57 => ($provider_valeur != 51) ? 146 : 195, // RDC
                    122 => ($provider_valeur != 51) ? 147 : 196, // Liberia
                    43 => ($provider_valeur != 51) ? 148 : 197, // Thacd
                    42 => ($provider_valeur != 51) ? 149 : 198, // Afrique centrale
                ],
                // Togo
                214 =>  [
                    25 => ($provider_valeur != 51) ?  4 : 39, // Benin
                    188 => ($provider_valeur != 51) ? 22 : 58, // Senegal
                    83 => ($provider_valeur != 51) ? 23 : 59, // Ghana
                    36 => ($provider_valeur != 51) ? 10 : 46, // BF
                    133 => ($provider_valeur != 51) ? 16 : 52, // Mali
                    157 => ($provider_valeur != 51) ? 24 : 60, // Niger
                    91 => ($provider_valeur != 51) ? 25 : 61, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 26 : 62, // CIV
                    1 => ($provider_valeur != 51) ? 250 : 244, // USA
                    218 => ($provider_valeur != 51) ? 266 : 295, // Tunisia
                    146 => ($provider_valeur != 51) ? 280 : 304, // Maroc
                    199 => ($provider_valeur != 51) ? 438 : 315, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 470 : 358, // France
                    179 => ($provider_valeur != 51) ? 534 : 422, // Russie
                    226 => ($provider_valeur != 51) ? 502 : 390, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 518 : 406, // Italie
                    82 => ($provider_valeur != 51) ? 486 : 374, // Allemagne
                    39 => ($provider_valeur != 51) ? 150 : 199, // Cameroun
                    79 => ($provider_valeur != 51) ? 151 : 200, // Gabon
                    50 => ($provider_valeur != 51) ? 152 : 201, // COngo Braza
                    57 => ($provider_valeur != 51) ? 153 : 202, // RDC
                    122 => ($provider_valeur != 51) ? 154 : 203, // Liberia
                    43 => ($provider_valeur != 51) ? 155 : 204, // Thacd
                    42 => ($provider_valeur != 51) ? 156 : 205, // Afrique centrale
                ],
                // Senegal
                188 =>  [

                    25 => ($provider_valeur != 51) ?  5 : 40, // Benin
                    83 => ($provider_valeur != 51) ? 27 : 63, // Ghana
                    133 => ($provider_valeur != 51) ? 28 : 64, // Niger
                    36 => ($provider_valeur != 51) ? 11 : 47, // BF
                    188 => ($provider_valeur != 51) ? 17 : 53, // Mali
                    214 => ($provider_valeur != 51) ? 22 : 58, // TOgo
                    91 => ($provider_valeur != 51) ? 29 : 65, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 30 : 66, // CIV
                    1 => ($provider_valeur != 51) ? 251 : 245, // USA
                    218 => ($provider_valeur != 51) ? 267 : 296, // Tunisia
                    146 => ($provider_valeur != 51) ? 281 : 305, // Maroc
                    199 => ($provider_valeur != 51) ? 439 : 316, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 471 : 359, // France
                    179 => ($provider_valeur != 51) ? 535 : 423, // Russie
                    226 => ($provider_valeur != 51) ? 503 : 391, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 519 : 407, // Italie
                    82 => ($provider_valeur != 51) ? 487 : 375, // Allemagne
                    39 => ($provider_valeur != 51) ? 157 : 206, // Cameroun
                    79 => ($provider_valeur != 51) ? 158 : 207, // Gabon
                    50 => ($provider_valeur != 51) ? 159 : 208, // COngo Braza
                    57 => ($provider_valeur != 51) ? 160 : 209, // RDC
                    122 => ($provider_valeur != 51) ? 161 : 210, // Liberia
                    43 => ($provider_valeur != 51) ? 162 : 211, // Thacd
                    42 => ($provider_valeur != 51) ? 163 : 212, // Afrique centrale
                ],
                // Ghana
                83 =>  [
                    25 => ($provider_valeur != 51) ?  6 : 41, // Benin
                    188 => ($provider_valeur != 51) ? 27 : 63, // Senegal
                    157 => ($provider_valeur != 51) ? 31 : 67, // Niger
                    36 => ($provider_valeur != 51) ? 12 : 48, // bf
                    133 => ($provider_valeur != 51) ? 18 : 54, // Mali
                    214 => ($provider_valeur != 51) ? 23 : 59, // Ghana
                    91 => ($provider_valeur != 51) ? 32 : 68, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 33 : 69, // CIV
                    1 => ($provider_valeur != 51) ? 252 : 571, // USA
                    218 => ($provider_valeur != 51) ? 268 : 297, // Tunisia
                    146 => ($provider_valeur != 51) ? 282 : 306, // Maroc
                    199 => ($provider_valeur != 51) ? 440 : 317, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 472 : 360, // France
                    179 => ($provider_valeur != 51) ? 536 : 424, // Russie
                    226 => ($provider_valeur != 51) ? 504 : 392, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 520 : 408, // Italie
                    82 => ($provider_valeur != 51) ? 488 : 376, // Allemagne
                    39 => ($provider_valeur != 51) ? 164 : 213, // Cameroun
                    79 => ($provider_valeur != 51) ? 165 : 214, // Gabon
                    50 => ($provider_valeur != 51) ? 166 : 215, // COngo Braza
                    57 => ($provider_valeur != 51) ? 167 : 216, // RDC
                    122 => ($provider_valeur != 51) ? 168 : 217, // Liberia
                    43 => ($provider_valeur != 51) ? 169 : 218, // Thacd
                    42 => ($provider_valeur != 51) ? 170 : 219, // Afrique centrale
                ],
                // Niger
                157 =>  [
                    25 => ($provider_valeur != 51) ? 7 : 42, // Benin
                    188 => ($provider_valeur != 51) ? 28 : 64, // Senegal
                    91 => ($provider_valeur != 51) ? 34 : 70, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 35 : 71, // CIV
                    214 => ($provider_valeur != 51) ? 24 : 60, // Togo
                    1 => ($provider_valeur != 51) ? 253 : 246, // USA
                    36 => ($provider_valeur != 51) ? 13 : 49, // BF
                    133 => ($provider_valeur != 51) ? 19 : 55, // Mali
                    218 => ($provider_valeur != 51) ? 269 : 298, // Tunisia
                    146 => ($provider_valeur != 51) ? 283 : 307, // Maroc
                    199 => ($provider_valeur != 51) ? 441 : 318, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 473 : 361, // France
                    179 => ($provider_valeur != 51) ? 537 : 425, // Russie
                    226 => ($provider_valeur != 51) ? 505 : 393, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 521 : 409, // Italie
                    82 => ($provider_valeur != 51) ? 489 : 377, // Allemagne
                    39 => ($provider_valeur != 51) ? 576 : 220, // Cameroun
                    79 => ($provider_valeur != 51) ? 577 : 221, // Gabon
                    50 => ($provider_valeur != 51) ? 578 : 222, // COngo Braza
                    57 => ($provider_valeur != 51) ? 579 : 223, // RDC
                    122 => ($provider_valeur != 51) ? 580 : 224, // Liberia
                    43 => ($provider_valeur != 51) ? 581 : 225, // Thacd
                    42 => ($provider_valeur != 51) ? 582 : 226, // Afrique centrale
                ],
                // Guinnée Conakry
                91 =>  [
                    25 => ($provider_valeur != 51) ? 7 : 42, // Benin
                    188 => ($provider_valeur != 51) ? 29 : 65, // Senegal
                    157 => ($provider_valeur != 51) ? 34 : 70, // Niger
                    83 => ($provider_valeur != 51) ?  32 : 68, // Ghana
                    36 => ($provider_valeur != 51) ? 14 : 50, //BF
                    133 => ($provider_valeur != 51) ? 20 : 56, // Mali
                    107 => ($provider_valeur != 51) ? 36 : 72, // CIV
                    214 => ($provider_valeur != 51) ? 25 : 61, //Togo
                    1 => ($provider_valeur != 51) ? 253 : 246, // USA
                    36 => ($provider_valeur != 51) ? 14 : 50, // BF
                    218 => ($provider_valeur != 51) ? 270 : 299, // Tunisia
                    146 => ($provider_valeur != 51) ? 284 : 308, // Maroc
                    199 => ($provider_valeur != 51) ? 442 : 319, // ESPAGNE
                    74 => ($provider_valeur != 51) ? 474 : 362, // France
                    179 => ($provider_valeur != 51) ? 538 : 426, // Russie
                    226 => ($provider_valeur != 51) ? 506 : 394, // Royaume Unie
                    106 => ($provider_valeur != 51) ? 522 : 410, // Italie
                    82 => ($provider_valeur != 51) ? 490 : 378, // Allemagne
                    39 => ($provider_valeur != 51) ? 171 : 227, // Cameroun
                    79 => ($provider_valeur != 51) ? 172 : 228, // Gabon
                    50 => ($provider_valeur != 51) ? 173 : 229, // COngo Braza
                    57 => ($provider_valeur != 51) ? 174 : 230, // RDC
                    122 => ($provider_valeur != 51) ? 175 : 231, // Liberia
                    43 => ($provider_valeur != 51) ? 176 : 232, // Thacd
                    42 => ($provider_valeur != 51) ? 177 : 233, // Afrique centrale
                ]

                // Ajoutez d'autres identifiants de pays et leurs valeurs associées selon vos besoins
            ];
        } else {
            return  $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Erreur de calcul du montant a recevoir !');
            return false;
        }

        $robusId = sabdy()->country->id; // Pour éviter les appels répétitifs

        if (isset($countriesLevels[$robusId])) {

            $specificCountry = $countriesLevels[$robusId][$countrie->id];

            if ($specificCountry) {
                $levelValeur = $specificCountry;
            } else {
                return  $this->resetInterField();
                $this->emit('modal-hide-inters');
                $this->emit('error', 'Erreur de calcul du montant a recevoir !');
                return false; // La levelValeur pour $provider_valeur n'est pas définie pour $countrie
            }
        } else {
            return  $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Erreur de calcul du montant a recevoir !');
            return false;
        }

        return  LevelCountry::whereId($levelValeur)->first();
    }
    //Wallet Update Amounts 
    private function updateAmountReceived($walletInter, $country_robus, $valeur_received)
    {

        $country_civ = Country::whereId(107)
            ->first();

        if (sabdy()->country->currencie->value > $country_robus->currencie->value) {


            //   Convertion  in CFA
            $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_received *   (float) sabdy()->country->currencie->value;

            //   Convertion in Country send
            $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;
        } elseif (sabdy()->country->currencie->value == $country_robus->currencie->value) {

            $commission_transaction_val_inter_convertion_send = (float)   $valeur_received;
        } elseif (sabdy()->country->currencie->value < $country_robus->currencie->value) {

            //   Convertion  in CFA
            $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_received *   (float) sabdy()->country->currencie->value;

            //   Convertion in Country send
            $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;
        }

        if ($commission_transaction_val_inter_convertion_send) {

            $walletInter->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            return true;
        } else {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Erreur de calcul du montant a recevoir !');
            return false;
        }
    }

    private function processCommissions($percent_robus, $country_robus, $walletInter, $admin_civ)
    {

        $commission =  $this->amount_inter_out_civ;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);
        // sabdy()->wallet->update([
        //     'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
        //     'sid_user_relod' => sabdy()->id,
        //     'amount' => DB::raw('amount-' . $commission),
        // ]);

        // Commissions Inters 
        if (sabdy()->is_under_distributer == 1) {

            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent_under_distributer * $this->amount_inter_out_civ;

            // 50 %
            $valuer_admin = $percent_robus->percent_admin_under_distributer * $valeur_percent_admin;

            // 20%
            $valuer_deposit = $percent_robus->percent_deposit_under_distributer *  $valeur_percent_admin;

            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin;
            } else {


                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            }

            // $valuer_with_drawal = $percent_robus->percent_with_drawal *  $valeur_percent_admin;

            $walletInter->update([
                'commissions' => DB::raw('commissions+'  . $valuer_deposit),
            ]);

            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $valeur_single,
                        'sid_user_id' =>   $admin_single->id,
                        'wallet_transaction_id' => null,
                        'wallet_bank_id' => null,
                        'wallet_deposit_id' => null,
                        'wallet_inter_id' =>  $walletInter->id,
                        'wallet_payment_id' => null,
                        'wallet_phone_credit_id' => null,
                        'wallet_reload_id' => null,
                        'bank_card_id' => null,
                        'description' => 'Commission',
                    ]);
                    $admin_single->wallet->update([
                        'reload_by' => sabdy()->firstname .
                            ' ' .
                            sabdy()->lastname,
                        'sid_user_relod' => sabdy()->id,
                        'amount' => DB::raw('amount+' . $valeur_single),
                    ]);
                }
            }
        } else {

            if (sabdy()->country->code == 'CI') {

                $message = \App\Support\SmsMessage::transaction($walletInter, (string) ($walletInter->status ?? 'PENDING'), sabdy()->wallet->currencie->code, 'Paiement');

                $encode = substr(sabdy()->phone, -10);

                Http::get("https://app.smspro.africa/api/http/sms/send", [
                    'recipient' => "225$encode",
                    'sender_id' => "ROBUS",
                    'message' => \App\Support\SmsMessage::normalize($message),
                    'api_token' => "20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af",
                ]);
            }
            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent_admin * $this->amount_inter_out_civ;

            if ($country_civ->currencie->value > $country_robus->currencie->value) {

                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == $country_robus->currencie->value) {

                $admin_valeur_convertion = (float)   $valeur_percent_admin;
            } else {

                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            }

            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $valeur_single,
                        'sid_user_id' =>   $admin_single->id,
                        'wallet_transaction_id' => null,
                        'wallet_bank_id' => null,
                        'wallet_deposit_id' => null,
                        'wallet_inter_id' =>  $walletInter->id,
                        'wallet_payment_id' => null,
                        'wallet_phone_credit_id' => null,
                        'wallet_reload_id' => null,
                        'bank_card_id' => null,
                        'description' => 'Commission',
                    ]);
                    $admin_single->wallet->update([
                        'reload_by' => sabdy()->firstname .
                            ' ' .
                            sabdy()->lastname,
                        'sid_user_relod' => sabdy()->id,
                        'amount' => DB::raw('amount+' . $valeur_single),
                    ]);
                }
            }
        }

        return true;
    }

    private function sendNotification($walletInter)
    {

        $msg_title = 'Transfert internationnal Mobile money';
        $msg = 'Demande de transfert est en cours de validation ';
        sabdy()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletInterEvent($walletInter));
        $this->emit('transaction_inter_sends', $walletInter->id);
        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
        Notification::send(
            $admins,
            new AdminNotification(
                'Demande de paiement de ' .
                    ' ' . $walletInter->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfers d\'argent Money de ' .
            ' ' . $walletInter->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }

            // $notifs = $admin->unreadNotifications;
            // event(new AdminNotifEvent($notifs));
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newPaiementInter($walletInter);
            }
        } else {

            // $notifs = $admin->unreadNotifications;
            // event(new AdminNotifEvent($notifs));
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newPaiementInter($walletInter);
            }
        }
    }

    private function sendNotificationRobus($walletInter)
    {

        $msg_title = 'Transfert internationnal Robus';
        $msg = 'Votre transfert vers Robus effectué avec success ';
        sabdy()->notify(
            new UserNotification([
                'title' => $msg_title,
                'message' => $msg,
            ])
        );
        $notifs = sabdy()->unreadNotifications;
        event(new CreateWalletInterEvent($walletInter));
        $this->emit('transaction_inter_sends', $walletInter->id);
        $admins = SidUser::whereStatus(true)->whereIsAdmin(true)->where('country_id', $walletInter->country_id)->get();
        Notification::send(
            $admins,
            new AdminNotification(
                'Transfert  Robus de ' .
                    ' ' . $walletInter->wallet->sidUser->firstname
            )
        );
        Notification::send($admins, new AdminNotification('Transfers d\'argent Robus de ' .
            ' ' . $walletInter->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }

            // $notifs = $admin->unreadNotifications;
            // event(new AdminNotifEvent($notifs));
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newPaiementInter($walletInter);
            }
        } else {

            // $notifs = $admin->unreadNotifications;
            // event(new AdminNotifEvent($notifs));
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('success', 'Transfers effectué !');
            if (sabdy()->email != null) {
                $notify = new MailController();
                $notify->newPaiementInter($walletInter);
            }
        }
    }

    // Validated WALLET mobile
    private function createMobileWalletInter($percent_robus, $country_robus, $provider_payment)
    {
        $val_amount = $this->amount_inter_out_civ;
        $amount_solde = sabdy()->wallet->amount - $val_amount;

        return WalletInter::create([
            'wallet_id' => sabdy()->wallet->id,
            'amount' => $this->amount_inter_out_civ,
            'description' => null,
            'inter_cost_id' => null,
            'transaction_cost_id' => $percent_robus->id,
            'phone' => $country_robus->indecative . $this->phone_inter_out_civ ?? null,
            'number_compte' => null,
            'created_by' => sabdy()->id,
            'provider_id' => $provider_payment->id,
            'costs_amount' => $percent_robus->percent * $this->amount_inter_out_civ,
            'provider_type' => 1,
            'status' => 'PENDING',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => null,
            'amount_solde' => $amount_solde ?? null
        ]);
    }
    // Wallet Robus

    private function updateAmountRobusReceived($walletInter, $country_robus, $valeur_received)
    {

        $country_civ = Country::whereId(107)
            ->first();

        $wallet_search = Wallet::where('wallet_id', $this->phone_inter_out_civ)
            ->where('country_id', $country_robus->id)
            //   ->where('status', true)
            ->first();

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));

        if (sabdy()->country->currencie->value > $country_robus->currencie->value) {

            //   Convertion  in CFA
            $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_received *   (float) sabdy()->country->currencie->value;

            //   Convertion in Country send
            $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;
        } elseif (sabdy()->country->currencie->value == $country_robus->currencie->value) {

            $commission_transaction_val_inter_convertion_send = (float)   $valeur_received;
        } elseif (sabdy()->country->currencie->value < $country_robus->currencie->value) {

            //   Convertion  in CFA
            $commission_transaction_val_inter_convertion_cfa =   (float)  $valeur_received *   (float) sabdy()->country->currencie->value;

            //   Convertion in Country send
            $commission_transaction_val_inter_convertion_send = (float)   $commission_transaction_val_inter_convertion_cfa  /   (float) $country_robus->currencie->value;
        }

        if ($commission_transaction_val_inter_convertion_send) {

            $walletInter->update(['amount_received' => $commission_transaction_val_inter_convertion_send]);

            $wallet_search->update([
                'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
                'sid_user_relod' => sabdy()->id,
                'amount' => DB::raw('amount+' . $commission_transaction_val_inter_convertion_send),
            ]);


            // Valuer in Ivoice transaction
            $transaction = TransactionInter::create([
                'wallet_inter_id' => $walletInter->id,
                'sid_user_id' => sabdy()->id,
                'transaction_id' => 'RB_' . $walletInter->provider->name . '_' . $transactionCode,
                'operator_transaction_name' => $walletInter->provider->name,
                'trans_comp_id' => 'RB_' . $transactionCompiCode,
                'pay_amount' => $walletInter->amount,
                'fee_amount' =>  $walletInter->costs_amount,
                'received_amount' => $commission_transaction_val_inter_convertion_send,
                'status' => $walletInter->status,
            ]);

            InvoiceInter::create([
                'title' => "Transfers d'argent",
                'transaction_inter_id' => $transaction->id,
                // 'company_id' => sabdy()->wallet->company->id,
                'description' => $walletInter->description,
                'is_send' => true,
                'status' => $walletInter->status,
                'created_by' => sabdy()->id,
            ]);

            return true;
        } else {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Erreur de calcul du montant a recevoir !');
            return false;
        }
    }

    // Validated wallet Robus

    private function createRobusWalletInter($percent_robus, $country_robus, $provider_payment)
    {
        $val_amount = $this->amount_inter_out_civ;
        $amount_solde = sabdy()->wallet->amount - $val_amount;

        return WalletInter::create([
            'wallet_id' => sabdy()->wallet->id,
            'amount' => $this->amount_inter_out_civ,
            'description' => "Transferer par ROBUS",
            'inter_cost_id' => null,
            'transaction_cost_id' => $percent_robus->id,
            'phone' =>  null,
            'number_compte' =>  $this->phone_inter_robus_civ,
            'created_by' => sabdy()->id,
            'provider_id' => $provider_payment->id,
            'costs_amount' => $percent_robus->percent * $this->amount_inter_out_civ,
            'provider_type' => 1,
            'status' => 'SUCCESS',
            'country_id' => $country_robus->id,
            'choose_type_transaction' => null,
            'amount_solde' => $amount_solde ?? null
        ]);
    }

    // Validated commissions

    private function processCommissionsRobus($percent_robus, $country_robus, $walletInter, $admin_civ)
    {

        $commission =  $this->amount_inter_out_civ;
        $country_civ = Country::whereId(107)
            ->first();

        $wallet = sabdy()->wallet;


        // Préparez les valeurs mises à jour
        $updatedValues = [
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount - ' . $commission),
        ];

        $wallet->update($updatedValues);

        // Commissions Inters 
        if (sabdy()->is_under_distributer == 1) {

            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent_under_distributer * $this->amount_inter_out_civ;

            // 50 %
            $valuer_admin = $percent_robus->percent_admin_under_distributer * $valeur_percent_admin;

            // 20%
            $valuer_deposit = $percent_robus->percent_deposit_under_distributer *  $valeur_percent_admin;


            $valuer_with_drawal =  $percent_robus->percent_with_drawal_under_distributer * $valeur_percent_admin;

            //  Admin send commission
            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {


                $admin_valeur_convertion_drawal =  $valuer_with_drawal  *  (float) sabdy()->country->currencie->value;
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

                //   Convertion in Country send
                $admin_valeur_convertion_drawal =  $valuer_with_drawal;
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin;
            } else {


                $admin_valeur_convertion_drawal =  $valuer_with_drawal  *  (float) sabdy()->country->currencie->value;
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valuer_admin *   (float) sabdy()->country->currencie->value;
            }

            // $valuer_with_drawal = $percent_robus->percent_with_drawal *  $valeur_percent_admin;

            $walletInter->update([
                'commissions' => DB::raw('commissions+'  . $valuer_deposit),
            ]);

            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = ($admin_valeur_convertion + $admin_valeur_convertion_drawal) / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $valeur_single,
                        'sid_user_id' =>   $admin_single->id,
                        'wallet_transaction_id' => null,
                        'wallet_bank_id' => null,
                        'wallet_deposit_id' => null,
                        'wallet_inter_id' =>  $walletInter->id,
                        'wallet_payment_id' => null,
                        'wallet_phone_credit_id' => null,
                        'wallet_reload_id' => null,
                        'bank_card_id' => null,
                        'description' => 'Commission',
                    ]);
                    $admin_single->wallet->update([
                        'reload_by' => sabdy()->firstname .
                            ' ' .
                            sabdy()->lastname,
                        'sid_user_relod' => sabdy()->id,
                        'amount' => DB::raw('amount+' . $valeur_single),
                    ]);
                }
            }
        } else {

            if (sabdy()->country->code == 'CI') {

                $message = \App\Support\SmsMessage::transaction($walletInter, (string) ($walletInter->status ?? 'PENDING'), sabdy()->wallet->currencie->code, 'Paiement');

                $encode = substr(sabdy()->phone, -10);

                Http::get("https://app.smspro.africa/api/http/sms/send", [
                    'recipient' => "225$encode",
                    'sender_id' => "ROBUS",
                    'message' => \App\Support\SmsMessage::normalize($message),
                    'api_token' => "20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af",
                ]);
            }
            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent_admin * $this->amount_inter_out_civ;

            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

                //   Convertion in Country send
                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valeur_percent_admin;
            } else {

                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            }

            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = $admin_valeur_convertion / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $valeur_single,
                        'sid_user_id' =>   $admin_single->id,
                        'wallet_transaction_id' => null,
                        'wallet_bank_id' => null,
                        'wallet_deposit_id' => null,
                        'wallet_inter_id' =>  $walletInter->id,
                        'wallet_payment_id' => null,
                        'wallet_phone_credit_id' => null,
                        'wallet_reload_id' => null,
                        'bank_card_id' => null,
                        'description' => 'Commission',
                    ]);
                    $admin_single->wallet->update([
                        'reload_by' => sabdy()->firstname .
                            ' ' .
                            sabdy()->lastname,
                        'sid_user_relod' => sabdy()->id,
                        'amount' => DB::raw('amount+' . $valeur_single),
                    ]);
                }
            }
        }

        return true;
    }

    // Validated wallet
    private function validateWallet($country_robus)
    {

        // Search Wallet Send
        $wallet_search = Wallet::where('wallet_id', $this->phone_inter_robus_civ)
            ->where('country_id', $country_robus->id)
            //   ->where('status', true)
            ->first();

        if ($this->phone_inter_robus_civ == sabdy()->wallet->wallet_id || $wallet_search->sidUser->is_custumers == false) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Ce numero vous appartient !');
            return false;
        }

        return true;
    }

    // Validated phone
    private function validateAmount()
    {

        $val_amount = $this->amount_inter_out_civ;

        if ($val_amount > sabdy()->wallet->amount) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Vérifiez votre solde !');
            return false;
        }

        return true;
    }
    // Validated phone
    private function validatePhone()
    {

        if ($this->phone_inter_out_civ == sabdy()->phone) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('error', 'Ce numero vous appartient !');
            return false;
        }

        return true;
    }

    // Validated date daily
    private function validateDailyLimit($som_transfers, $valeur_ceiling)
    {
        if ($som_transfers >= $valeur_ceiling) {
            $this->resetInterField();
            $this->emit('modal-hide-inters');
            $this->emit('warning', 'Votre montant journalier est atteint !');
            return false;
        }

        return true;
    }
    public function getSumValTransactionProperty()
    {

        $somm_valeur =  $this->WalletBanksCeiling + $this->WalletDepositsCeiling +
            $this->WalletIntersCeiling +
            $this->WalletPaymentsCeiling +
            $this->WalletTransactionsCeiling +
            $this->WalletPhoneCreditsCeiling +
            $this->WalletReloadsCeiling + $this->WalletBankCardCeiling;

        return  $somm_valeur;
    }

    // Verify ceilling

    public function getWalletBanksCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_banks.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_banks.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_banks.*')
            ->where('wallet_banks.status', 'SUCCESS')
            ->sum('wallet_banks.amount');
    }

    public function getWalletDepositsCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletDeposit::join(
            'wallets',
            'wallet_deposits.wallet_id',
            'wallets.id'
            )
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_deposits.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_deposits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_deposits.*')
            ->where('wallet_deposits.status', 'SUCCESS')
            ->sum('wallet_deposits.amount');
    }

    public function getWalletIntersCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletInter::join(
            'wallets',
            'wallet_inters.wallet_id',
            'wallets.id'
            )
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_inters.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_inters.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_inters.*')
            ->where('wallet_inters.status', 'SUCCESS')
            ->sum('wallet_inters.amount');
    }

    public function getWalletPaymentsCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletPayment::join(
            'wallets',
            'wallet_payments.wallet_id',
            'wallets.id'
            )
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_payments.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_payments.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_payments.*')
            ->where('wallet_payments.status', 'SUCCESS')
            ->sum('wallet_payments.amount');
    }

    public function getWalletTransactionsCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletTransaction::join(
            'wallets',
            'wallet_transactions.wallet_id',
            'wallets.id'
            )
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_transactions.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_transactions.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_transactions.*')
            ->where('wallet_transactions.status', 'SUCCESS')
            ->sum('wallet_transactions.amount');
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletPhoneCredit::join(
            'wallets',
            'wallet_phone_credits.wallet_id',
            'wallets.id'
            )
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )
            ->where(
            'wallet_phone_credits.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_phone_credits.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_phone_credits.*')
            ->where('wallet_phone_credits.status', 'SUCCESS')
            ->sum('wallet_phone_credits.amount');
    }

    public function getWalletBankCardCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return BankCard::where('created_by', sabdy()->id)
            ->whereBetween('created_at', [Carbon::today(), Carbon::today()->endOfDay()])->whereStatus(1)
            ->sum('amount');
    }

    public function getWalletReloadsCeilingProperty()
    {
        // Somme calculée par la base au lieu de charger toutes les lignes en mémoire
        return WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
            ->where(
            'wallets.country_id',
            sabdy()->country->id
            )->where(
            'wallet_reloads.created_by',
            sabdy()->id
            )
            ->whereBetween('wallet_reloads.created_at', [Carbon::today(), Carbon::today()->endOfDay()])
            ->select('wallet_reloads.*')
            ->where('wallet_reloads.status', 'SUCCESS')
            ->sum('wallet_reloads.amount');
    }


    public function getAdminDrawalCIVProperty()
    {

        $admin_drawal =  SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->whereId(1)
            ->where('country_id', 107)
            ->first();

        return $admin_drawal;
    }

    public function getAdminValeurProperty()
    {

        $admins = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', sabdy()->country->id)
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
}
