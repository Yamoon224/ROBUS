<?php

namespace App\Http\Controllers;

use App\Events\UpdateReloadEvent;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_states()
    {
        return view('pages.states.browse_state');
    }


    public function index()
    {
        // dd('ici');
        $event = new UpdateReloadEvent();
        event($event);
        dd('iic');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_reload()
    {

        return view('pages.states.browse_reload');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_transfer()
    {

        return view('pages.states.browse_transfer');
    }

    public function browse_state_employes()
    {
        return view('firms.pages.browse_transfer_state');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_paiement()
    {

        return view('pages.states.browse_paiement');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_virement()
    {

        return view('pages.states.browse_virement');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_inter()
    {

        return view('pages.states.browse_inter');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_carte()
    {

        return view('pages.states.browse_carte');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_phone()
    {

        return view('pages.states.browse_phone');
    }
}