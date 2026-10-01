<?php

namespace App\Http\Controllers;


use App\Models\AdminsCom;
use App\Models\Sid\SidUser;
use Illuminate\Http\Request;
use App\Mail\EmailCreatedCard;
use App\Models\Banks\BankCard;
use Illuminate\Support\Carbon;
use App\Events\AdminNotifEvent;
use App\Models\Custumers\Country;
use App\Models\Providers\Provider;
use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Events\CreateCardBankEvent;
use App\Models\Costs\OrderCardCost;
use App\Models\Wallets\WalletInter;
use App\Events\UpdateOrderCardEvent;
use App\Models\Wallets\WalletReload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Events\UpdateActiveCardEvent;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Notifications\UserNotification;
use App\Notifications\AdminNotification;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Admin\MailController;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-cartes')) {
            abort(403);
        }

        return view('pages.cards.browse');
    }

    /**
     * Add a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function add()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-cartes')) {
            abort(403);
        }

        $type_reload =  $this->getProvidersByTypepaddd(new Provider, sabdy()->country->id, 5);


        return view('pages.cards.add', compact('type_reload'));
    }

    private static function getProvidersByType($query, $countryId, $providerType)
    {


        return $query
            ->join('country_services', 'country_services.provider_id', 'providers.id')
            ->where('country_services.country_id', $countryId)
            ->where('providers.status', true)
            ->where('providers.provider_type_id', $providerType)
            ->select('providers.*')
            ->orderBy('providers.name', 'asc')
            ->get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Admin In CIV
        $admin_civ = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();
        $wallet_user = Auth::guard('web')->user();
        $valeur_ceiling  =  $wallet_user->silver_ceiling;
        $amount_days = $this->getWalletBanksCeilingProperty() + $this->getWalletDepositsCeilingProperty() + $this->getWalletIntersCeilingProperty()
            + $this->getWalletPaymentsCeilingProperty() + $this->getWalletTransactionsCeilingProperty()
            + $this->getWalletPhoneCreditsCeilingProperty() + $this->getWalletReloadsCeilingProperty()  + $this->getWalletBankCardCeilingProperty();



        try {

            $validator = Validator::make($request->all(), [
                'phone' => 'required',
                'email' => 'required',
                'address' => 'required',
                'identity_doc' => 'nullable|mimes:jpeg,png,jpg|max:2048',
                'identity_form' => 'nullable|mimes:jpeg,png,jpg|max:2048',
                'type_card_id' => 'required',
            ]);


            if ($validator->fails()) {
                return back()->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Erreur de création')
                    ->withInput();
            }


            $percent_robus = Provider::where('provider_type_id',  5)
                ->whereId($request->type_card_id)
                ->first();

            $valeur_amount =   $this->convertAdminCommission($percent_robus->amount);

            if (!$valeur_amount) {

                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Cette carte n\'a pas de montant ')
                    ->withInput();
            }

            if ($amount_days >=  $valeur_ceiling) {

                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Vous avez atteint votre montant maxi journalier')
                    ->withInput();
            }

            if ($valeur_amount > $wallet_user->wallet->amount) {

                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Verifier votre solde')
                    ->withInput();
            }

            if ($percent_robus != null) {

                $card_bank = new BankCard();
                $card_bank->company_id  = auth()->user()->company->id;
                $card_bank->company_id  = Auth::guard('web')->user()->company->id;
                $card_bank->country_id  = Auth::guard('web')->user()->country->id;
                $card_bank->city_id  = Auth::guard('web')->user()->city->id;
                $card_bank->email =  $request->email ?? null;
                $card_bank->order_card_cost_id = 1;
                $card_bank->phone  = $request->phone;
                $card_bank->address  = $request->address;
                $card_bank->bank_card_id = $request->type_card_id;
                $card_bank->amount  = $valeur_amount ?? null;
                $card_bank->wallet_id = Auth::guard('web')->user()->wallet->id;
                $card_bank->status  = false;
                $card_bank->created_by = Auth::guard('web')->user()->id;
                $card_bank->costs_amount  = $valeur_amount;
                $file_identity_doc = $request->file('identity_doc');
                $file_identity_form = $request->file('identity_form');

                // Upload Images        
                if (isset($file_identity_doc)) {
                    $destination_path = public_path('bank_cards/' . Auth::guard('api')->user()->id . '/');
                    $front_file = 'recto_' . date('YmdHis') . "." . $file_identity_doc->getClientOriginalExtension();
                    $file_identity_doc->move($destination_path, $front_file);
                    $card_bank->identity_doc =  env('APP_URL') . "/public/bank_cards/" . Auth::guard('api')->user()->id . "/$front_file";
                }

                if (isset($file_identity_form)) {
                    $destination_path = public_path('bank_cards/' . Auth::guard('api')->user()->id  . '/');
                    $back_file = 'verso_' . date('YmdHis') . "." . $file_identity_form->getClientOriginalExtension();
                    $file_identity_form->move($destination_path, $back_file);
                    $card_bank->identity_form =  env('APP_URL') . "/public/bank_cards/" . Auth::guard('api')->user()->id  . "/$back_file";
                }

                $card_bank->save();


                $card_bank->wallet->update([
                    'amount' => DB::raw('amount-'  .   $card_bank->amount),
                ]);

                if ($this->updateamountOrderCardReceveid($admin_civ, $percent_robus, $card_bank, $valeur_amount)) {

                    if ($this->sendNotificationOrderCard($card_bank)) {

                        $this->sendNotificationOrderCard($card_bank);
                        $msg = "Carte crée avec succès";
                        $color = 'success';

                        return redirect(route('cards.browse'))->with('color', $color)->with('status', $msg);
                    }
                } else {
                    return back()
                        ->withErrors($validator)
                        ->with('color', 'danger')
                        ->with('status', 'Erreur de MAJ des données')
                        ->withInput();
                }
            } else {
                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Voir le provider selectionner')
                    ->withInput();
            }
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
        }
    }

    private function sendNotificationOrderCard($walletOrderCard)
    {

        if (sabdy()->is_custumers) {
            if (sabdy()->country->code == "CI") {

                $message = 'Vous avez effectué une commande de carte de  ' . $walletOrderCard->amount .  sabdy()->wallet->currencie->code . ' ' .   'par '
                    . $walletOrderCard->provider->name  .  ' le '
                    . $walletOrderCard->created_at . '  bien été prise en compte le traitement se fera dans Maxi 5min ' . 'Robus vous remercie';

                $encode = substr(sabdy()->phone,  -10);

                http::get("//app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
            }
        }
        $msg = 'Votre demande est en cours de validation';
        $msg_title = 'Commande carte';
        sabdy()->notify(new UserNotification(
            [
                'title' => $msg_title,
                'message' => $msg
            ]
        ));

        $admins = SidUser::whereStatus(true)
            ->whereIsAdmin(true)
            ->where('country_id', 107)
            ->get();

        $notifs = sabdy()->unreadNotifications;
        event(new CreateCardBankEvent($walletOrderCard));
        Notification::send($admins, new AdminNotification('Commande de carte de ' . ' ' . $walletOrderCard->wallet->sidUser->firstname));

        if ($admins->count() > 0) {

            foreach ($admins  as $user) {

                $admin_single = SidUser::find($user->id);

                $notifs = $admin_single->unreadNotifications;

                event(new AdminNotifEvent($notifs));
            }

            if (sabdy()->email != null) {

                $notify = new MailController;
                $notify->newCardOrder($walletOrderCard);
            }

            return true;
        }

        return true;
    }

    private function updateamountOrderCardReceveid($admin_civ, $percent_robus, $walletOrderCard, $amount_commission)
    {

        if (sabdy()->is_under_distributer) {

            $valuer_deposit =  $percent_robus->percent_merchands;

            // Convertion in devise Use send
            $convertion_commission = $this->convertAdminCommission($valuer_deposit);

            $admin_valeur =  $amount_commission -  $percent_robus->percent_merchands;

            $walletOrderCard->update([
                'commissions' => DB::raw('commissions+'  .  $convertion_commission),
            ]);


            if ($admin_civ->count() > 0) {

                $nmbre_admin = $admin_civ->count();

                $valeur_single = $admin_valeur / $nmbre_admin;

                foreach ($admin_civ as $user) {
                    $admin_single = SidUser::find($user->id);

                    AdminsCom::create([
                        'amount' =>   $admin_valeur,
                        'sid_user_id' =>   $admin_single->id,
                        'bank_card_id' =>  $walletOrderCard->id,
                        'description' => 'Commission administrateur',
                        'amount_under' =>  sabdy()->is_under_distributer ? $valuer_deposit : 0,
                        'description_underdistributer' =>  sabdy()->is_under_distributer ? 'Paiement commission sous-distributer' : null
                    ]);
                }
            }
        } else {

            $admin_valeur =  $amount_commission;
        }


        $admin_valeur_convertion = $this->convertAdminCommission($admin_valeur);

        sabdy()->wallet->update([
            'reload_by' => sabdy()->firstname . ' ' . sabdy()->lastname,
            'sid_user_relod' => sabdy()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);


        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $admin_valeur_convertion / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'bank_card_id' =>  $walletOrderCard->id,
                    'description' => 'Commission administrateur',
                ]);
            }
        }

        return true;
    }

    public function convertAdminCommission($amount)
    {
        $country_civ = Country::find(107);
        $robus_currency = sabdy()->country->currencie->value;

        if ($country_civ->currencie->value > sabdy()->country->currencie->value) {
            return $amount * $robus_currency;
        } elseif ($country_civ->currencie->value == sabdy()->country->currencie->value) {
            return $amount;
        } else {
            return $amount * $robus_currency;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-cartes')) {
            abort(403);
        }

        $card = BankCard::where('id', $id)->first();
        if (empty($card)) {
            return redirect(route('cards.browse'))->with('color', 'danger')->with('status', 'Pas de doc');
        }

        return view('pages.cards.show', compact('card'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('activer-carte')) {
            abort(403);
        }

        $card = BankCard::where('id', $id)->first();

        if (empty($card)) {
            return redirect(route('cards.browse'))->with('color', 'danger')->with('status', 'Pas de doc');
        }
        return view('pages.cards.edit', compact('card'));
    }

    public function update_cards(Request $request, $id)
    {



        try {

            $card = BankCard::find($id);

            $card->update([
                'validated_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                'date_created' => Carbon::today(),
                'ref_card' => $request->ref_card,
                'status' => true
            ]);


            $message = 'Votre compte a été desactive avec success';

            $encode = substr($card->wallet->sidUser->phone,  -10);

            $message = 'Votre carte a été activé ' . ' ' . 'Le code de la carte est ' . $card->ref_card;

            Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");

            $msg_title = 'Commande de carte';
            $msg = 'Votre commande de carte a été validé';
            $card->wallet->sidUser->notify(
                new UserNotification([
                    'title' => $msg_title,
                    'message' => $msg,
                ])
            );
            $notifs = $card->wallet->sidUser->unreadNotifications;
            event(new UpdateOrderCardEvent($card));
            $admins = SidUser::whereStatus(true)
                ->whereIsAdmin(true)
                ->where('country_id', Auth::guard('web')->user()->country->id)
                ->first();
            Notification::send(
                $admins,
                new AdminNotification(
                    'Commande de carte a été validé'
                )
            );
            event(new AdminNotifEvent($notifs));

            DB::commit();

            return redirect(route('cards.browse'))->with('status', 'Vous avez validez la carte \n');
        } catch (\Exception $exception) {
            return back()
                ->with('color', 'danger')
                ->with('status', 'Erreur de modification')
                ->withInput();
        }
    }


    public function activate(int $id)
    {
        try {
            DB::beginTransaction();
            $card = BankCard::find($id);
            if ($card->status == '1') {
                $card->status = '0';
                $msg = 'Désactivation effectuée avec succès';
                $message = 'Votre compte a été desactive avec success';

                $encode = substr($card->phone,  -10);

                $message = 'Votre carte a été desactivé ' . ' ' . 'Le code de la carte est ' . $card->ref_card;

                Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
            } else {
                $card->status = '1';
                $msg = 'Activation effectuée avec succès';
                $message = 'Votre compte a été desactive avec success';

                $encode = substr($card->phone,  -10);

                $message = 'Votre carte a été activé ' . ' ' . 'Le code de la carte est ' . $card->ref_card;

                Http::get("https://app.smspro.africa/api/http/sms/send?recipient=225$encode&sender_id=ROBUS&message=$message&api_token=20|OCxFaENhPjLssgD7GpVWLyDVbRNBrKAsQfjeW67i41f1b7af");
            }
            $card->save();
            DB::commit();
            event(new UpdateActiveCardEvent($card));

            $color = 'success';
        } catch (\Exception $exception) {
            DB::rollBack();
            $msg = 'Action échouée';
            $color = 'danger';
        }
        return redirect()->back()->with('color', $color)
            ->with('status', $msg);
    }

    public function delete(int $id)
    {
        if (!Auth::user()->hasPermissionTo('supprimer-cartes')) {
            abort(403);
        }
        try {
            DB::beginTransaction();
            $bankcard = BankCard::findOrFail($id);
            $bankcard->delete();
            DB::commit();
            return redirect(route('cards.browse'))
                ->with('status', 'Carte supprimée avec succèss');
        } catch (\Exception $exception) {
            return redirect(route('cards.browse'))
                ->with('color', 'danger')
                ->with('status', 'Une erreure est survenue');
        }
    }

    // Verify ceilling


    public function getWalletBankCardCeilingProperty()
    {
        $data = [];
        foreach (
            BankCard::where('created_by', Auth::guard('web')->user()->id)
                ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (
            WalletBank::where('created_by', Auth::guard('web')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $bank
        ) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeilingProperty()
    {

        $data = [];

        foreach (
            WalletDeposit::where('created_by',  Auth::guard('web')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $deposit
        ) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (
            WalletInter::where('created_by',  Auth::guard('web')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $inter
        ) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletPayment::where('created_by',  Auth::guard('web')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $payment
        ) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }


    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletTransaction::where('created_by',  Auth::guard('web')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $transaction
        ) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];


        foreach (
            WalletPhoneCredit::where('created_by', Auth::guard('web')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $phone
        ) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (
            WalletReload::where('wallet_reloads.created_by',  Auth::guard('web')->user()->id)
                ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $reload
        ) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }
}
