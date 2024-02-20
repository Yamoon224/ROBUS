<?php

namespace App\Http\Services;


use App\Models\AdminsCom;
use App\Models\Invoices\OneBetInvoice;
use App\Models\Payments\OneBetTransaction;
use App\Models\Sid\SidUser;
use App\Repositories\OnexbetRepository;
use Illuminate\Support\Facades\DB;

class XbetService
{

    /** @var  XbetRepository */
    public $xbetRepository;

    public function __construct(OnexbetRepository $xbetRepository)
    {
        $this->xbetRepository = $xbetRepository;
    }


    public function createRobusWalletTransactionOneBet($amount_valeur, $provider, $percent_robus, $numberAcompte, $fullName , $amountReceved)
    {

        $amount_commission = (float)$amount_valeur + (float)$percent_robus->percent * $amount_valeur ;
        
        $amount_solde = (float)robus()->wallet->amount - (float)$amount_commission;
       

        return $this->xbetRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande  de depot 1Xbet',
            'Onexbet_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde  ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent * $amount_valeur),
            'choose_type_transaction' => 1,
            'num_acompte'  =>  $numberAcompte,
            'full_name' =>  $fullName,
            'status'  => "PENDING",
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }



    public function createRobusWalletDrawalOneBet($amount_valeur, $provider, $percent_robus, $numberAcompte, $countrie , $citie , $code_drawal, $amountReceved)
    {

        $amount_solde = robus()->wallet->amount ;

        return $this->xbetRepository->create([
            'wallet_id' =>   robus()->wallet->id,
            'amount' => $amount_valeur,
            'description'  => 'Demande de retrait 1Xbet',
            'Onexbet_id' => 1,
            'created_by' => robus()->id,
            'amount_solde'  => $amount_solde ?? null,
            'provider_id'  =>  $provider,
            'costs_amount' => ($percent_robus->percent_with_drawal * $amount_valeur),
            'choose_type_transaction' => 2,
            'num_acompte'  =>  $numberAcompte,
            'code_drawal' =>  $code_drawal,
            'countrie' =>  $countrie,
            'citie' =>  $citie,
            'status'  => "PENDING", 
            'amount_received'  =>  $amountReceved ?? 0.0,
        ]);
    }

    public function updateamountOneBetReceveid($admin_civ, $percent_robus, $walletXbet, $amount)
    {

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));

        $amount_commission = $amount + ($percent_robus->percent * $amount);

        $amount_solde = robus()->wallet->amount - $amount_commission;

        $walletXbet->update([
            'amount_solde' => $amount_solde,
        ]);

        robus()->wallet->update([
            'reload_by' => robus()->firstname . ' ' . robus()->lastname,
            'sid_user_relod' => robus()->id,
            'amount' => DB::raw('amount-'  .  $amount_commission),
        ]);

        
        $admin_valeur =  $amount * $percent_robus->percent;


        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $admin_valeur / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_xbet_id' => $walletXbet->id,
                    'description' => 'Commission administrateur',
                ]);
            }
        }

        return true;
    }

    public function updateamountOneBetDrawal($admin_civ, $percent_robus, $walletxbet, $amount)
    {

        $rt = rand(40, 5000) * time();
        $tr = str_shuffle('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $transactionCompiCode = sprintf('%0.8s', str_shuffle($rt . $tr));
        $transactionCode = sprintf('%0.9s', str_shuffle($rt . $tr));


        $admin_valeur =  $amount * $percent_robus->percent_with_drawal;

        //  Send valeur in Admin
        if ($admin_civ->count() > 0) {

            $count_admin = $admin_civ->count();

            $valeur_single = $admin_valeur / $count_admin;

            foreach ($admin_civ as $user) {
                $admin_single = SidUser::find($user->id);
                AdminsCom::create([
                    'amount' =>   $valeur_single,
                    'sid_user_id' =>   $admin_single->id,
                    'wallet_xbet_id' => $walletxbet->id,
                    'description' => 'Commission administrateur',
                ]);
            }
        }

        return true;
    }
}
