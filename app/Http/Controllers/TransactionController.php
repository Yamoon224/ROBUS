<?php

namespace App\Http\Controllers;

use App\Events\CreateWalletBankEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse()
    {

        if (sabdyWebVerify()) {
            return view('pages.transfers.browse');
        } else {
            abort(403);
        }
    }
    
    public function browse_visitors()
    {

        if (sabdyWebVerify()) {
            return view('pages.transfers.browse_visitors');
        } else {
            abort(403);
        }

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function browse_historique()
    {
        if (sabdyWebVerify()) {
            return view('pages.transfers.browse_historiques');
        } else {
            abort(403);
        }
    }


    public function browse_historique_visitors()
    {
        if (sabdyWebVerify()) {
            return view('pages.transfers.browse_histo_visitors');
        } else {
            abort(403);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_uv()
    {
        if (sabdyWebVerify()) {
            return view('pages.transfers.browse_uv');
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
        //
    }
}
