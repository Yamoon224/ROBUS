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
