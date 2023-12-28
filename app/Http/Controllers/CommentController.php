<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_banks()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }

        return view('pages.commissions.banks');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_deposits()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.deposits');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_inters()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.inters');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_payments()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.payments');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_phones()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.phone_credits');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_reloads()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.reloads');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_transactions()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('voir-commissions')) {
            abort(403);
        }
        return view('pages.commissions.transactions');
    }
}