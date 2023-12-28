<?php

namespace App\Http\Controllers\Invoices;

use App\Models\Sid\SidUser;
use Illuminate\Http\Request;
use App\Models\Wallets\Wallet;
use App\Models\Wallets\WalletBank;
use App\Models\Wallets\WalletInter;
use App\Http\Controllers\Controller;
use App\Models\Wallets\WalletReload;
use App\Models\Wallets\WalletDeposit;
use App\Models\Wallets\WalletPayment;
use App\Models\Wallets\WalletPhoneCredit;
use App\Models\Wallets\WalletTransaction;

class InvoiceController extends Controller
{
    
    
    public function invoice_realod($id)
    {

        $direction = 'ltr';
        $default_text_align = 'left';
        $reverse_text_align = 'right';


        $font_family = "'Roboto','sans-serif'";


        $walletReload = WalletReload::find($id);

        // Search Wallet Send
        $wallet_search = Wallet::where('wallet_id',$walletReload->account_wallet)
                           ->first();
     
        if ($walletReload != null) {
            return view('pages.invoices.invoice_print', compact('walletReload','font_family', 'direction', 'default_text_align' ,'reverse_text_align' , 'wallet_search'));
        }
    } 

    public function transfers_uv($id)
    {

        $direction = 'ltr';
        $default_text_align = 'left';
        $reverse_text_align = 'right';


        $font_family = "'Roboto','sans-serif'";


        $walletTransfers = WalletTransaction::find($id);
        

        if ($walletTransfers != null) {
            return view('pages.invoices.invoice_transfers_print', compact('walletTransfers','font_family', 'direction', 'default_text_align' ,'reverse_text_align'));
        }
    } 


    public function transfers_uv_company($id)
    {
        $direction = 'ltr';
        $default_text_align = 'left';
        $reverse_text_align = 'right';


        $font_family = "'Roboto','sans-serif'";


        $walletTransfers = WalletTransaction::find($id);
        

        if ($walletTransfers != null) {
            return view('pages.invoices.invoice_transfers_print', compact('walletTransfers','font_family', 'direction', 'default_text_align' ,'reverse_text_align'));
        }
    }

    public function paiements_invoice($id)
    {

        $direction = 'ltr';
        $default_text_align = 'left';
        $reverse_text_align = 'right';


        $font_family = "'Roboto','sans-serif'";


        $walletPayments = WalletPayment::find($id);
        

        if ($walletPayments != null) {
            return view('pages.invoices.invoice_payments_print', compact('walletPayments','font_family', 'direction', 'default_text_align' ,'reverse_text_align'));
        }
    } 

    public function versements_invoice($id)
    {
        $direction = 'ltr';
        $default_text_align = 'left';
        $reverse_text_align = 'right';


        $font_family = "'Roboto','sans-serif'";


        $walletBanks = WalletBank::find($id);
        

        if ($walletBanks != null) {
            return view('pages.invoices.invoice_versements_print', compact('walletBanks','font_family', 'direction', 'default_text_align' ,'reverse_text_align'));
        } 
    }
  
    public function inters_invoice($id)
    {
        $direction = 'ltr';
        $default_text_align = 'left';
        $reverse_text_align = 'right';


        $font_family = "'Roboto','sans-serif'";


        $walletInters = WalletInter::find($id);
        

        if ($walletInters != null) {
            return view('pages.invoices.invoice_inters_print', compact('walletInters','font_family', 'direction', 'default_text_align' ,'reverse_text_align'));
        } 
    }

    public function reload_card_invoice($id)
    {
       $direction = 'ltr';
        $default_text_align = 'left';
        $reverse_text_align = 'right';


        $font_family = "'Roboto','sans-serif'";


        $walletDeposits = WalletDeposit::find($id);
        if ($walletDeposits != null) {
            return view('pages.invoices.invoice_deposit_print', compact('walletDeposits','font_family', 'direction', 'default_text_align' ,'reverse_text_align'));
        } 
        
    }

    public function phone_credit_invoice($id)
    {
         
        $direction = 'ltr';
        $default_text_align = 'left';
        $reverse_text_align = 'right';


        $font_family = "'Roboto','sans-serif'";


        $walletPhoneCredit = WalletPhoneCredit::find($id);
        if ($walletPhoneCredit != null) {
            return view('pages.invoices.invoice_phone_credit_print', compact('walletPhoneCredit','font_family', 'direction', 'default_text_align' ,'reverse_text_align'));
        } 
        
        
    }
   
}