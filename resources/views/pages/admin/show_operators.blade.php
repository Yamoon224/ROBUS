@extends('layouts.master')

@section('title', 'Details du sous distributeurs')

@php

    $currencie = $user->wallet->currencie->code;
@endphp

@section('content')
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total reçu des rechargements</div>
            <div class="text-slate-500 mt-3">

                {{ number_format($sum_wallet_reload_received, 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}

            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total compte rechargés </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_Reload'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}

            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total des Transactions </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_transaction'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total transactions recus </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($sum_wallet_transaction_received, 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total Inter rechargé </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($sum_wallet_Inter_received, 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total Inter envoyé </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_Inter'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total paiements</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_payment'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total virements</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_bank'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total credits telephonique</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_phone'], 2, ',', ' ') ?? null }} {{ $currencie ?? null }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_table_factures">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total rechargements carte telephonique</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_deposit'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_user1">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total 1Xbet depot</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_xbet_depot'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style_user2">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total 1Xbet retrait</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($totalSumAmounts['sum_wallet_xbet_retrait'], 2, ',', ' ') ?? null }}
                {{ $currencie ?? null }}
            </div>
        </div>

        

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-pendd">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-alert-circle font-medium-5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">En attente</div>
            <div class="text-slate-500 mt-3">
                {{ $pending_success ?? null }}
            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-check font-medium-5">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <div class="font-medium text-historique text-white mt-6">Success</div>
            <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">
                {{ $sum_success ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error ">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-x-circle font-medium-5">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <div class="font-medium text-historique mt-6">Echec</div>
            <div class="text-slate-500 mt-3">
                {{ $sum_error ?? null }}
            </div>
        </div>

    </div>
    <h2 class="text-lg font-medium truncate mr-5 mt-6">Telechargé les differents raports d'activités</h2>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('reloads.historical.received.reporting_reload_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport réchargements
            </a>
        </div>
        {{-- New --}}
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('reloads.historical.reporting_reload_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport des comptes réchargés
            </a>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('cards.historical.reporting_card_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport rechargement carte
            </a>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('virements.historical.reporting_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport virements
            </a>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('transfers.historical.reporting_transfer_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport transferts
            </a>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('transfers.historical.received.reporting_transfer_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport transferts récus
            </a>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('payments.historical.reporting_payment_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport paiements
            </a>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-2  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('inters.historical.reporting_inter_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport inter
            </a>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary "
                href="{{ route('phones.historical.reporting_phone_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport credits
            </a>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-2 " style="background-color: red">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary text-white"
                style="background-color: red"
                href="{{ route('echecs.historical.reporting_transfer_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport des échecs
            </a>
        </div>


    </div>

    @if ($user->password == null)
        <div class="grid grid-cols-12 gap-6 mt-3">

            <div class="intro-y col-span-12 lg:col-span-12">
                <!-- BEGIN: Form Validation -->
                <div class="intro-y box">

                    <div class="p-5">
                        <div class="" id="step-2">
                            <form class="form-vertical row"
                                action="{{ route('admin.update_password', ['id' => $user->id]) }}" method="post"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="grid grid-cols-12 gap-6 mt-3 items-center">
                                    <div class="col-span-12 lg:col-span-4">
                                        <label class="form-label">Mot de passe <span class="text-danger"> *</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            id="password" required maxlength="4">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-span-12 lg:col-span-4">
                                        <label class="form-label">Confirmer Mot de passe <span
                                                class="text-danger">*</span></label>
                                        <input type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            name="password_confirmation" id="password_confirmation" required
                                            maxlength="4">
                                    </div>
                                    <div class="col-span-12 lg:col-span-4">
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-primary w-24  mb-2 mt-6 ">Enregistrer</button>
                                        </div>

                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>


                </div>
                <!-- END: Form Validation -->
            </div>
        </div>
    @endif
    <div class="grid grid-cols-12 gap-6 mt-3">

        <div class="intro-y col-span-12 lg:col-span-12">
            <!-- BEGIN: Form Validation -->
            <div class="intro-y box">

                <div class="p-5">
                    <div class="" id="step-2">
                        <form class="form-vertical row" action="{{ route('admin.update_phone', ['id' => $user->id]) }}"
                            method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-12 gap-6 mt-3 items-center">
                                <div class="col-span-12 lg:col-span-4">
                                    <label class="form-label">Ancien numero <span class="text-danger"> *</label>
                                    <span
                                        class="alert alert-secondary mr-10 inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                        Phone :{{ $user->phone ?? null }}
                                    </span>

                                </div>
                                <div class="col-span-12 lg:col-span-4">
                                    <label class="form-label">Modifier le numero <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('new_phone') is-invalid @enderror"
                                        name="new_phone" id="new_phone" maxlength="15">
                                </div>
                                <div class="col-span-12 lg:col-span-4">
                                    <div class="text-center">
                                        <button type="submit"
                                            class="btn btn-primary w-24  mb-2 mt-6 ">Enregistrer</button>
                                    </div>

                                </div>

                            </div>

                        </form>
                    </div>
                </div>


            </div>
            <!-- END: Form Validation -->
        </div>
    </div>
    <h2 class="text-lg font-medium truncate mr-5 mt-6">Liste des operateurs :</h2>
    @foreach ($user->operator_services as $opera)
        <span
            class="alert alert-success font-assign-list inline-flex items-center justify-center px-2 py-1 mb-3 text-xs leading-none text-indigo-100 bg-indigo-700 rounded">
            {{ $opera->operator->name ?? null }}
        </span>
    @endforeach
    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        @if ($user->email_agent != null)
            @php
                $user_agent = App\Models\Sid\SidUser::where('email', $user->email_agent)->first();
            @endphp
            <span
                class="alert alert-secondary mr-10 inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                Le nom de l'agent qui l'a enregistré : {{ $user_agent->firstname }} {{ $user_agent->lastname }} <br>
            </span>
            <span
                class="alert alert-secondary mr-10 inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                Email & phone : {{ $user_agent->email }} {{ $user_agent->phone }}
            </span>
        @endif
        <h2 class="intro-y text-lg font-medium mt-3 text-center">
            Details sur l'equipe {{ $user->firstname }}
        </h2>
        <h2 class="font-medium text-base mr-auto mt-3 mb-3">
            <a href="{{ URL::previous() }}" class="btn btn-sm">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                    fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
            </a>
        </h2>


        <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">

            <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                    <img alt="Midone - HTML Admin Template"
                        src="{{ asset($user->image ?? 'public/dist/images/profil.png') }}">
                </div>
                <div class="ml-5">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg mb-3">
                        {{ $user->lastname ?? null }} {{ $user->firstname ?? null }}
                    </div>
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                        Code wallet : <br> {{ $user->wallet->wallet_id ?? null }}
                    </div>
                </div>

            </div>

            <div class="ml-5 mr-6">
                <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                    <form class="validate-form" method="POST"
                        action="{{ route('custumers.update_silver_admin_ceiling', ['id' => $user->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <p for="silver_ceiling" class="mr-3">Deplafonnez en
                            {{ $user->country->currencie->code ?? null }}
                        </p>
                        <input type="text" name="silver_ceiling"
                            class="form-control @error('silver_ceiling') is-invalid @enderror"
                            value="{{ $user->silver_ceiling ?? 'Application' }}" required>

                        @error('silver_ceiling')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <button type="submit" class="btn btn-primary w-20 me-1 mb-2 mt-6"
                            style="height: 2rem;">MAJ</button>
                    </form>
                </div>
            </div>

            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Les details</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-feather="mail"
                            class="w-4 h-4 mr-2"></i> {{ $user->email ?? 'Inconnu' }} </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="mic" data-lucide="mic"
                            class="lucide lucide-mic w-4 h-4 text-slate-500 ml-auto mr-2">
                            <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z"></path>
                            <path d="M19 10v2a7 7 0 01-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="23"></line>
                            <line x1="8" y1="23" x2="16" y2="23"></line>
                        </svg>
                        {{ $user->phone ?? 'Inconnu' }}
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                            class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg> Creer : {{ $user->created_at ?? 'Inconnu' }}
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                            class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg> Date connexion : {{ $user->last_login ?? null }}
                    </div>

                    <div class="truncate sm:whitespace-normal flex items-center mt-3">

                        @if ($user->last_logout == null)
                            <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                                    class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg> Toujours connecté
                            </div>
                        @else
                            <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                                    class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg> Date deconnexion : {{ $user->last_logout ?? null }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            <div
                class="mt-6 lg:mt-0 flex-1 flex items-center justify-center px-5 border-t lg:border-0 mr-3 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0 color-wallet">
                <div class="text-center rounded-md  py-3" style="color: white; font-size: 16px;">
                    <div class="font-medium text-lg mb-3">Solde Wallet</div>
                    <div class="font-medium text-primary text-xl" style="color: white;">
                        {{ number_format($user->wallet()->sum('amount'), 2, ',', ' ') ?? null }}
                        {{ $user->country->currencie->code ?? null }}</div>
                </div>

            </div>

        </div>
        @include('components.account_nav')

    </div>
    <!-- END: Profile Info -->

    <div class="tab-content mt-6">

        {{-- <div id="doc" class="tab-pane active" role="tabpanel" aria-labelledby="doc-tab">

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


            </div>
        </div> --}}
        <div id="reload" class="tab-pane" role="tabpanel" aria-labelledby="reload-tab">

            @livewire('transaction-details.reload-transactions', ['walletAllReloads' => $user->id])
        </div>
        <div id="transfers" class="tab-pane " role="tabpanel" aria-labelledby="transfers-tab">
        
            @livewire('transaction-details.transactions-users', ['walletAllTransactions' => $user->id])

        </div>

        <div id="paiement" class="tab-pane" role="tabpanel" aria-labelledby="paiement-tab">
            @livewire('transaction-details.payment-users', ['walletAllPayments' => $user->id])

        </div>
        <div id="order" class="tab-pane" role="tabpanel" aria-labelledby="order-tab">
            @livewire('transaction-details.orders-users', ['walletAllOrders' => $user->id])

        </div>
        <div id="virement" class="tab-pane" role="tabpanel" aria-labelledby="virement-tab">
            @livewire('transaction-details.virements-users', ['walletAllVirements' => $user->id])

        </div>
        <div id="phone" class="tab-pane" role="tabpanel" aria-labelledby="phone-tab">
            @livewire('transaction-details.phone-credit-users', ['walletAllPhoneCredits' => $user->id])

        </div>
      <div id="inter" class="tab-pane" role="tabpanel" aria-labelledby="inter-tab">
            @livewire('transaction-details.inters-users', ['walletAllInters' => $user->id])

        </div>
        

        <div id="carte" class="tab-pane" role="tabpanel" aria-labelledby="carte-tab">
            @livewire('transaction-details.carte-users', ['walletAllDeposits' => $user->id])

        </div>
        
        
        <div id="xbet" class="tab-pane" role="tabpanel" aria-labelledby="xbet-tab">
            @livewire('transaction-details.xbets-users', ['walletAllXbets' => $user->id])

        </div>





    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        function sort_status(el) {
            $('#sort_status').submit();
        }
    </script>
@endpush
