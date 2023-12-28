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
        return view('pages.levels.browse_level_bank');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_levels_ivoire()
    {
        return view('pages.levels.browse_level_ivoirie');
    }

    public function browse_levels_custumers() 
    {

        return view('pages.levels.browse_level_custumers');

    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_levels_deposit()
    {
        return view('pages.levels.browse_level_deposit');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function browse_levels_deposit_ivoire()
    {
        return view('pages.levels.browse_level_deposit_ivoirie');
    }


    public function browse_levels_deposit_ivoire_under() {

        return view('pages.levels.browse_levels_deposit_ivoire_under');

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