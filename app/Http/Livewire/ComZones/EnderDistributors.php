<?php

namespace App\Http\Livewire\ComZones;

use App\Events\AdminNotifEvent;
use App\Mail\NewAdmin;
use App\Mail\UvSend;
use App\Models\Custumers\Country;
use App\Models\Operator;
use App\Models\OperatorService;
use App\Models\Sid\SidUser;
use App\Models\Wallets\Wallet;
use App\Notifications\AdminNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use Livewire\WithPagination;

class EnderDistributors extends Component
{


    use WithPagination;

    public $search = "";
    public $countriesFilter = "";
    public $statusFilter_piece = "";
    public $statusFilter_active = "";

    public $wallet_id;
    public $user_id;
    public $underdistributeurDesabled;
    public $amount_payment;
    public $choose_type_payement_emp;
    public $wallet_id_underdistributor;
    public $amount_payment_wallet;
    public $wallet_id_add;
    public $transaction_description_robus;
    public $wallet_search;

    public $perPage = 30;
    public $sortField = 'id';
    public $sortAsc = true;
    public $sidUserId;
    public $sidUserStatus;
    public $master_id;
    public $user_country;
    public $user_city;
    public $master_selected;
    public $master_country;
    public $masters;

    public function edit($id, $state)
    {
        $check = SidUser::whereId($id)->first();
        $this->sidUserId = $id;
        $this->sidUserStatus = $check->status;
        $this->emit($state);
    }


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
                $this->cancelUnderdistributersField();
                $this->emit('info', 'Desolé Ce wallet est pour vous !!!');

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
    

   public function edit_update($data)
   {
     
       $sid_user = $data['user_id'];
       
       $operator_id = $data['operator_id'];

       $check_user = SidUser::whereId($sid_user)->first();

       $check_operator = Operator::whereId($operator_id)->first();
       
       $old_operators = OperatorService::where('sid_user_id', $check_user->id)
                                               ->where('operator_id', $check_operator->id)
                                               ->first();
     
       if (!empty($old_operators)) {

           $old_operators->forcedelete();
           $this->resetdesableField();
           $this->emit('modal-hide', 'hide');
           // $this->emit('success', 'Assurance supprimée avec success !');
           $this->emit('success', 'Liste des operateurs mise à jour !');

       }else {

           $operator_service = new OperatorService;
           $operator_service->sid_user_id =$check_user->id;
           $operator_service->operator_id  = $check_operator->id;
           $operator_service->save();

           $this->resetdesableField();
           $this->emit('modal-hide', 'hide');
           $this->emit('success', 'Liste des attributions mise à jour !');

       }
   }

   public function resetdesableField()
   {
       $this->emit('modal-hide', 'hide');
       // return redirect(request()->header('Referer'));
   }


   public function editConnected($id, $state)
   {
       $check = SidUser::whereId($id)->first();
       $this->user_id = $check->id;
       $this->master_id = $check->master_id;
       $this->underdistributeurDesabled = $check->is_accepted;
       $this->emit($state);
   }

   public function addConnected($id, $state)
   {
       $check = SidUser::whereId($id)->first();
       $this->user_id = $check->id;
       $this->master_id = $check->master_id;
       $this->user_country = $check->country->id;
       $this->user_city = $check->city->id;
       $this->underdistributeurDesabled = $check->is_accepted;
       $this->emit($state);
   }


   public function connected()
   {
       $check = SidUser::find($this->user_id);

       $check_master = SidUser::find($this->master_id)->first();

       if (!empty($check)) {
           if ($check->master_id  == $this->master_id && $check->is_accepted) {
               $msg = 'Vous avez été lié a un sous distributeur';
               $msg_title = 'Liaison a un sous distributeur';
               $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
               $check->update(['master_id' => null, 'is_accepted' => false]);
               $check->assignRole(4);
           } else {
               $msg = 'Vous avez été detaché a un sous distributeur';
               $msg_title = 'Détachément a un sous distributeur';
               $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
               $check->update(['master_id' => $this->master_id, 'is_accepted' => true]);
               $check->assignRole(4);
           }
           $this->cancelConnectedField();
           $this->emit('modal-hide', 'hide');
           $this->emit('refreshPage'); // À appeler après avoir effectué les modifications
           $this->emit('success', $check->master_id  == $this->master_id ? 'Sous distributeur lié !' : 'Sous distributeur détaché !');
       }
   }


   public function cancelConnectedField()
   {
       $this->user_country = null;
       $this->user_city = null;
       $this->master_selected = null;
       $this->emit('modal-hide', 'hide');
   }

   public function connectedOneMaster()
   {
       $check = SidUser::find($this->user_id);

       $check_master = SidUser::find($this->master_selected)->first();

       if (!empty($check)) {
           $check->update(['master_id' => $this->master_selected, 'is_accepted' => true]);
           $check->assignRole(4);
           $msg = 'Vous avez été lié a un sous distributeur';
           $msg_title = 'Liaison a un sous distributeur';
           $check_master->notify(new UserNotification(['title' => $msg_title, 'message' => $msg]));
           $this->emit('modal-hide', 'hide');
           $this->cancelConnectedField();
           $this->emit('refreshPage'); // À appeler après avoir effectué les modifications
           $this->emit('success', $check->master_id  == $this->master_selected ? 'Sous distributeur lié !' : 'Sous distributeur désactivée !');
       }
   }

   public function disable()
   {
       $check = SidUser::whereId($this->sidUserId)->first();

       if (!empty($check)) {

           if ($check->status == 0) {
               $check->update(['status' => 1]);

               if ($check->email != null) {

                   Mail::to($check->email)->send(new UvSend('Votre compte Robus a été activé pour une raison \n Merci Bien'));
               }

               $this->resetField();
               $this->emit('modal-hide', 'hide');
               $this->emit('success', $check->status == 1 ? 'Utilisateur activée !' : 'Utilisateur désactivée !');
           } else {

               $check->update(['status' => 0]);

               if ($check->email != null) {

                   Mail::to($check->email)->send(new UvSend('Votre compte Robus a été desactivé pour une raison \n Merci Bien'));
               }
               $this->resetField();
               $this->emit('modal-hide', 'hide');
               $this->emit('success', $check->status == 1 ? 'Utilisateur activée !' : 'Utilisateur désactivée !');
           }
       }
   }

    public function updatedWalletIdAdd()
    {
        $this->reset('wallet_search'); 

        if ($this->wallet_id_add) {
            $this->wallet_search =  Wallet::where('wallet_id', $this->wallet_id_add)
                ->where('country_id', comzone()->country->id)
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

        // Une seule ligne suffit : la boucle plus bas relance la même recherche pour chaque wallet,
        // charger tous les wallets du pays multipliait les requêtes identiques.
        $wallets = Wallet::join('sid_users', 'sid_users.id', 'wallets.sid_user_id')
            ->where('wallets.status', true)
            ->Where('wallets.country_id', comzone()->country->id)
            ->limit(1)
            ->get();


        if ($this->search != "") {

            $this->resetPage();

            $users->where("lastname", "LIKE",  "%" . $this->search . "%")
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


        return view('livewire.com-zones.ender-distributors', [
            "users" => $users->where('country_id', comzone()->country->id)->whereIsUnderDistributer(true)->orderBy('created_at', 'desc')->latest()->paginate(30),
            "countries" => Country::where('status', true)->orderBy("name", 'desc')->get(),
            "wallet_amount" => Wallet::where('sid_user_id', comzone()->id)->first(),
            'wallet_info_searchs' => $wallet_info_firstname . '  ' . $wallet_info_lastname,
            'imagePath'   =>   $wallet_info_image ,
            "operators" => Operator::whereStatus(true)->orderBy("name", 'desc')->get(),
        ]);
    }


}
