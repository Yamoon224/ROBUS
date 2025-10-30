<?php

namespace App\Http\Controllers;

use App\Events\CreateWalletBankEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-soldes')) {
            abort(403);
        }
        return view('pages.custumers.browse_rankings');
    }
    
    public function browse_visitors()
    {

        if (sabdyWebVerify()) {
            return view('pages.transfers.browse_visitors');
        } else {
            abort(403);
        }

    }

}
