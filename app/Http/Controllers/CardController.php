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
        if (sabdyWebVerify()) {
            return view('pages.cards.browse');
        } else {
            abort(403);
        }
    }

    /**
     * Add a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function add()
    {
        // $countries = Country::where('robus', true)->where('indecative', '!=', null)->get();
        $type_reload  = Provider::where('status', true)->where('provider_type_id',  5)->orderBy('name', 'asc')->get();
        $amount_valeur = OrderCardCost::whereId(1)->first();
        if (sabdyWebVerify()) {
            return view('pages.cards.add', compact('type_reload', 'amount_valeur'));
        } else {
            abort(403);
        }
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
        $admins = SidUser::where('status', true)->where('is_admin', true)->where('country_id', Auth::guard('web')->user()->country->id)->get();
        // Verify Solde sen amount days
        $valeur_ceiling  =  $wallet_user->silver_ceiling;

        $amount_days = $this->getWalletBanksCeilingProperty() + $this->getWalletDepositsCeilingProperty() + $this->getWalletIntersCeilingProperty()
            + $this->getWalletPaymentsCeilingProperty() + $this->getWalletTransactionsCeilingProperty()
            + $this->getWalletPhoneCreditsCeilingProperty() + $this->getWalletReloadsCeilingProperty()  + $this->getWalletBankCardCeilingProperty();

        $validator = Validator::make($request->all(), [
            'phone' => 'required',
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

        try {

            $percent_robus = OrderCardCost::whereId(1)->first();

            if ($amount_days >=   $valeur_ceiling) {

                return back()
                    ->withErrors($validator)
                    ->with('color', 'danger')
                    ->with('status', 'Vous avez atteint votre montant maxi journalier')
                    ->withInput();
            } else {

                if (($wallet_user->wallet->currencie->code == "EUR" ||
                    $wallet_user->wallet->currencie->code == "DH" ||
                    $wallet_user->wallet->currencie->code == "Dt" ||
                    $wallet_user->wallet->currencie->code == "CEDI" ||
                    $wallet_user->wallet->currencie->code == "USD")) {

                    $valeur_amount =  $percent_robus->amount / $wallet_user->wallet->currencie->value;

                    if ($valeur_amount > $wallet_user->wallet->amount) {

                        return back()
                            ->withErrors($validator)
                            ->with('color', 'danger')
                            ->with('status', 'Verifier votre solde')
                            ->withInput();
                    } else {

                        $card_bank = new BankCard();
                        $card_bank->company_id  = auth()->user()->company->id;
                        $card_bank->company_id  = Auth::guard('web')->user()->company->id;
                        $card_bank->country_id  = Auth::guard('web')->user()->country->id;
                        $card_bank->city_id  = Auth::guard('web')->user()->city->id;
                        $card_bank->email =  $request->email ?? null;
                        $card_bank->order_card_cost_id = 1;
                        $card_bank->phone  = $request->phone;
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

                        if ($wallet_user->is_under_distributer == 1) {

                            // Commission if distributer
                            $valuer_deposit =  $percent_robus->percent_merchands;

                            // Convertion valeur distributer
                            $valeur_conver_distr = $valuer_deposit / $wallet_user->wallet->currencie->value;

                            // Commission admin
                            $admin_valeur =  $card_bank->amount -  $percent_robus->percent_merchands;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $admin_valeur / $count_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => null,
                                        'wallet_bank_id' => null,
                                        'wallet_deposit_id' => null,
                                        'wallet_inter_id' => null,
                                        'wallet_payment_id' => null,
                                        'wallet_phone_credit_id' => null,
                                        'wallet_reload_id' => null,
                                        'bank_card_id' =>  $card_bank->id,
                                        'description' => 'Commission',
                                    ]);

                                    $admin_single->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $valeur_single),
                                    ]);
                                }
                            }

                            $card_bank->update([
                                'commissions' => DB::raw('commissions+'  .  $valeur_conver_distr),
                            ]);
                            $msg = 'Votre demande est en cours de validation';
                            $msg_title = 'Commande carte';
                            $wallet_user->notify(new UserNotification(
                                [
                                    'title' => $msg_title,
                                    'message' => $msg
                                ]
                            ));
                            if ($wallet_user->country->code == "CI") {

                                $message = 'Vous avez effectué une commande de carte de ' . $card_bank->amount .  $wallet_user->wallet->currencie->code . ' ' .  'par '
                                    . $card_bank->typeCard->name  .  ' a '
                                    . $card_bank->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone,  -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            $notifs = $wallet_user->unreadNotifications;
                            event(new CreateCardBankEvent($card_bank));
                            Notification::send($admins, new AdminNotification('Commande de carte de ' . ' ' . $card_bank->wallet->sidUser->firstname));

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            } else {

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            }
                        } else {


                            // Commission admin
                            $admin_valeur =  $card_bank->amount;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $admin_valeur / $count_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => null,
                                        'wallet_bank_id' => null,
                                        'wallet_deposit_id' => null,
                                        'wallet_inter_id' => null,
                                        'wallet_payment_id' => null,
                                        'wallet_phone_credit_id' => null,
                                        'wallet_reload_id' => null,
                                        'bank_card_id' =>  $card_bank->id,
                                        'description' => 'Commission',
                                    ]);

                                    $admin_single->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $valeur_single),
                                    ]);
                                }
                            }

                            $msg = 'Votre demande est en cours de validation';
                            $msg_title = 'Commande carte';
                            $wallet_user->notify(new UserNotification(
                                [
                                    'title' => $msg_title,
                                    'message' => $msg
                                ]
                            ));
                            if ($wallet_user->country->code == "CI") {

                                $message = 'Vous avez effectué une commande de carte de ' . $card_bank->amount .  $wallet_user->wallet->currencie->code . ' ' .  'par '
                                    . $card_bank->typeCard->name  .  ' a '
                                    . $card_bank->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone,  -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            $notifs = $wallet_user->unreadNotifications;
                            event(new CreateCardBankEvent($card_bank));
                            Notification::send($admins, new AdminNotification('Commande de carte de ' . ' ' . $card_bank->wallet->sidUser->firstname));

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            } else {

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            }
                        }

                        $msg = "Carte crée avec succès";
                        $color = 'success';

                        return redirect(route('cards.browse'))->with('color', $color)->with('status', $msg);
                    }
                } elseif ($wallet_user->wallet->currencie->code == "GNF") {

                    $valeur_amount =  $percent_robus->amount *  $wallet_user->wallet->currencie->value;

                    if ($valeur_amount > $wallet_user->wallet->amount) {

                        return back()
                            ->withErrors($validator)
                            ->with('color', 'danger')
                            ->with('status', 'Verifier votre solde')
                            ->withInput();
                    } else {

                        $card_bank = new BankCard();
                        $card_bank->company_id  = auth()->user()->company->id;
                        $card_bank->company_id  = Auth::guard('web')->user()->company->id;
                        $card_bank->country_id  = Auth::guard('web')->user()->country->id;
                        $card_bank->city_id  = Auth::guard('web')->user()->city->id;
                        $card_bank->email =  $request->email ?? null;
                        $card_bank->order_card_cost_id = 1;
                        $card_bank->phone  = $request->phone;
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

                        if ($wallet_user->is_under_distributer == 1) {

                            // Commission if distributer
                            $valuer_deposit =  $percent_robus->percent_merchands;

                            // Convertion valeur distributer
                            $valeur_conver_distr = $valuer_deposit / $wallet_user->wallet->currencie->value;

                            // Commission admin
                            $admin_valeur =  $card_bank->amount -  $percent_robus->percent_merchands;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $admin_valeur / $count_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => null,
                                        'wallet_bank_id' => null,
                                        'wallet_deposit_id' => null,
                                        'wallet_inter_id' => null,
                                        'wallet_payment_id' => null,
                                        'wallet_phone_credit_id' => null,
                                        'wallet_reload_id' => null,
                                        'bank_card_id' =>  $card_bank->id,
                                        'description' => 'Commission',
                                    ]);

                                    $admin_single->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $valeur_single),
                                    ]);
                                }
                            }

                            $card_bank->update([
                                'commissions' => DB::raw('commissions+'  .  $valeur_conver_distr),
                            ]);
                            $msg = 'Votre demande est en cours de validation';
                            $msg_title = 'Commande carte';
                            $wallet_user->notify(new UserNotification(
                                [
                                    'title' => $msg_title,
                                    'message' => $msg
                                ]
                            ));
                            if ($wallet_user->country->code == "CI") {

                                $message = 'Vous avez effectué une commande de carte de ' . $card_bank->amount .  $wallet_user->wallet->currencie->code . ' ' .  'par '
                                    . $card_bank->typeCard->name  .  ' a '
                                    . $card_bank->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone,  -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            $notifs = $wallet_user->unreadNotifications;
                            event(new CreateCardBankEvent($card_bank));
                            Notification::send($admins, new AdminNotification('Commande de carte de ' . ' ' . $card_bank->wallet->sidUser->firstname));

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            } else {

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            }
                        } else {


                            // Commission admin
                            $admin_valeur =  $card_bank->amount;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $admin_valeur / $count_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => null,
                                        'wallet_bank_id' => null,
                                        'wallet_deposit_id' => null,
                                        'wallet_inter_id' => null,
                                        'wallet_payment_id' => null,
                                        'wallet_phone_credit_id' => null,
                                        'wallet_reload_id' => null,
                                        'bank_card_id' =>  $card_bank->id,
                                        'description' => 'Commission',
                                    ]);

                                    $admin_single->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $valeur_single),
                                    ]);
                                }
                            }

                            $msg = 'Votre demande est en cours de validation';
                            $msg_title = 'Commande carte';
                            $wallet_user->notify(new UserNotification(
                                [
                                    'title' => $msg_title,
                                    'message' => $msg
                                ]
                            ));
                            if ($wallet_user->country->code == "CI") {

                                $message = 'Vous avez effectué une commande de carte de ' . $card_bank->amount .  $wallet_user->wallet->currencie->code . ' ' .  'par '
                                    . $card_bank->typeCard->name  .  ' a '
                                    . $card_bank->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone,  -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            $notifs = $wallet_user->unreadNotifications;
                            event(new CreateCardBankEvent($card_bank));
                            Notification::send($admins, new AdminNotification('Commande de carte de ' . ' ' . $card_bank->wallet->sidUser->firstname));

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            } else {

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            }
                        }

                        $msg = "Carte crée avec succès";
                        $color = 'success';

                        return redirect(route('cards.browse'))->with('color', $color)->with('status', $msg);
                    }
                } elseif ($wallet_user->wallet->currencie->code == "CFA") {

                    $valeur_amount =  $percent_robus->amount;

                    if ($valeur_amount > $wallet_user->wallet->amount) {

                        return back()
                            ->withErrors($validator)
                            ->with('color', 'danger')
                            ->with('status', 'Verifier votre solde')
                            ->withInput();
                    } else {

                        $card_bank = new BankCard();
                        $card_bank->company_id  = auth()->user()->company->id;
                        $card_bank->company_id  = Auth::guard('web')->user()->company->id;
                        $card_bank->country_id  = Auth::guard('web')->user()->country->id;
                        $card_bank->city_id  = Auth::guard('web')->user()->city->id;
                        $card_bank->email =  $request->email ?? null;
                        $card_bank->order_card_cost_id = 1;
                        $card_bank->phone  = $request->phone;
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

                        if ($wallet_user->is_under_distributer == 1) {

                            // Commission if distributer
                            $valuer_deposit =  $percent_robus->percent_merchands;

                            // Convertion valeur distributer
                            $valeur_conver_distr = $valuer_deposit / $wallet_user->wallet->currencie->value;

                            // Commission admin
                            $admin_valeur =  $card_bank->amount -  $percent_robus->percent_merchands;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $admin_valeur / $count_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => null,
                                        'wallet_bank_id' => null,
                                        'wallet_deposit_id' => null,
                                        'wallet_inter_id' => null,
                                        'wallet_payment_id' => null,
                                        'wallet_phone_credit_id' => null,
                                        'wallet_reload_id' => null,
                                        'bank_card_id' =>  $card_bank->id,
                                        'description' => 'Commission',
                                    ]);

                                    $admin_single->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $valeur_single),
                                    ]);
                                }
                            }

                            $card_bank->update([
                                'commissions' => DB::raw('commissions+'  .  $valeur_conver_distr),
                            ]);

                            $msg = 'Votre demande est en cours de validation';
                            $msg_title = 'Commande carte';
                            $wallet_user->notify(new UserNotification(
                                [
                                    'title' => $msg_title,
                                    'message' => $msg
                                ]
                            ));
                            if ($wallet_user->country->code == "CI") {

                                $message = 'Vous avez effectué une commande de carte de ' . $card_bank->amount .  $wallet_user->wallet->currencie->code . ' ' .  'par '
                                    . $card_bank->typeCard->name  .  ' a '
                                    . $card_bank->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone,  -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            $notifs = $wallet_user->unreadNotifications;
                            event(new CreateCardBankEvent($card_bank));
                            Notification::send($admins, new AdminNotification('Commande de carte de ' . ' ' . $card_bank->wallet->sidUser->firstname));

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            } else {

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            }
                        } else {


                            // Commission admin
                            $admin_valeur =  $card_bank->amount;

                            //  Send valeur in Admin
                            if ($admin_civ->count() > 0) {

                                $count_admin = $admin_civ->count();

                                $valeur_single = $admin_valeur / $count_admin;

                                foreach ($admin_civ as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    AdminsCom::create([
                                        'amount' =>   $valeur_single,
                                        'sid_user_id' =>   $admin_single->id,
                                        'wallet_transaction_id' => null,
                                        'wallet_bank_id' => null,
                                        'wallet_deposit_id' => null,
                                        'wallet_inter_id' => null,
                                        'wallet_payment_id' => null,
                                        'wallet_phone_credit_id' => null,
                                        'wallet_reload_id' => null,
                                        'bank_card_id' =>  $card_bank->id,
                                        'description' => 'Commission',
                                    ]);

                                    $admin_single->wallet->update([
                                        'reload_by' => Auth::guard('web')->user()->firstname . ' ' . Auth::guard('web')->user()->lastname,
                                        'sid_user_relod' => Auth::guard('web')->user()->id,
                                        'amount' => DB::raw('amount+' . $valeur_single),
                                    ]);
                                }
                            }

                            $msg = 'Votre demande est en cours de validation';
                            $msg_title = 'Commande carte';
                            $wallet_user->notify(new UserNotification(
                                [
                                    'title' => $msg_title,
                                    'message' => $msg
                                ]
                            ));
                            if ($wallet_user->country->code == "CI") {

                                $message = 'Vous avez effectué une commande de carte de ' . $card_bank->amount .  $wallet_user->wallet->currencie->code . ' ' .  'par '
                                    . $card_bank->typeCard->name  .  ' a '
                                    . $card_bank->created_at . ' votre demande est en-cours de validation ' . 'Robus vous remercie';

                                $encode = substr(Auth::guard('web')->user()->phone,  -10);

                                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
                            }
                            $notifs = $wallet_user->unreadNotifications;
                            event(new CreateCardBankEvent($card_bank));
                            Notification::send($admins, new AdminNotification('Commande de carte de ' . ' ' . $card_bank->wallet->sidUser->firstname));

                            if ($admins->count() > 0) {

                                foreach ($admins  as $user) {

                                    $admin_single = SidUser::find($user->id);

                                    $notifs = $admin_single->unreadNotifications;

                                    event(new AdminNotifEvent($notifs));
                                }

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            } else {

                                if ($wallet_user->email != null) {

                                    $notify = new MailController;
                                    $notify->newCardOrder($card_bank);
                                }
                            }
                        }

                        $msg = "Carte crée avec succès";
                        $color = 'success';

                        return redirect(route('cards.browse'))->with('color', $color)->with('status', $msg);
                    }
                }
            }
        } catch (\Throwable $th) {
            return back()
                ->withErrors($validator)
                ->with('color', 'danger')
                ->with('status', 'Erreur de création')
                ->withInput();
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

            Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");

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

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
            } else {
                $card->status = '1';
                $msg = 'Activation effectuée avec succès';
                $message = 'Votre compte a été desactive avec success';

                $encode = substr($card->phone,  -10);

                $message = 'Votre carte a été activé ' . ' ' . 'Le code de la carte est ' . $card->ref_card;

                Http::get("https://admin.sms-mail.pro/sms/api?action=send-sms&api_key=PXF0TGpuQ0t4c3dESUpPc2NCRXc=&to=225$encode&from=ROBUS&sms=$message");
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
        foreach (BankCard::where('created_by', Auth::guard('web')->user()->id)
            ->whereDate('created_at', Carbon::today())->whereStatus(1)->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }


    public function getWalletBanksCeilingProperty()
    {
        $data = [];
        foreach (WalletBank::where('created_by', Auth::guard('web')->user()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $bank) {
            $data[] = $bank->amount;
        }

        return array_sum($data);
    }

    public function getWalletDepositsCeilingProperty()
    {

        $data = [];

        foreach (WalletDeposit::where('created_by',  Auth::guard('web')->user()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $deposit) {
            $data[] = $deposit->amount;
        }

        return array_sum($data);
    }

    public function getWalletIntersCeilingProperty()
    {
        $data = [];

        foreach (WalletInter::where('created_by',  Auth::guard('web')->user()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $inter) {
            $data[] = $inter->amount;
        }

        return array_sum($data);
    }

    public function getWalletPaymentsCeilingProperty()
    {
        $data = [];

        foreach (WalletPayment::where('created_by',  Auth::guard('web')->user()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $payment) {
            $data[] = $payment->amount;
        }

        return array_sum($data);
    }


    public function getWalletTransactionsCeilingProperty()
    {
        $data = [];

        foreach (WalletTransaction::where('created_by',  Auth::guard('web')->user()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $transaction) {
            $data[] = $transaction->amount;
        }

        return array_sum($data);
    }

    public function getWalletPhoneCreditsCeilingProperty()
    {
        $data = [];


        foreach (WalletPhoneCredit::where('created_by', Auth::guard('web')->user()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $phone) {
            $data[] = $phone->amount;
        }

        return array_sum($data);
    }

    public function getWalletReloadsCeilingProperty()
    {
        $data = [];

        foreach (WalletReload::where('wallet_reloads.created_by',  Auth::guard('web')->user()->id)
            ->whereDate('created_at', Carbon::today())->where('status', 'SUCCESS')->get() as $reload) {
            $data[] = $reload->amount;
        }

        return array_sum($data);
    }
}
