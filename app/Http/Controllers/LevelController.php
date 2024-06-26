<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_levels_bank()
    {
        if (sabdyWebVerify()) {
            return view('pages.levels.browse_level_bank');
        } else {
            abort(403);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_levels_ivoire()
    {
        if (sabdyWebVerify()) {
            return view('pages.levels.browse_level_ivoirie');
        } else {
            abort(403);
        }
    }

    public function browse_levels_custumers()
    {

        if (sabdyWebVerify()) {
            return view('pages.levels.browse_level_custumers');
        } else {
            abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_levels_deposit()
    {
        if (sabdyWebVerify()) {
            return view('pages.levels.browse_level_deposit');
        } else {
            abort(403);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_levels_deposit_ivoire()
    {
        if (sabdyWebVerify()) {
            return view('pages.levels.browse_level_deposit_ivoirie');
        } else {
            abort(403);
        }
    }


    public function browse_levels_deposit_ivoire_under()
    {

        if (sabdyWebVerify()) {
            return view('pages.levels.browse_levels_deposit_ivoire_under');
        } else {
            abort(403);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
