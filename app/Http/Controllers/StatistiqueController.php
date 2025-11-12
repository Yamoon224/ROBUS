<?php

namespace App\Http\Controllers;

use App\Events\UpdateReloadEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatistiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_states()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }

        return view('pages.states.browse_state');
    }

    // public function browse_state_masters()
    // {

    //     if (master()) {
    //         return view('pages.states.browse_state_masters');
    //     } else {
    //         abort(403);
    //     }
    // }


    // public function index()
    // {
    //     // dd('ici');
    //     $event = new UpdateReloadEvent();
    //     event($event);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_reload()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }

        return view('pages.states.browse_reload');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_transfer()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_transfer');
    }

    public function browse_state_employes()
    {

        // if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
        //     abort(403);
        // }
        return view('firms.pages.browse_transfer_state');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_paiement()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_paiement');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_virement()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_virement');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_inter()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_inter');
    }

     public function browse_versment()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_versment');
    }

        public function browse_xbet()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_xbet');
    }

        public function browse_push()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_push');
    }

        public function browse_tresort()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_tresort');
    }

        public function browse_djamos()
    {

        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_djamos');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_carte()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_carte');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_phone()
    {
        if (!Auth::guard('web')->user()->hasPermissionTo('statistiques')) {
            abort(403);
        }
        return view('pages.states.browse_phone');
    }
}