<?php

namespace App\Http\Livewire\Transferts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Offer;
use App\Models\OperatorService;
use App\Models\Providers\Provider;
use App\Models\Sid\SidUser;
use App\Http\Services\TotalAmoutUserServiveBackend;
use App\Http\Services\NotificationServiceBackend;
use App\Http\Services\TransfertServiceBackend;
use App\Http\Services\CommissionServiceBackend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\CommissionServicePercentBackend;
use App\Events\CreateWalletDjamoEvent;
use Livewire\WithFileUploads;
use App\Models\Costs\DepositCost;


class TransfertBankSend extends Component
{

    use WithFileUploads;
    public $stepBankSend = 1;
    public $image;

    public $amount_bank;
    public $back_bank;
    public $front_bank;
    public $date_expiry;
    public $full_exp_bank;
    public $full_benf_bank;
    public $phone_bank;
    public $name_bank;
    public $num_accompte_bank;
    public $num_swift_bank;


    public $image_preview;
    public $operators;
    public $operatorsCompagnie;
    // Compagnies
    public $showRemonterBankSendCButton = false;
    public $operatorsTransaction;
    public $isPasswordValid = false;
    public $showRemonterBankSendButton = false;
    public $password_validated = '';
    // Phone credit
    public $commission_transaction_val_nat_djamo_pay;
    public $commission_transaction_val_admin_djamo_receive;
    public $commission_transaction_val_nat_djamo_receive;
    public $commission_transaction_drawal;

    protected $rules = [
        'image' => 'required|image|mimes:png,jpg,jpeg|max:102400',
    ];


    public function mount()
    {

        $this->operators = OperatorService::getOperatorsWithCommissions(sabdy()->id);
        $this->operatorsTransaction = OperatorService::getOperatorsWithTranactions(sabdy()->id);
        $account_accepted = sabdy()->is_accepted_companie ?? false;
        $company_id = sabdy()->company_id ?? null;
        if ($company_id && $company_id != 0 && $company_id != 1) {
            $compagnie = SidUser::whereIsCompany(true)->where('company_id', $company_id)->first();
            $sidUserId = $account_accepted && $compagnie ? $compagnie->id : null;
        } else {
            $compagnie = null;
            $sidUserId = null;
        }
        if ($sidUserId) {
            $this->operatorsCompagnie = OperatorService::getOperatorsWithCommissions($sidUserId);
        } else {
            $this->operatorsCompagnie = [];
        }

        $restrictionMap = [
            'Active Bank Send' => 'showRemonterBankSendButton'
        ];

        $restrictionCompangieMap = [
            'Active Bank Send' => 'showRemonterBankSendCButton'
        ];

        foreach ($this->operators as $operator) {
            $restrictions = explode(',', $operator->name);

            foreach ($restrictionMap as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $this->{$buttonVar} = true;
                }
            }
        }

        foreach ($this->operatorsCompagnie as $operator) {
            $restrictions = explode(',', $operator->name);

            foreach ($restrictionCompangieMap as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $this->{$buttonVar} = true;
                }
            }
        }
    }

    public function checkPassword()
    {

        if (empty($this->password_validated)) {
            return;
        }

        if (Hash::check($this->password_validated, sabdy()->password)) {
            $this->isPasswordValid = true;
        } else {
            $this->isPasswordValid = false;
        }
    }

    public function addBankSend()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId(104)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $som_transfers = app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();


        $this->validate(
            [
                'image'            => 'required|image|mimes:png,jpg,jpeg|max:102400',
                'full_exp_bank'    => 'required',
                'amount_bank'      => 'required',
                'date_expiry'      => 'required',
                'front_bank'       => 'required|image|mimes:png,jpg,jpeg|max:102400',
                'back_bank'        => 'required|image|mimes:png,jpg,jpeg|max:102400',
                'phone_bank'       => 'required',
                'full_benf_bank'   => 'required',
                'name_bank'        => 'required',
                'num_accompte_bank' => 'required',
                'num_swift_bank'   => 'required',
            ],
            [
                'image.required'          => 'Image est obligatoire.',
                'full_exp_bank.required'  => 'Nom & prénom sont obligatoires.',
                'amount_bank.required'    => 'Le montant est obligatoire.',
                'date_expiry.required'    => 'La date d’expiration est obligatoire.',
                'front_bank.required'     => 'L’image recto est obligatoire.',
                'back_bank.required'      => 'L’image verso est obligatoire.',
                'phone_bank.required'     => 'Le numéro de téléphone est obligatoire.',
                'full_benf_bank.required' => 'Le nom du bénéficiaire est obligatoire.',
                'name_bank.required'      => 'Le nom de la banque est obligatoire.',
                'num_accompte_bank.required' => 'Le numéro de compte est obligatoire.',
                'num_swift_bank.required' => 'Le code SWIFT est obligatoire.',
            ]
        );



        try {

            if ($provider) {


                if ($walletUser->country->code != 'CI') {
                    $this->resetBankSendField();
                    $this->emit(
                        'warning',
                        'Cette operation est uniquement pour la CIV !'
                    );
                    return;
                }
                if ($som_transfers >= $valeur_ceiling) {

                    $this->resetBankSendField();
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                    return;
                }

                $reel_amount = $this->search_amount($this->amount_bank);

                $val_amount = $reel_amount;

                if ($val_amount > $walletUser->wallet->amount) {
                    $this->resetBankSendField();
                    $this->emit(
                        'error',
                        'Verifier votre montant  svp !'
                    );
                    return;
                }

                $this->stepBankSend = 2;
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetBankSendField();
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addValidatedBankSend()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId(104)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $admins = app(TotalAmoutUserServiveBackend::class)->getAdminValeurProperty();
        $admin_civ = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        $som_transfers =  app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();

        try {


            if (!$provider) {
                $this->emit('error', 'Rechargement existant !');
                return;
            }

            $percent_robus = DepositCost::whereId(4)->first();

            $this->validate([
                'password_validated' => 'required',
                'image'            => 'required|image|mimes:png,jpg,jpeg|max:102400',
                'full_exp_bank'    => 'required',
                'amount_bank'      => 'required',
                'date_expiry'      => 'required',
                'front_bank'       => 'required|image|mimes:png,jpg,jpeg|max:102400',
                'back_bank'        => 'required|image|mimes:png,jpg,jpeg|max:102400',
                'phone_bank'       => 'required',
                'full_benf_bank'   => 'required',
                'name_bank'        => 'required',
                'num_accompte_bank' => 'required',
                'num_swift_bank'   => 'required',
            ], [
                'password_validated.required' => 'Mot de passe est obligatoire',
                'image.required'          => 'Image est obligatoire.',
                'full_exp_bank.required'  => 'Nom & prénom sont obligatoires.',
                'amount_bank.required'    => 'Le montant est obligatoire.',
                'date_expiry.required'    => 'La date d’expiration est obligatoire.',
                'front_bank.required'     => 'L’image recto est obligatoire.',
                'back_bank.required'      => 'L’image verso est obligatoire.',
                'phone_bank.required'     => 'Le numéro de téléphone est obligatoire.',
                'full_benf_bank.required' => 'Le nom du bénéficiaire est obligatoire.',
                'name_bank.required'      => 'Le nom de la banque est obligatoire.',
                'num_accompte_bank.required' => 'Le numéro de compte est obligatoire.',
                'num_swift_bank.required' => 'Le code SWIFT est obligatoire.',
            ]);

            if (!Hash::check($this->password_validated, sabdy()->password)) {
                $this->resetBankSendField();
                $this->emit('error', 'Vérifiez votre mot de passe SVP !');
                return;
            }


            if ($walletUser->country->code != 'CI') {
                $this->resetBankSendField();
                $this->emit(
                    'warning',
                    'Ce réchargement est uniquement pour la CIV !'
                );
                return;
            }


            if ($som_transfers >= $valeur_ceiling) {
                $this->resetBankSendField();
                $this->emit('warning', 'Votre montant journier est atteint !');
                return;
            }


            if (in_array($this->provider_reloadcard, $this->validReloadCardProviders)) {

                $this->validateReloadCard();

                if ($som_transfers >= $valeur_ceiling) {
                    $this->resetPaiementField();
                    $this->emit('modal-hide-reloadCard');
                    $this->emit('warning', 'Votre montant journier est atteint !');
                    return;
                }


                if (sabdy()->is_under_distributer) {
                    $valeur_first = $percent_robus->levelDepositIvories->where('is_ivoires', true)->first();
                    $valeur_end = $percent_robus->levelDepositIvories->where('is_ivoires', true)->last();
                    $all_valeurs = $percent_robus->levelDepositIvories->where('is_ivoires', true);
                } else {

                    $valeur_first = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->first();
                    $valeur_end = $percent_robus->levelDepositIvories->where('is_inter_bank', true)->last();
                    $all_valeurs = $percent_robus->levelDepositIvories->where('is_inter_bank', true);
                }

                $reel_amount = $this->search_amount($this->amount_reloadcard);

                if ($valeur_end->amount_end < $reel_amount) {
                    $this->resetReloadCardField();
                    $this->emit('modal-hide-reloadCard');
                    $this->emit('info', 'Vous avez depasser le montant max !'  . $$valeur_end->amount_end);
                    return;
                }


                if ($valeur_first->amount_begin > $reel_amount) {
                    $this->resetReloadCardField();
                    $this->emit('modal-hide-reloadCard');
                    $this->emit('info', 'Vous avez depasser le montant min !' . $valeur_first->amount_begin);
                    return;
                }



                foreach ($all_valeurs as $amount_user) {

                    if ($reel_amount >= $amount_user->amount_begin && $reel_amount <= $amount_user->amount_end) {

                        //  If amount is Null

                        if ($amount_user->amount == null) {

                            $percent_valeur = $amount_user->percent * $reel_amount;

                            if (sabdy()->is_under_distributer) {

                                $amount_verify = $reel_amount;
                                $valuer_deposit_com = $amount_user->comission_distributer * $reel_amount;
                                $valuer_admin_com = $percent_valeur - $valuer_deposit_com;
                                $amount_cost = $amount_user->comission_distributer * $reel_amount;
                                $commissionCompanie = $amount_user->percent_companie * $percent_valeur;
                                $amountReceved = (float)$reel_amount;
                            } else {
                                $amount_verify = $reel_amount;
                                $valuer_admin_com =  $amount_user->percent * $reel_amount;
                                $amount_cost = $amount_user->percent * $percent_valeur;
                                $valuer_deposit_com = 0;
                                $amountReceved = (float)$reel_amount - (float)$percent_valeur;
                            }
                        } else {

                            $percent_valeur = (float)$amount_user->amount + (float)$reel_amount;

                            if (sabdy()->is_under_distributer) {

                                $amount_verify =  (float)$reel_amount;
                                $valuer_deposit_com = $amount_user->comission_distributer;
                                $valuer_admin_com = $reel_amount - $valuer_deposit_com;
                                $amount_cost = $amount_user->comission_distributer;
                                $commissionCompanie = $amount_user->percent_companie;
                                $amountReceved = (float)$reel_amount;
                            } else {
                                $amount_verify = (float)$reel_amount;
                                $amount_cost = $amount_user->amount;
                                $valuer_deposit_com = 0;
                                $valuer_admin_com = (float)$amount_user->amount;
                                $amountReceved = (float)$reel_amount - (float)$amount_user->amount;
                            }
                        }


                        $amount_solde =  $walletUser->wallet->amount -  $amount_verify;

                        $soldeWallet = $walletUser->wallet->amount;

                        if ($amount_verify > $soldeWallet) {

                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit(
                                'error',
                                'Verifier votre montant  svp ou le montant minimum !'
                            );
                            return;
                        }

                        if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                            $walletReloadCard = app(TransfertServiceBackend::class)->createNewReloadCard(
                                $amount_solde,
                                $walletUser,
                                $reel_amount,
                                $this->reloadcard_description,
                                $this->account_number_reload_card,
                                $this->name_reloadcard,
                                $amount_cost,
                                $provider,
                                $amountReceved,
                            );


                            $message = 'Vous avez effectué un rechargement  de ' .
                                $walletReloadCard->amount .
                                $walletUser->wallet->currencie->code .
                                ' ' .
                                'par ' .
                                $walletReloadCard->provider->name .
                                ' le ' .
                                $walletReloadCard->created_at .
                                ' votre demande est en cours de validation ' .
                                'Robus vous remercie';

                            app(NotificationServiceBackend::class)->sendSuccessMessageCreateNotification($walletUser, $message);


                            if ($walletReloadCard != null && $walletReloadCard->status != 'PENDING') {
                                $this->updateamountReloadCardReceveid($admin_civ, $valuer_deposit_com, $walletReloadCard, $valuer_admin_com, $walletUser, $amount_verify, $commissionCompanie);
                            }

                            event(new CreateWalletReloadCardEvent($walletReloadCard));

                            app(NotificationServiceBackend::class)->notifyCreateUsers($walletUser, $admins, 'Rechargement de carte', 'Demande de Rechargement de carte est en cours de validation', 'Demande de Rechargement de carte de ' . ' ' . $walletReloadCard->wallet->sidUser->firstname);

                            if ($admins->count() > 0) {
                                app(NotificationServiceBackend::class)->notifyAdmins($admins);
                            }

                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit(
                                'success',
                                'Votre demande de transaction a bien été prise en compte.'
                            );

                            if ($walletUser->email != null) {
                                app(NotificationServiceBackend::class)->sendMailReloadCardNotification($walletReloadCard);
                            }
                        } else {

                            $this->resetReloadCardField();
                            $this->emit('modal-hide-reloadCard');
                            $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                            return; // Arrête l'exécution si le montant est supérieur au solde
                        }
                    }
                }
            } else {
                $this->emit('error', 'Aucune selection !');
            }

            $reel_amount = $this->search_amount($this->amount_bank);
            $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageDjamo();

            $val_amount = $reel_amount;

            if ($val_amount > $walletUser->wallet->amount) {
                $this->resetBankSendField();
                $this->emit('error', 'Vérifier votre montant SVP !');
                return;
            }

            $amount_solde =  $walletUser->wallet->amount -  $val_amount;

            $userFolder = 'transactions/' . sabdy()->firstname;
            $filename = date('YmdHis') . '_' . uniqid() . '.' . $this->image->getClientOriginalExtension();
            $savedPath = $this->image->storeAs($userFolder, $filename, 'public');


            if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {

                $amountReceved = $reel_amount;

                $walletDjamo =  app(TransfertServiceBackend::class)->createDjamoTransaction(
                    $val_amount,
                    $provider,
                    $percent_robus,
                    $amount_solde,
                    $this->full_exp_bank,
                    $savedPath,
                    $amountReceved
                );

                $message =
                    'Vous avez effectué un rechargement de carte Djamo de ' .
                    $walletDjamo->amount .
                    $walletUser->wallet->currencie->code .
                    ' ' .
                    'par ' .
                    $walletDjamo->provider->name .
                    ' le ' .
                    $walletDjamo->created_at .
                    ' votre demande est en cours de validation ' .
                    'Robus vous remercie';


                app(NotificationServiceBackend::class)->sendSuccessMessageCreateNotification($walletUser, $message);

                if ($walletDjamo != null  && $walletDjamo->status == 'PENDING') {
                    $this->updateamountDjamoReceveid($admin_civ, $percent_robus, $walletDjamo,  $walletUser, $reel_amount);
                }

                event(new CreateWalletDjamoEvent($walletDjamo));

                app(NotificationServiceBackend::class)->notifyCreateUsers($walletUser, $admins, 'Réchargement Push', 'Demande de rechargement push est en cours de validation', 'Demande de paiement de ' . ' ' . $walletDjamo->wallet->sidUser->firstname);

                if ($admins->count() > 0) {
                    app(NotificationServiceBackend::class)->notifyAdmins($admins);
                }

                $this->resetBankSendField();
                $this->emit('success', 'Votre demande de transaction a bien été prise en compte !');
                if ($walletUser->email != null) {
                    app(NotificationServiceBackend::class)->sendMailPushNotification($walletDjamo);
                }
            } else {
                $this->resetBankSendField();
                $this->emit('success', 'Desolez vous n\'etes pas autorisée !');
                return;
            }
            DB::commit();
        } catch (\Exception $exception) {
            $this->resetBankSendField();
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }


    public function updatedImage()
    {

        $this->validateOnly('image');

        if ($this->image) {
            $this->image_preview = $this->createImagePreview();
        }
    }

    private function createImagePreview()
    {
        try {
            $path = $this->image->getRealPath();
            $type = $this->image->getClientOriginalExtension();
            $data = file_get_contents($path);

            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        } catch (\Exception $e) {
            return null;
        }
    }


    public function calculateAmountDjamos()
    {

        $this->commission_transaction_val_admin_djamo_receive = 0;
        $this->commission_transaction_val_nat_djamo_receive = 0;
        $this->commission_transaction_val_nat_djamo_pay = 0;


        if ($this->amount_bank) {

            $reel_amount = $this->search_amount($this->amount_bank);

            if ($reel_amount != '') {

                $commission_transaction = app(CommissionServicePercentBackend::class)->getRobusPercentageDjamo();

                $commission_percent_transaction = (float) $commission_transaction->percent * $reel_amount;
                $this->commission_transaction_val_admin_djamo_receive = (float) $commission_percent_transaction;
                $this->commission_transaction_val_nat_djamo_receive = (float) $reel_amount;
                $this->commission_transaction_val_nat_djamo_pay = (float) $commission_transaction->percent_merchands * $reel_amount;
            }
        }
    }

    public function updatedAmountDjamo()
    {
        $this->calculateAmountDjamos();
    }


    public function resetBankSendField()
    {
        $this->image = null;
        $this->image_preview = null;
        usleep(100000);
        $this->amount_bank = '';
        $this->full_exp_bank = '';
        $this->stepBankSend = 1;
        $this->emit('modal-hide-djamo');
        $this->emit('menu-djamo-active');
    }

    public function render()
    {
        return view(
            'livewire.transferts.transfert-bank-send',
            [
                'showRemonterBankSendButton' => $this->showRemonterBankSendButton,
                'countrie_number_local' => Auth::guard('web')->user()->country
                    ->indecative,
                'amount_transaction_nat_val_djamo_percent' => $this->commission_transaction_val_nat_djamo_pay,
                'amount_transaction_nat_val_djamo_receive' => $this->commission_transaction_val_nat_djamo_receive,
                'amount_transaction_nat_val_djamo_commission' => $this->commission_transaction_val_admin_djamo_receive,
                'usersCountry' => sabdy()->wallet->currencie->code,
            ]
        );
    }

    public function updateamountDjamoReceveid($admin_civ, $percent_robus, $walletDjamo,  $walletUser, $amount)
    {

        $amount_percent = $percent_robus->percent * $amount;

        $valuer_deposit = (float) $percent_robus->percent_merchands * $amount;

        $amount_commission = $amount + ($percent_robus->percent * $amount);

        $amount_solde = sabdy()->wallet->amount - $amount;

        $walletDjamo->update([
            'amount_solde' => $amount_solde,
        ]);

        app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount);

        if (sabdy()->is_under_distributer) {

            if ($this->showRemonterBankSendButton) {

                $valuer_companie = $percent_robus->percent_companie * $amount;

                $cumul_valeurs = (float) $valuer_companie + (float) $valuer_deposit;

                $adminAmount = (float) $amount_percent - (float) $cumul_valeurs;

                $walletDjamo->increment('commissions', $valuer_deposit);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterBankSendCButton) {
                    $walletDjamo->increment('commission_companie', $valuer_companie);

                    $totalCommission =  $valuer_companie + $valuer_deposit;

                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAcceptedCompanies($admin_civ, 'wallet_djamo_id', $walletDjamo->id, $totalCommission, $adminAmount);
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminNoCommissionAccepteds($admin_civ, 'wallet_djamo_id', $walletDjamo->id, $adminAmount, $valuer_deposit);
                }
            } else {

                app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_djamo_id', $walletDjamo->id, $amount_percent);
            }
        } else {

            app(CommissionServiceBackend::class)->distributeAdminNoCommissions($admin_civ, 'wallet_djamo_id', $walletDjamo->id, $amount_percent);
        }


        return true;
    }

    public function search_amount($amount_received)
    {

        $reel_amount = str_replace(['.', ','], '', $amount_received);

        $reel_amount = (float) $reel_amount;

        return $reel_amount;
    }
}
