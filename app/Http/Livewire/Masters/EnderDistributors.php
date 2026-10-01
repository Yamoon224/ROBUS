<?php

namespace App\Http\Livewire\Masters;

use Livewire\Component;
use App\Models\Sid\SidUser;
use Livewire\WithPagination;
use App\Models\Wallets\Wallet;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use App\Mail\NewAdmin;
use Illuminate\Support\Facades\Mail;
use App\Notifications\AdminNotification;
use Illuminate\Support\Facades\Notification;


class EnderDistributors extends Component
{

    use WithPagination;

    public $search = "";
    public $countriesFilter = "";
    public $statusFilter_piece = "";
    public $statusFilter_active = "";

    public $wallet_id;
    public $user_id;
    public $categorieId;
    public $underdistributeurDesabled;
    public $amount_payment;
    public $choose_type_payement_emp;
    public $wallet_id_underdistributor;
    public $amount_payment_wallet;
    public $wallet_id_add;
    public $transaction_description_robus;
    public $wallet_search;

    public function add_under_distributors()
    {
        $admins = $this->getAdminValeurProperty();

        $this->validate([
            'wallet_id_add' => 'required',
        ], [
            'wallet_id_add.required' => 'Votre wallet est obligatoire',

        ]);

        $check = Wallet::where('wallet_id', $this->wallet_id_add)->first();


        if ($check) {


            if ($check->sidUser->id == master()->id || $check->sidUser->is_company || $check->sidUser->is_master || $check->sidUser->is_admin  || $check->sidUser->is_supervisor || $check->sidUser->is_distributer) {

                $this->emit('modal-hide', 'hide');
                $this->cancelUndersField();
                $this->emit('info', 'Desolé Ce wallet est pour vous  ou il fais partie de votre groupe !!!');

            } else {


                // Update Users
                $check->sidUser->update(
                    [
                        'master_id' => master()->id,
                        'is_accepted'  => false,
                    ]
                );

                // Message admin
                Notification::send($admins, new AdminNotification('Demande de liaison sous distributeur'));

                if ($admins->count() > 0) {

                    foreach ($admins  as $user) {

                        $admin_single = SidUser::find($user->id);

                        $notifs = $admin_single->unreadNotifications;
                        event(new AdminNotifEvent($notifs));
                    }
                }

                if (master()->email != null) {
                    Mail::to(master()->email)->send(new NewAdmin("Vous avez fais une demande de liaison du sous distributeur "  . $check->sidUser->firstname  . ' ' .  $check->sidUser->firstname . env('APP_NAME')));
                }
                $this->emit('modal-hide', 'hide');
                $this->cancelUndersField();
                $this->emit('success', 'Sous distributeur ajouté !');
            }
        } else {
            $this->emit('modal-hide', 'hide');
            $this->cancelUndersField();
            $this->emit('error', 'Numero Inconnu chez ROBUS !');
        }
    }

    // Admin valeur function
    public function getAdminValeurProperty()
    {

        $admins = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->get();

        return  $admins;
    }




    public function edit($id, $state)
    {
        $check = Wallet::whereId($id)->first();
        $this->user_id = $check->sidUser->id;
        $this->wallet_id_add = $check->wallet_id;
        $this->underdistributeurDesabled = $check->master_id;
        $this->emit($state);
    }

    public function updatedWalletIdAdd()
    {
        $this->reset('wallet_search');

        if ($this->wallet_id_add) {
            $this->wallet_search =  Wallet::where('wallet_id', $this->wallet_id_add)
                ->where('country_id', master()->country->id)
                ->first();
        }
    }

    public function update()
    {

        $this->validate([
            'wallet_id_add' => 'required',
        ], [
            'wallet_id_add.required' => 'Votre wallet est obligatoire',

        ]);

        $check =  Wallet::where('wallet_id', $this->wallet_id_add)->first();

        $this->cancelUndersField();
        $this->emit('modal-hide', 'hide');
        $this->emit('success', 'Sous distributeur modifié !');
    }

    public function disable()
    {
        $check = SidUser::find($this->user_id);
        if (!empty($check)) {
            if ($check->master_id  == master()->id) $check->update(['master_id' => null]);
            else $check->update(['master_id' => master()->id]);
            $this->emit('modal-hide', 'hide');
            $this->cancelUndersField();
            $this->emit('success', $check->master_id  == master()->id ? 'Sous distributeur activée !' : 'Sous distributeur désactivée !');
        }
    }

    public function cancelUndersField()
    {

        $this->wallet_id_add = '';
        $this->amount_payment = '';
        $this->amount_payment_wallet = '';
        $this->wallet_id_underdistributor = '';
        $this->choose_type_payement_emp = null;
        $this->transaction_description_robus = '';
        $this->emit('modal-hide', 'hide');
    }

    public function render()
    {
        $users = SidUser::query();
        $wallet_info_firstname = null;
        $wallet_info_lastname = null;
        $wallet_info_image = null;

        $wallets = Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('wallets.status', true)
            ->Where('wallets.country_id', master()->country->id)
            ->get();


        if ($this->search != "") {

            $this->resetPage();

            $users->where('master_id', master()->id)->where("firstname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("lastname", "LIKE",  "%" . $this->search . "%")
                ->orWhere("email", "LIKE",  "%" . $this->search . "%")
                ->orWhere("phone", "LIKE",  "%" . $this->search . "%");
        }



        if ($this->wallet_id != '') {


            foreach ($wallets  as $wallet_user) {

                $wallet_search_user = $wallet_user->where('wallet_id', $this->wallet_id)->first();

                if ($wallet_search_user == null) {

                    $wallet_info_firstname = "N'existe pas chez nous";
                } else {

                    $wallet_info_firstname = $wallet_search_user->sidUser->firstname ?? null;

                    $wallet_info_lastname = $wallet_search_user->sidUser->lastname ?? null;

                    $wallet_info_image = $wallet_search_user->sidUser->image ?? "public/dist/images/profil.png";
                }
            }
        }



        return view('livewire.masters.ender-distributors', [
            "users" => $users->where('master_id', master()->id)->orderBy('created_at', 'desc')->latest()->paginate(30),
            "countries" => Country::where('status', true)->orderBy("name", 'desc')->get(),
            "wallet_amount" => Wallet::where('sid_user_id', master()->id)->first(),
            'wallet_info_searchs' => $wallet_info_firstname . '  ' . $wallet_info_lastname,
            'imagePath'   =>   $wallet_info_image
        ]);
    }
}
