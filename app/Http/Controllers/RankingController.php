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
        return view('pages.rankings.browse_rankings');
    }

    public function browse_transactions()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-soldes')) {
            abort(403);
        }
        return view('pages.rankings.browse_transactions');
    }

    public function browse_paiments()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-soldes')) {
            abort(403);
        }
        return view('pages.rankings.browse_paiments');
    }

    public function browse_virments()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-soldes')) {
            abort(403);
        }
        return view('pages.custumers.browse_virments');
    }

    public function browse_phone_credits()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-soldes')) {
            abort(403);
        }
        return view('pages.rankings.browse_phone_credits');
    }

    public function browse_reloads_cards()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-soldes')) {
            abort(403);
        }
        return view('pages.rankings.browse_reloads_cards');
    }

    public function browse_inters()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-soldes')) {
            abort(403);
        }
        return view('pages.rankings.browse_inters');
    }

    public function browse_bank_sends()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('voir-soldes')) {
            abort(403);
        }
        return view('pages.rankings.browse_bank_sends');
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
