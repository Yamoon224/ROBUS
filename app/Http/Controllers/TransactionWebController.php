<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionWebController extends Controller
{
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function transactions_reloads()
        {

                return view('pages.transactionWeb.browse_carte_reloads');
        }

        public function transactions_pushs()
        {

                return view('pages.transactionWeb.browse_carte_pushs');
        }


        public function transactions_robus()
        {

                return view('pages.transactionWeb.browse_robus');
        }

        public function transactions_deposits()
        {

                return view('pages.transactionWeb.browse_deposits');
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */

        public function transactions_inters()
        {
                return view('pages.transactionWeb.browse_inters');
        }

        public function transactions_xbets()
        {
                return view('pages.transactionWeb.browse_xbets');
        }

        public function transactions_payments()
        {
                return view('pages.transactionWeb.browse_payments');
        }


        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
      

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
