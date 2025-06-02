@extends('layouts.master_app')

@section('title', 'Acceuil')

@if ($appStatusResult['success'])

    @section('content')
        <div>
            <livewire:home-masters>
        </div>
    @endsection
@else
    <section class="contact-info-section style_percent">


        <div class="container">
            <div class="row ">
                <div class="col-md-3 col-sm-10 col-xs-12">

                </div>

                <div class="col-md-6 col-sm-10 col-xs-12">
                    @include('layouts.shared_web.close_app_master')

                </div>

                <div class="col-md-3 col-sm-10 col-xs-12">

                </div>

            </div>

        </div>
    </section>
@endif
