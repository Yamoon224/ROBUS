@extends('layouts.master')

@section('title', 'Details du société')

@section('content')
    <h2 class="text-lg font-medium truncate mr-5 mt-6">Liste des gains a remonté</h2>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">


        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in">


            <div class="font-medium text-historique mt-6">Total Gain</div>

            <div class="text-slate-500 mt-3">
                {{ number_format($company_gains, 2, ',', ' ') ?? null }}
                {{ $usersCountry ?? null }}</div>

        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">

            <div class="font-medium text-historique text-white mt-6">Paiements</div>


            <div class="text-slate-500 mt-3">
                {{ number_format($gain_payments, 2, ',', ' ') ?? null }}
                {{ $usersCountry ?? null }}</div>

        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error">

            <div class="font-medium text-historique mt-6">Dépot Robus</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_depots, 2, ',', ' ') ?? null }} {{ $usersCountry ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in ">

            <div class="font-medium text-historique mt-6">Rétrait Robus</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_retraits, 2, ',', ' ') ?? null }} {{ $usersCountry ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-error">




            <div class="font-medium text-historique mt-6">Mobile Money</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_moneys, 2, ',', ' ') ?? null }} {{ $usersCountry ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_user1">



            <div class="font-medium text-historique mt-6">Credits</div>
            <div class="text-slate-500 mt-3"> {{ number_format($gain_phone_credits, 2, ',', ' ') ?? null }}
                {{ $usersCountry ?? null }}</div>

        </div>
        {{-- luiscrepin@gmail.com --}}
        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in">



            <div class="font-medium text-historique mt-6">Inters</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_inters, 2, ',', ' ') ?? null }}
                {{ $usersCountry ?? null }}</div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table2">

            <div class="font-medium text-historique mt-6">Virements</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_banks, 2, ',', ' ') ?? null }}
                {{ $usersCountry ?? null }}</div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">


            <div class="font-medium text-historique mt-6">Réchargements cartes</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_deposits, 2, ',', ' ') ?? null }}
                {{ $usersCountry ?? null }}


            </div>
        </div>

    </div>
    <div class="grid grid-cols-12 gap-6 mt-3">

        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

                <div class="p-5">
                    <div class="" id="step-2">
                        @include('components.update_password_form')
                    </div>
                </div>


            </div>
            <!-- END: Form Validation -->
        </div>
        
    </div>
    <div class="grid grid-cols-12 gap-6 mt-3">

        <div class="intro-y col-span-6 lg:col-span-12">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

                <div class="p-5">
                    <h2 class="text-lg mt-3 font-medium truncate mr-5 mt-6">Modification du Numero</h2>

                    <div class="" id="step-2">
                        @include('components.update_phone_form')

                    </div>
                </div>


            </div>
            <!-- END: Form Validation -->
        </div>

        <div class="intro-y col-span-6 lg:col-span-12">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

                <div class="p-5">
                    <h2 class="text-lg mt-3 font-medium truncate mr-5 mt-6">Modification du email</h2>

                    <div class="" id="step-2">

                        @include('components.update_email_form')
                    </div>
                </div>


            </div>
            <!-- END: Form Validation -->
        </div>
    </div>
    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <div class="grid grid-cols-12 gap-6">

            <div class="col-span-12 2xl:col-span-12">

                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y style_user1">
                        <div class="report-box zoom-in style_user1">
                            <div class="box p-5 style_user1">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>

                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">
                                    {{ $employers ?? null }}
                                </div>
                                <div class="text-base text-slate-500 mt-1">Clients</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:col-span-6 lg:col-span-6 intro-y style_user2">
                        <div class="report-box zoom-in style_user2">
                            <div class="box p-5 style_user2">
                                <div class="flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                                    </svg>
                                </div>
                                <div class="text-3xl font-medium leading-8 mt-6">
                                    {{ $underDistributors }}
                                </div>
                                <div class="text-base text-slate-500 mt-1">Sous-distributeurs</div>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
        </div>

        <h2 class="intro-y text-lg font-medium mt-3 text-center">
            Details SOCIETE {{ $user->firstname }}
        </h2>
        <h2 class="font-medium text-base mr-auto mt-3 mb-3">
            <a href="{{ URL::previous() }}" class="btn btn-sm">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none"
                    stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
        </h2>
            @include('components.info_detail_wallet')

        <div class="ml-5">
            @if ($user->is_active == true)
                <div class="sm:w-40 truncate sm:whitespace-normal font-medium text-lg mt-3 text-success">
                    <a class="flex items-center mr-3 mb-3 mt-3">
                        <i class="fas fa-unlock-alt w-4 h-4 mr-2 "></i>
                        Validez
                    </a>
                </div>
            @else
                <div class="sm:w-40 truncate sm:whitespace-normal font-medium text-lg mt-3">
                    <form class="validate-form" method="POST"
                        action="{{ route('custumers.update_custumers', ['id' => $user->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <p>Valider le compte </p>
                        <button type="submit" class="btn btn-primary mb-2 mt-6" style="height: 2rem;">Validez</button>
                    </form>
                </div>
            @endif

        </div>
        @include('components.account_company_nav')

    </div>
    <!-- END: Profile Info -->

    <div class="tab-content mt-6">
        <div id="doc" class="tab-pane active" role="tabpanel" aria-labelledby="doc-tab">

            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>


                <div class="hidden md:block mx-auto text-slate-500"></div>


            </div>
            <div class="grid grid-cols-12 gap-6  mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des documents
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Date d'envoie
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Image de face
                                            </th>

                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Arriere
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Image
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Motif
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Validez par
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Date de validation
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap text-center">
                                                Status
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($kycs->count() > 0)
                                            @foreach ($kycs as $item)
                                                <tr class="intro-x">
                                                    <td class="w-40 text-center">

                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>

                                                    <td class="w-40 text-center">
                                                        <div class="flex">
                                                            <div class="imw-10 imh-10 image-fit zoom-in text-center">
                                                                <a href="{{ $item->screen }}" target="_bank">
                                                                    <img alt="image" class="max-w-sm h-auto shadow-lg"
                                                                        src="{{ asset($item->screen ?? 'public/dist/images/preview-8.jpg') }}"
                                                                        title="Image">
                                                                </a>

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="w-40 text-center">
                                                        <div class="flex">
                                                            <div class="imw-10 imh-10 image-fit zoom-in text-center">
                                                                <a href="{{ $item->screen_back }}" target="_bank">
                                                                    <img alt="image" class="max-w-sm h-auto shadow-lg"
                                                                        src="{{ asset($item->screen_back ?? 'public/dist/images/preview-8.jpg') }}"
                                                                        title="Logo">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="w-40 text-center">
                                                        <div class="flex">
                                                            <div class="imw-10 imh-10 image-fit zoom-in text-center">
                                                                <a href="{{ $item->image }}" target="_bank">
                                                                    <img alt="image" class="max-w-sm h-auto shadow-lg"
                                                                        src="{{ asset($item->image ?? 'public/dist/images/preview-8.jpg') }}"
                                                                        title="Logo">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="w-40 text-center">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->motif ?? null }} </p>
                                                    </td>
                                                    <td class="w-40 text-center">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->validated_by ?? null }} </p>
                                                    </td>
                                                    <td class="w-40 text-center">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->validated_at ?? null }} </p>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == '1')
                                                            <div class="flex items-center justify-center text-success">

                                                                <a class="flex items-center mr-3">
                                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                                    Validez
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-danger">

                                                                <a class="flex items-center mr-3">
                                                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Invalidé
                                                                </a>

                                                            </div>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le
                                                    moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->

                <!-- END: Pagination -->

            </div>
        </div>

        <div id="transfers" class="tab-pane " role="tabpanel" aria-labelledby="transfers-tab">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>


                <div class="hidden md:block mx-auto text-slate-500"></div>


            </div>
            <div class="grid grid-cols-12 gap-6 mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des transfers d'argent
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant demandé
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Envoyer le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Traité le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Numero
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($transactions->count() > 0)
                                            @foreach ($transactions as $key => $item)
                                                <tr class="intro-x">
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->provider->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-50">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}</div>
                                                        </div>
                                                        <div class="text-slate-500  mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>

                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->type_transaction == 'Depot')
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                                    text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Depot
                                                            </span>
                                                            <p class="mb-3">
                                                                Montant envoyé :
                                                            </p>
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->usersCountry ?? (null->code ?? null) }}
                                                            <p class="mt-3">
                                                                Montant reçu
                                                            </p>
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ number_format($item->amount_received, 2, ',', ' ') ?? null }}
                                                                {{ $item->wallet->usersCountry ?? (null->code ?? null) }}
                                                            </span>
                                                        @elseif ($item->type_transaction == 'Transfert')
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                            text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                transfert
                                                            </span>
                                                            <p class="mb-3">
                                                                Montant envoyé :
                                                            </p>
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->usersCountry ?? (null->code ?? null) }}
                                                            <p class="mt-3">
                                                                Montant reçu
                                                            </p>
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ number_format($item->amount_received, 2, ',', ' ') ?? null }}
                                                                {{ $item->wallet->usersCountry ?? (null->code ?? null) }}
                                                            </span>
                                                        @else
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                            text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Retrait
                                                            </span>
                                                            <p class="mb-3">
                                                                Montant envoyé :
                                                            </p>
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->usersCountry ?? (null->code ?? null) }}
                                                        @endif


                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->wallet->wallet_id != $item->num_wallet)
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                        text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Transfers d'argent
                                                            </span>
                                                        @else
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                            text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Depot d'argent
                                                            </span>
                                                        @endif
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->transaction->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        @php
                                                            $wallet_info = \App\Models\Wallets\Wallet::where(
                                                                'wallet_id',
                                                                $item->num_wallet,
                                                            )->first();

                                                        @endphp
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $wallet_info->sidUser->phone ?? null }}
                                                            {{ $wallet_info->sidUser->num_wallet ?? null }}</p>
                                                        <p class="font-medium whitespace-nowrap mt-3">
                                                            {{ $wallet_info->sidUser->firstname ?? null }}
                                                            {{ $wallet_info->sidUser->lastname ?? null }}
                                                        </p>
                                                        <span
                                                            class="alert alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                text-indigo-100 bg-indigo-700 rounded"
                                                            style="color: white;">
                                                            {{ $item->phone ?? null }} {{ $item->num_wallet ?? null }}
                                                        </span>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == 'PENDING')
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    En-attente
                                                                </span>

                                                            </div>
                                                        @elseif ($item->status == 'ERROR')
                                                            <div class="flex items-center justify-center text-danger">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Refusé
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-success">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Accepté
                                                                    </span>


                                                                </div>

                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="w-56">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->status == 'PENDING')
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <a class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        href="javascript:;" title="Modifier">
                                                                        En-attente
                                                                    </a>
                                                                @endif
                                                            @elseif ($item->status == 'SUCCESS')
                                                                <a target="_bank"
                                                                    href="{{ route('transfers.invoice.print', $item->id) }}"
                                                                    class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                    title="Facture">
                                                                    Facture
                                                                </a>
                                                            @else
                                                                <div class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Aucune
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $transactions->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->
            </div>
        </div>
        <div id="reloads" class="tab-pane " role="tabpanel" aria-labelledby="reloads-tab">
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>
                <div class="hidden md:block mx-auto text-slate-500"></div>


                <div class="hidden md:block mx-auto text-slate-500"></div>


            </div>
            <div class="grid grid-cols-12 gap-6 mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des rechargements Robus
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant demandé
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Envoyer le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Numero
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($walletreloads->count() > 0)
                                            @foreach ($walletreloads as $key => $item)
                                                <tr class="intro-x">
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->provider->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-50">
                                                        <div class="text-slate-300 text-xs  mt-0.5 mb-2">
                                                            <div class="text-slate-300 text-xs  mt-0.5">
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}</div>
                                                        </div>
                                                        <div class="text-slate-500  mt-0.5">
                                                            <span
                                                                class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>

                                                    </td>
                                                    <td class="w-40">
                                                        <span
                                                            class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                            text-indigo-100 bg-indigo-700 rounded"
                                                            style="color: white;">
                                                            Depot
                                                        </span>
                                                        <p class="mb-3">
                                                            Montant envoyé :
                                                        </p>
                                                        {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                        {{ $item->wallet->usersCountry ?? (null->code ?? null) }}
                                                        <p class="mt-3">
                                                            Montant reçu
                                                        </p>
                                                        <span
                                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ number_format($item->amount_received, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->usersCountry ?? (null->code ?? null) }}
                                                        </span>

                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->wallet->wallet_id != $item->num_wallet)
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                        text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Transfers d'argent
                                                            </span>
                                                        @else
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                            text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Depot d'argent
                                                            </span>
                                                        @endif
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        @php
                                                            $wallet_info = \App\Models\Wallets\Wallet::where(
                                                                'wallet_id',
                                                                $item->account_wallet,
                                                            )->first();

                                                        @endphp
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $wallet_info->sidUser->phone ?? null }}
                                                            {{ $wallet_info->sidUser->num_wallet ?? null }}</p>
                                                        <p class="font-medium whitespace-nowrap mt-3">
                                                            {{ $wallet_info->sidUser->firstname ?? null }}
                                                            {{ $wallet_info->sidUser->lastname ?? null }}
                                                        </p>
                                                        <span
                                                            class="alert alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                text-indigo-100 bg-indigo-700 rounded"
                                                            style="color: white;">
                                                            {{ $item->account_wallet ?? null }}
                                                        </span>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == 'PENDING')
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    En-attente
                                                                </span>

                                                            </div>
                                                        @elseif ($item->status == 'ERROR')
                                                            <div class="flex items-center justify-center text-danger">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                        Refusé
                                                                    </span>

                                                                </div>
                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-success">
                                                                <div class="flex items-center justify-center text-pending">
                                                                    <span
                                                                        class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                        style="color: white;">
                                                                        Accepté
                                                                    </span>


                                                                </div>

                                                            </div>
                                                            <div class="flex items-center justify-center text-pending">
                                                                <span
                                                                    class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                    style="color: white;">
                                                                    Traiter
                                                                </span>

                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="w-56">
                                                        <div class="flex justify-center items-center">
                                                            @if ($item->status == 'PENDING')
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <a class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        href="javascript:;" title="Modifier">
                                                                        En-attente
                                                                    </a>
                                                                @endif
                                                            @elseif ($item->status == 'SUCCESS')
                                                                <a target="_bank"
                                                                    href="{{ route('reaload.invoice.print', $item->id) }}"
                                                                    class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                    title="Facture">
                                                                    Facture
                                                                </a>
                                                            @else
                                                                <div class="flex items-center justify-center text-dark">
                                                                    <div
                                                                        class="flex items-center justify-center text-pending">
                                                                        <span
                                                                            class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                            style="color: white;">
                                                                            Aucune
                                                                        </span>

                                                                    </div>
                                                                </div>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $transactions->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->
            </div>
        </div>
        <div id="employers" class="tab-pane" role="tabpanel" aria-labelledby="employers-tab">

            @livewire('companies.companie-employers', ['employers' => $user->id])


        </div>

        <div id="unders" class="tab-pane" role="tabpanel" aria-labelledby="unders-tab">

            @livewire('companies.under-distributors', ['underDistributors' => $user->id])
        </div>

        @if ($user->company->percent != null)
            <div id="percent" class="tab-pane" role="tabpanel" aria-labelledby="percent-tab">
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>
                    <div class="hidden md:block mx-auto text-slate-500"></div>

                    <div class="hidden md:block mx-auto text-slate-500"></div>

                </div>
                <div class="grid grid-cols-12 gap-6 mt-6">
                    <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                        <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">
                                Pourcentage
                            </h2>
                        </div>
                        <div id="new-products" class="tiny-slider py-5">
                            <div class="px-5">
                                <div class="overflow-x-auto">
                                    <table class="table table-bordered table-hover -mt-2 -intro-x ">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="whitespace-nowrap">Nom</th>
                                                <th class="whitespace-nowrap text-center">Pourcentage</th>
                                                <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                                                <th class="whitespace-nowrap text-center">Pourcentage companie depot</th>
                                                <th class="whitespace-nowrap text-center">Pourcentage companie retrait</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="intro-x">
                                                <td class="w-65">
                                                    {{ $user->company->percent->name ?? null }}

                                                </td>
                                                <td class="text-center">
                                                    <p class="font-medium whitespace-nowrap">
                                                        {{ $user->company->percent->percent * 100 ?? null }} %
                                                    </p>

                                                </td>
                                                <td class="text-center">
                                                    <p class="font-medium whitespace-nowrap">
                                                        {{ $user->company->percent->percent_admin * 100 ?? null }} %
                                                    </p>

                                                </td>
                                                <td class="text-center">
                                                    <p class="font-medium whitespace-nowrap">
                                                        {{ $user->company->percent->percent_deposit * 100 ?? null }} %
                                                    </p>

                                                </td>
                                                <td class="w-40">
                                                    <p class="font-medium whitespace-nowrap">
                                                        {{ $user->company->percent->percent_with_drawal * 100 ?? null }} %
                                                    </p>
                                                </td>

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif


    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        function sort_status(el) {
            $('#sort_status').submit();
        }
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('active-acccount-user.{{ $user->id }}').listen(
                '.active_account_user',
                (e) => {
                    console.log("It is working!");
                },
            )
        });
    </script>
@endpush
