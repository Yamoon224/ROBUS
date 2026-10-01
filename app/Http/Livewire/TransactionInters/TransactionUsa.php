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

class TransactionUsa extends Component
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

                if (in_array($this->provider_inter_out_civ, [48, 49, 50, 55, 71, 72, 73, 74, 75, 76, 77, 78])) {

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
        return view('livewire.transaction-inters.transaction-europe', [
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
                ->whereIn('name_groupe', array('AFL', 'CIV', 'AFC', 'USA'))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_civ' => Provider::whereIn('id', array(49, 50, 55, 48, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_civ_out' => Provider::whereIn('id', array(49, 50, 48))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_benin' => Provider::whereIn('id', array(50, 48))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_mali' => Provider::whereIn('id', array(50, 49, 55, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_bf' => Provider::whereIn('id', array(49, 50, 51, 55))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_togo' => Provider::whereIn('id', array(50, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_senegal' => Provider::whereIn('id', array(55, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_ghana' => Provider::whereIn('id', array(48, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_niger' => Provider::whereIn('id', array(71, 49, 50, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_west_guinne_kona' => Provider::whereIn('id', array(48, 49, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_europe' => Provider::whereIn('id', array(51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_usa' => Provider::whereIn('id', array(51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_gabon' => Provider::whereIn('id', array(71, 72, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_cameroun' => Provider::whereIn('id', array(48, 49, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_congo' => Provider::whereIn('id', array(48, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_rdc' => Provider::whereIn('id', array(71, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_liberia' => Provider::whereIn('id', array(48, 49, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_tchad' => Provider::whereIn('id', array(71, 51))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_centre_afrique' => Provider::whereIn('id', array(51, 49, 78))
                ->orderBy('name', 'asc')
                ->get(),
            'provide_serialionne' => Provider::whereIn('id', array(51, 49, 74, 75))
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

        if (sabdy()->country->name_groupe == 'USA') {

            $countriesLevels = [
                // Is USA
                1 => [
                    35 => ($provider_valeur != 51) ? 248 : 241, // Benin
                    36 => ($provider_valeur != 51) ? 249 : 242, // BF
                    133 => ($provider_valeur != 51) ? 570 : 243, // Mali
                    214 => ($provider_valeur != 51) ? 250 : 244, // Togo
                    188 => ($provider_valeur != 51) ? 251 : 245, // Senegal
                    83 => ($provider_valeur != 51) ? 252 : 571, // Ghana
                    157 => ($provider_valeur != 51) ? 253 : 246, // Niger
                    91 => ($provider_valeur != 51) ? 35 : 71, // Guinne conakry
                    107 => ($provider_valeur != 51) ? 79 : 107, // CIV
                    // 1 => ($provider_valeur != 51) ? 255 : 234, // USA
                    218 => ($provider_valeur == 51) ? 554 : null, // Tunisia
                    146 => ($provider_valeur == 51) ? 555 : null, // Maroc
                    199 => ($provider_valeur == 51) ? 547 : null, // ESPAGNE
                    74 => ($provider_valeur == 51) ? 548 : null, // France
                    179 => ($provider_valeur == 51) ? 553 : null, // Russie
                    226 => ($provider_valeur == 51) ? 551 : null, // Royaume Unie
                    106 => ($provider_valeur == 51) ? 552 : null, // Italie
                    82 => ($provider_valeur == 51) ? 550 : null, // Allemagne
                    39 => ($provider_valeur != 51) ? 255 : 234, // Cameroun
                    79 => ($provider_valeur != 51) ? 256 : 235, // Gabon
                    50 => ($provider_valeur != 51) ? 570 : 243, // COngo Braza
                    57 => ($provider_valeur != 51) ? 258 : 237, // RDC
                    122 => ($provider_valeur != 51) ? 238 : 259, // Liberia
                    43 => ($provider_valeur != 51) ? 260 : 239, // Thacd
                    // 43 => ($provider_valeur != 51) ? 77 : 105, // Thacd
                    42 => ($provider_valeur != 51) ? 261 : 240, // Afrique centrale
                ],
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

            //  Admin send commission
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

                $message =  'Vous avez effectué un paiement de ' . $walletInter->amount .  sabdy()->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletInter->provider->name .
                    ' a ' . $walletInter->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                $encode = substr(sabdy()->phone, -10);

                Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
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

                $message =  'Vous avez effectué un paiement de ' . $walletInter->amount .  sabdy()->wallet->currencie->code .
                    ' ' .
                    'par ' . $walletInter->provider->name .
                    ' a ' . $walletInter->created_at .
                    ' votre demande est en-cours de validation ' .
                    'Robus vous remercie';

                $encode = substr(sabdy()->phone, -10);

                Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
            }
            // Percent admin 
            $valeur_percent_admin  = $percent_robus->percent_admin * $this->amount_inter_out_civ;

            if ($country_civ->currencie->value > sabdy()->country->currencie->value) {

                //   Convertion  in CFA
                $admin_valeur_convertion =   (float)  $valeur_percent_admin *   (float) sabdy()->country->currencie->value;
            } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {

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
        $wallet_search = Wallet::where('wallet_id', $this->phone_inter_out_civ)
            ->where('country_id', $country_robus->id)
            //   ->where('status', true)
            ->first();

        if ($this->phone_inter_out_civ == sabdy()->wallet->wallet_id || $wallet_search->sidUser->is_custumers == false) {
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
        $data = [];
        foreach (
            WalletBank::join('wallets', 'wallet_banks.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )
                ->where(
                    'wallet_banks.created_by',
                    sabdy()->id
                )
                ->whereDate('wallet_banks.created_at', Carbon::today())
                ->select('wallet_banks.*')
                ->where('wallet_banks.status', 'SUCCESS')
                ->get()
            as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletDeposit::join(
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
                ->whereDate('wallet_deposits.created_at', Carbon::today())
                ->select('wallet_deposits.*')
                ->where('wallet_deposits.status', 'SUCCESS')
                ->get()
            as $deposit
        ) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (
            WalletInter::join(
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
                ->whereDate('wallet_inters.created_at', Carbon::today())
                ->select('wallet_inters.*')
                ->where('wallet_inters.status', 'SUCCESS')
                ->get()
            as $inter
        ) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletPayment::join(
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
                ->whereDate('wallet_payments.created_at', Carbon::today())
                ->select('wallet_payments.*')
                ->where('wallet_payments.status', 'SUCCESS')
                ->get()
            as $payment
        ) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
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
                    sabdy()->country->id
                )
                ->where(
                    'wallet_transactions.created_by',
                    sabdy()->id
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

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletPhoneCredit::join(
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
                ->whereDate('wallet_phone_credits.created_at', Carbon::today())
                ->select('wallet_phone_credits.*')
                ->where('wallet_phone_credits.status', 'SUCCESS')
                ->get()
            as $phone
        ) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (
            BankCard::where('created_by', sabdy()->id)
                ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletReload::join('wallets', 'wallet_reloads.wallet_id', 'wallets.id')
                ->where(
                    'wallets.country_id',
                    sabdy()->country->id
                )->where(
                    'wallet_reloads.created_by',
                    sabdy()->id
                )
                ->whereDate('wallet_reloads.created_at', Carbon::today())
                ->select('wallet_reloads.*')
                ->where('wallet_reloads.status', 'SUCCESS')
                ->get()
            as $reload
        ) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
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
