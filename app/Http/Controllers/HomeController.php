<?php

namespace App\Http\Controllers;

use App\Models\Custumers\Country;
use App\Models\User;
use App\Models\Sid\SidUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{



  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */ public function index()
  {


    // $users = User::orderBy('created_at', 'DESC')->get();
    // $countries = Country::where('robus' , true)->orderBy('name','desc')->get();
    // $usersAdmin = SidUser::orderBy('created_at', 'desc')->get();

    //  return view('home', compact('users','usersAdmin' ,'countries'));  
      return view('home');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */

  public function store(Request $request)
  {
  }

  /**
   * Sell a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function sell(Request $request)
  {
  }
}