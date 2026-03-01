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
use Illuminate\Support\Facades\Storage;

class TransfertDjamo extends Component
{

    use WithFileUploads;
    public $stepDjamo = 1;
    public $choose_transaction;

    public $image;
    public $amount_djamo;
    public $full_name_djamo;
    public $image_preview;
    public $operators;
    public $operatorsCompagnie;
    // Compagnies
    public $showRemonterDjamoCButton = false;
    public $operatorsTransaction;
    public $isPasswordValid = false;
    public $showRemonterDjamoButton = false;
    public $password_validated = '';
    // Phone credit
    public $commission_transaction_val_nat_djamo_pay;
    public $commission_transaction_val_admin_djamo_receive;
    public $commission_transaction_val_nat_djamo_receive;
    public $commission_transaction_drawal;
    public $need_identity_number = false;
    public $identity_number;

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
            'Active Djamo' => 'showRemonterDjamoButton'
        ];

        $restrictionCompangieMap = [
            'Active Djamo' => 'showRemonterDjamoCButton'
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

    public function updated($property, $value)
    {
        if ($property === 'amount_djamo') {
            $amount = (int) $value;
            $this->need_identity_number = $amount >= 100000 && $amount <= 200000;
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

    public function addDjamo()
    {

        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId(104)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $som_transfers = app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();


        $this->validate(
            [
                'image' => 'required|image|mimes:png,jpg,jpeg|max:102400',
                'full_name_djamo' => 'required',
                'amount_djamo' => 'required',
                'choose_transaction' => 'required',

            ],
            [
                'image.required' =>
                'Image est obligatoire',
                'full_name_djamo.required' =>
                'Nom & prénom sont obligatoire',
                'amount_djamo.required' =>
                'Montant est obligatoire',
                'choose_transaction.required' => 'Le choix est obligatoire est obligatoire',

            ]
        );


        try {

            if ($provider) {


                if ($walletUser->country->code != 'CI') {
                    $this->resetDjamoField();
                    $this->emit(
                        'warning',
                        'Cette operation est uniquement pour la CIV !'
                    );
                    return;
                }
                if ($som_transfers >= $valeur_ceiling) {

                    $this->resetDjamoField();
                    $this->emit(
                        'warning',
                        'Votre montant journier est atteint !'
                    );
                    return;
                }

                $reel_amount = $this->search_amount($this->amount_djamo);

                $val_amount = $reel_amount;

                if ($this->choose_transaction == 1) {
                    if ($val_amount > $walletUser->wallet->amount) {
                        $this->resetDjamoField();
                        $this->emit(
                            'error',
                            'Verifier votre montant  svp !'
                        );
                        return;
                    }
                }

                $this->stepDjamo = 2;
            } else {
                $this->emit('error', 'Rechargement  existante !');
            }
        } catch (\Exception $exception) {
            $this->resetDjamoField();
            $this->emit('error', 'Error de message demande a l\'admin !');
        }
    }

    public function addValidatedDjamo()
    {
        $walletUser = Auth::guard('web')->user();
        $provider = Provider::whereId(104)->first();
        $valeur_ceiling = $walletUser->silver_ceiling;
        $admins = app(TotalAmoutUserServiveBackend::class)->getAdminValeurProperty();
        $admin_civ = app(TotalAmoutUserServiveBackend::class)->getAdminCIVProperty();
        $som_transfers = app(TotalAmoutUserServiveBackend::class)->getSumValTransactionProperty();

        try {
            if (!$provider) {
                $this->emit('error', 'Rechargement existant !');
                return;
            }

            $this->validate([
                'password_validated' => 'required',
                'image' => 'required|image|mimes:png,jpg,jpeg|max:102400',
                'full_name_djamo' => 'required',
                'amount_djamo' => 'required',
                'choose_transaction' => 'required',
            ], [
                'password_validated.required' => 'Mot de passe est obligatoire',
                'image.required' => 'Numero est obligatoire',
                'full_name_djamo.required' => 'Nom & prénom sont obligatoire',
                'amount_djamo.required' => 'Montant est obligatoire',
                'choose_transaction.required' => 'Le choix est obligatoire est obligatoire',
            ]);

            if (!Hash::check($this->password_validated, sabdy()->password)) {
                $this->resetDjamoField();
                $this->emit('error', 'Vérifiez votre mot de passe SVP !');
                return;
            }

            if ($walletUser->country->code != 'CI') {
                $this->resetDjamoField();
                $this->emit(
                    'warning',
                    'Ce réchargement est uniquement pour la CIV !'
                );
                return;
            }

            if ($som_transfers >= $valeur_ceiling) {
                $this->resetDjamoField();
                $this->emit('warning', 'Votre montant journier est atteint !');
                return;
            }

            $reel_amount = $this->search_amount($this->amount_djamo);
            $percent_robus = app(CommissionServicePercentBackend::class)->getRobusPercentageDjamo();
            $val_amount = $reel_amount;

            if ($this->choose_transaction == 1) {
                if ($val_amount > $walletUser->wallet->amount) {
                    $this->resetDjamoField();
                    $this->emit(
                        'error',
                        'Vérifiez votre montant SVP !'
                    );
                    return;
                }
            }

            $amount_solde = $walletUser->wallet->amount - $val_amount;

            // ✅ CHOISIR UNE SEULE MÉTHODE :
            $customDirectory = public_path('qr_djamos');
            // Déplacer le fichier vers public/qr_djamos
            $fileInfo = $this->moveUploadedFileToCustomDirectory(
                $this->image,
                $customDirectory,
                false // Ne pas garder dans storage
            );

            // Utiliser le chemin dans public/
            $savedPath = 'qr_djamos/' . $fileInfo['filename'];

            if ($walletUser->is_distributer == 1 || $walletUser->is_admin || $walletUser->is_under_distributer) {
                $amountReceved = $reel_amount;

                $walletDjamo = app(TransfertServiceBackend::class)->createDjamoTransaction(
                    $val_amount,
                    $provider,
                    $percent_robus,
                    $amount_solde,
                    $this->full_name_djamo,
                    $savedPath,
                    $amountReceved,
                    $this->identity_number,
                    $this->choose_transaction
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

                if ($walletDjamo != null && $walletDjamo->status == 'PENDING') {
                    $this->updateamountDjamoReceveid($admin_civ, $percent_robus, $walletDjamo, $walletUser, $reel_amount);
                }

                event(new CreateWalletDjamoEvent($walletDjamo));

                app(NotificationServiceBackend::class)->notifyCreateUsers($walletUser, $admins, 'Réchargement Push', 'Demande de rechargement push est en cours de validation', 'Demande de paiement de ' . ' ' . $walletDjamo->wallet->sidUser->firstname);

                if ($admins->count() > 0) {
                    app(NotificationServiceBackend::class)->notifyAdmins($admins);
                }

                $this->resetDjamoField();
                $this->emit('success', 'Votre demande de transaction a bien été prise en compte !');
                if ($walletUser->email != null) {
                    app(NotificationServiceBackend::class)->sendMailPushNotification($walletDjamo);
                }
            } else {
                $this->resetDjamoField();
                $this->emit('success', 'Désolé vous n\'êtes pas autorisé !');
                return;
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->resetDjamoField();
            $this->emit('error', 'Erreur: ' . $exception->getMessage());
        }
    }

    private function moveUploadedFileToCustomDirectory($uploadedFile, $customDirectory, $keepInStorage = false)
    {
        // S'assurer que le dossier existe
        if (!file_exists($customDirectory)) {
            // Créer le dossier avec les bonnes permissions
            $oldMask = umask(0);
            @mkdir($customDirectory, 0775, true);
            umask($oldMask);
        }

        // Vérifier que le dossier est accessible en écriture
        if (!is_writable($customDirectory)) {
            @chmod($customDirectory, 0775);

            // Si toujours pas accessible, utiliser storage à la place
            if (!is_writable($customDirectory)) {
                return $this->saveToStorageOnly($uploadedFile);
            }
        }

        $originalName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        // Nettoyer le nom de fichier
        $cleanName = preg_replace('/[^\w\s.-]/', '', $originalName);
        $cleanName = str_replace(['’', "'", '"', '`'], '', $cleanName); // Enlever guillemets
        $filename = pathinfo($cleanName, PATHINFO_FILENAME) . '_' .
            date('YmdHis') . '_' . uniqid() . '.' . $extension;
        // Remplacer les espaces par des underscores
        $filename = str_replace(' ', '_', $filename);
        // Chemin complet
        $customPath = $customDirectory . '/' . $filename;
        // Déplacer le fichier avec gestion d'erreur
        try {
            $uploadedFile->move($customDirectory, $filename);
        } catch (\Exception $e) {
            // Si échec, utiliser copy() à la place
            $tempPath = $uploadedFile->getRealPath();
            if (copy($tempPath, $customPath)) {
                unlink($tempPath);
            } else {
                throw new \Exception("Impossible de déplacer le fichier: " . $e->getMessage());
            }
        }

        $result = [
            'custom_path' => $customPath,
            'filename' => $filename,
            'storage_path' => null
        ];

        // Optionnel : garder une copie dans le storage Laravel
        if ($keepInStorage) {
            $userFolder = 'qr_djamos/' . sabdy()->id;

            // Copier depuis le chemin public vers storage
            $storagePath = $userFolder . '/' . $filename;
            Storage::disk('public')->put($storagePath, file_get_contents($customPath));

            $result['storage_path'] = $storagePath;
        }

        return $result;
    }

    private function saveToStorageOnly($uploadedFile)
    {
        // Sauvegarder uniquement dans le storage Laravel
        $filename = date('YmdHis') . '_' . uniqid() . '.' .
            $uploadedFile->getClientOriginalExtension();

        $path = $uploadedFile->storeAs('qr_djamos', $filename, 'public');

        return [
            'custom_path' => storage_path('app/public/' . $path),
            'filename' => $filename,
            'storage_path' => $path
        ];
    }

    private function saveFileBothLocations($uploadedFile)
    {
        // Sauvegarder d'abord dans storage
        $filename = date('YmdHis') . '_' . uniqid() . '.' .
            $uploadedFile->getClientOriginalExtension();

        // 1. Dans storage
        $storagePath = $uploadedFile->storeAs('qr_djamos', $filename, 'public');

        // 2. Copier vers public/
        $publicDir = public_path('qr_djamos');
        if (!file_exists($publicDir)) {
            @mkdir($publicDir, 0775, true);
        }

        $publicPath = $publicDir . '/' . $filename;
        copy(storage_path('app/public/' . $storagePath), $publicPath);

        return [
            'storage_path' => $storagePath,
            'public_path' => 'qr_djamos/' . $filename,
            'filename' => $filename
        ];
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


        if ($this->amount_djamo) {

            $reel_amount = $this->search_amount($this->amount_djamo);

            if ($reel_amount != '') {

                $commission_transaction = app(CommissionServicePercentBackend::class)->getRobusPercentageDjamo();

                if ($this->choose_transaction == 1) {
                    $commission_percent_transaction = (float) $commission_transaction->percent * $reel_amount;
                    $this->commission_transaction_val_admin_djamo_receive = (float) $commission_percent_transaction;
                    $this->commission_transaction_val_nat_djamo_receive = (float) $reel_amount;
                    $this->commission_transaction_val_nat_djamo_pay = (float) $commission_transaction->percent_merchands * $reel_amount;
                } else {
                    $commission_percent_transaction = (float) $commission_transaction->percent * $reel_amount;
                    $this->commission_transaction_val_admin_djamo_receive = (float) $commission_percent_transaction;
                    $this->commission_transaction_val_nat_djamo_receive = (float) $reel_amount;
                    $this->commission_transaction_val_nat_djamo_pay = (float) $commission_transaction->percent_with_drawal * $reel_amount;
                }
            }
        }
    }

    public function updatedAmountDjamo()
    {
        $this->calculateAmountDjamos();
    }


    public function resetDjamoField()
    {
        $this->image = null;
        $this->image_preview = null;
        usleep(100000);
        $this->amount_djamo = '';
        $this->full_name_djamo = '';
        $this->stepDjamo = 1;
        $this->choose_transaction = null;

        $this->emit('modal-hide-djamo');
        $this->emit('menu-djamo-active');
    }

    public function render()
    {
        return view(
            'livewire.transferts.transfert-djamo',
            [
                'showRemonterDjamoButton' => $this->showRemonterDjamoButton,
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


        $percent = ($this->choose_transaction == 1)
            ? $percent_robus->percent_merchands
            : $percent_robus->percent_with_drawal_merchands;

        $percent_compagnie =  ($this->choose_transaction == 1)
            ? $percent_robus->percent_with_drawal_companie
            : $percent_robus->percent_companie;


        $valuer_deposit = (float) $percent * $amount;

        $amount_solde = sabdy()->wallet->amount - $amount;

        $walletDjamo->update([
            'amount_solde' => $amount_solde,
        ]);

        app(TransfertServiceBackend::class)->updatePaiementWallet($walletUser, $amount);

        if (sabdy()->is_under_distributer) {

            if ($this->showRemonterDjamoButton) {

                $valuer_companie = $percent_compagnie * $amount;

                $walletDjamo->increment('commissions', $valuer_deposit);

                $account_accepted = sabdy()->is_accepted_companie ?? false;

                if ($account_accepted && $this->showRemonterDjamoCButton) {
                    $walletDjamo->increment('commission_companie', $valuer_companie);

                    app(CommissionServiceBackend::class)->distributeUnderCommissionAcceptedCompanies($admin_civ, 'wallet_djamo_id', $walletDjamo->id, $valuer_companie, $valuer_deposit);
                } else {
                    app(CommissionServiceBackend::class)->distributeAdminCommissionUnders($admin_civ, 'wallet_djamo_id', $walletDjamo->id, $valuer_deposit);
                }
            }
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
