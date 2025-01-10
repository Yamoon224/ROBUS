@extends('layouts.master')

@section('title', 'Details du client')

@section('content')

    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">
        <h2 class="intro-y text-lg font-medium mt-3 text-center">
            Details sur le client {{ $user->firstname }}
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
            @if (Auth::guard('web')->user()->hasPermissionTo('modifier-plafond'))
                <div class="ml-5 mr-6">
                    <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">

                        <form class="validate-form" method="POST"
                            action="{{ route('custumers.update_silver_ceiling', ['id' => $user->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <p for="silver_ceiling" class="mr-3">Augmenter le plafond
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
            @endif
            <div
                class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
                <div class="font-medium text-center lg:text-left lg:mt-3">Les details</div>
                <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                    <div class="truncate sm:whitespace-normal flex items-center"> <i data-feather="mail"
                            class="w-4 h-4 mr-2"></i> {{ $user->email ?? null }} </div>
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
                        {{ $user->phone ?? null }}
                    </div>
                    <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                            class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg> Creer : {{ $user->created_at ?? null }}
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
                        {{ $user->country->currencie->code ?? null }}
                    </div>
                </div>

            </div>

        </div>


        @if ($user->password == null)
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
        @else
            <div class="grid grid-cols-12 gap-6 mt-3">

                <div class="intro-y col-span-12 lg:col-span-12">
                    <!-- BEGIN: Form Validation -->
                    <div class="intro-y box">


                        <div class="p-3">
                            <h2 class="text-lg mt-3 font-medium truncate mr-5 mt-6">Modification du mot passe</h2>
                            <div class="">
                                @include('components.update_password_form')
                            </div>
                        </div>


                    </div>
                    <!-- END: Form Validation -->
                </div>
            </div>
        @endif
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
        @include('components.account_custumers_nav')

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
        <div id="order" class="tab-pane" role="tabpanel" aria-labelledby="order-tab">

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
                            Liste des commandes de carte
                        </h2>
                    </div>
                    <div id="new-products" class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table id="table_orders" class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>

                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Date
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                                                Client
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Type
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Pays/Ville
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Statut
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($walletOrderCarte->count() > 0)
                                            @foreach ($walletOrderCarte as $item)
                                                <tr class="intro-x">
                                                    <td class="w-40">
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>

                                                    <td>

                                                        <span
                                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ $item->wallet->sidUser->firstname ?? null }}
                                                            {{ $item->wallet->sidUser->lastname ?? null }}
                                                        </span>
                                                        <div class="text-slate-500  mt-0.5">
                                                            @if ($item->is_either == 1)
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ 'Commander : pour soit' }}
                                                                </span>
                                                            @elseif ($item->is_other == 1)
                                                                <span
                                                                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                    {{ 'Commander : pour autre' }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                    </td>
                                                    <td>
                                                        <p href="" class="font-medium whitespace-nowrap">
                                                            {{ $item->typeCard->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td>
                                                        <span
                                                            class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ $item->country->name ?? null }} Ville
                                                            :{{ $item->citie->name ?? null }}
                                                        </span>
                                                    </td>
                                                    <td class="w-40">
                                                        @if ($item->status == '1')
                                                            <div class="flex items-center justify-center text-success">

                                                                <a href="#"
                                                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                                    href="javascript:;"
                                                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i>
                                                                    Activé
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="flex items-center justify-center text-danger">


                                                                <a href="#"
                                                                    class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                                    href="javascript:;"
                                                                    title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
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
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $walletOrderCarte->links('vendor.pagination.custum') }}

                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>
        </div>
        <div id="carte" class="tab-pane" role="tabpanel" aria-labelledby="carte-tab">
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
                            Liste des rechargements de carte
                        </h2>
                    </div>
                    <div class=" py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Montant
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Envoyer le
                                            </th>
                                            <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                                Traité le
                                            </th>
                                            {{-- <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Montant recu
                                    </th> --}}
                                            {{-- <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Ref
                                    </th> --}}
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
                                        @if ($reloadCards->count() > 0)
                                            @foreach ($reloadCards as $key => $item)
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
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                        <p class="font-medium whitespace-nowrap mt-3">
                                                            Solde déposant :
                                                            {{ number_format($item->amount_solde, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->deposit->created_at ?? null }}</p>
                                                    </td>
                                                    {{-- <td class="w-40">
                                                <p class="font-medium whitespace-nowrap">
                                                    {{ $item->deposit->received_amount ?? null }}
                                                    {{ $usersCountry }}</p>
                                            </td> --}}
                                                    {{-- <td class="w-40">
                                                <p class="font-medium whitespace-nowrap">
                                                    {{ $item->deposit->transaction_id ?? null }}</p>
                                            </td> --}}
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->num_carte ?? null }}
                                                        </p>
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
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <a target="_bank"
                                                                        href="{{ route('reload_card.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                                @endif
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
                            {{ $reloadCards->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>
        </div>
        <div id="phone" class="tab-pane" role="tabpanel" aria-labelledby="phone-tab">
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
                            Liste des rechargements de credit telephonique
                        </h2>
                    </div>
                    <div class="py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table class="table table-bordered">
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
                                            {{-- <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Montant recu
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Ref
                                    </th> --}}
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
                                        @if ($phoneCredits->count() > 0)
                                            @foreach ($phoneCredits as $key => $item)
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
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                        <p class="font-medium whitespace-nowrap mt-3">
                                                            Solde déposant :
                                                            {{ number_format($item->amount_solde, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->paymentPhone->created_at ?? null }}</p>
                                                    </td>
                                                    {{-- <td class="w-40">
                                                <p  class="font-medium whitespace-nowrap">
                                                    {{ $item->paymentPhone->received_amount ?? null }}
                                                    {{ $usersCountry }}</p>
                                            </td>
                                            <td class="w-40">
                                                <p  class="font-medium whitespace-nowrap">
                                                    {{ $item->paymentPhone->transaction_id ?? null }}</p>
                                            </td> --}}
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->phone ?? null }}
                                                        </p>
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
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <a target="_bank"
                                                                        href="{{ route('phone.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                                @endif
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
                            {{ $phoneCredits->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>
        </div>
        <div id="inter" class="tab-pane" role="tabpanel" aria-labelledby="inter-tab">
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
                            Liste des transfers internationnaux
                        </h2>
                    </div>
                    <div class="py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table class="table table-bordered">
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
                                        @if ($Internationals->count() > 0)

                                            @foreach ($Internationals as $key => $item)
                                                <tr class="intro-x">
                                                    <td>
                                                        @if ($item->provider_type == 1)
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Paiement Mooney
                                                            </span>
                                                        @elseif ($item->provider_type == 5)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1
                                                mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Paiement Facture
                                                            </span>
                                                        @elseif ($item->provider_type == 6)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1 
                                                mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Credit telephonique
                                                            </span>
                                                        @elseif ($item->provider_type == 7)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 
                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Versement bancaire
                                                            </span>
                                                        @elseif($item->provider_type == 8)
                                                            <span
                                                                class="alert alert-success  inline-flex items-center justify-center px-2 py-1 mb-3 
                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Rechargement carte
                                                            </span>
                                                        @endif
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->provider->name ?? null }}
                                                        </p>

                                                    </td>
                                                    <td class="w-50">
                                                        <div class="mt-0.5 mb-2">
                                                            <div class="mt-0.5">
                                                                @if ($item->wallet->sidUser->is_under_distributer == 1)
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">
                                                                                Sous-distributeur</div>
                                                                        </div>
                                                                    </div>
                                                                @elseif($item->wallet->sidUser->is_custumers == 1)
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">Client
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div
                                                                        class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                        <div class="fc-event-title-container">
                                                                            <div class="fc-event-title fc-sticky">Admin
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                                {{ $item->wallet->sidUser->firstname ?? null }}
                                                                {{ $item->wallet->sidUser->lastname ?? null }}
                                                            </div>
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
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                        <p class="font-medium whitespace-nowrap mt-3">
                                                            Solde déposant :
                                                            {{ number_format($item->amount_solde, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->transactionInter->created_at ?? null }}</p>
                                                    </td>
                                                    {{-- <td class="w-40">
                                                <p  class="font-medium whitespace-nowrap">
                                                    {{ $item->transactionInter->received_amount ?? null }}
                                                    {{ $usersCountry }}</p>
                                            </td>
                                            <td class="w-40">
                                                <p  class="font-medium whitespace-nowrap">
                                                    {{ $item->transactionInter->transaction_id ?? null }}</p>
                                            </td> --}}
                                                    <td class="w-40">
                                                        @if ($item->provider_type == 8)
                                                            <div
                                                                class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                <div class="fc-event-title-container"
                                                                    style="color: white;">
                                                                    <div class="fc-event-title fc-sticky">
                                                                        Rechargement carte</div>
                                                                </div>
                                                            </div>
                                                            {{ $item->number_card ?? null }}
                                                        @elseif($item->provider_type == 5)
                                                            <div
                                                                class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                <div class="fc-event-title-container"
                                                                    style="color: white;">
                                                                    <div class="fc-event-title fc-sticky">
                                                                        Paiement Facture</div>

                                                                </div>
                                                            </div>
                                                            @if ($item->factureChoix_type == 1)
                                                                <div class="alert alert-dark show mb-2" role="alert">
                                                                    Facture
                                                                    prépayée</div>
                                                                {{ $item->id_invoice ?? null }}
                                                            @elseif($item->factureChoix_type == 2)
                                                                <div class="alert alert-dark show mb-2" role="alert">
                                                                    Facture
                                                                    Normale</div>
                                                                {{ $item->id_invoice ?? null }}
                                                            @elseif($item->paiementChoix_phone == 1)
                                                                <div class="alert alert-dark show mb-2" role="alert">
                                                                    Numero de telephone</div>
                                                                {{ $item->number_phone ?? null }}
                                                            @elseif ($item->paiementChoix_phone == 2)
                                                                <div class="alert alert-dark show mb-2" role="alert">
                                                                    Numero du decodeur</div>
                                                                {{ $item->number_decoder ?? null }}
                                                            @elseif($item->paiementChoix_phone == null && $item->factureChoix_type == null)
                                                                <div class="alert alert-dark show mb-2" role="alert">
                                                                    Numero du contrat</div>
                                                                {{ $item->number_contract ?? null }}
                                                            @endif
                                                        @elseif ($item->provider_type == 1)
                                                            <div
                                                                class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                <div class="fc-event-title-container"
                                                                    style="color: white;">
                                                                    <div class="fc-event-title fc-sticky">
                                                                        Paiement Mooney</div>
                                                                </div>
                                                            </div>
                                                            {{ $item->phone ?? null }}
                                                            {{ $item->number_compte ?? null }}
                                                        @elseif ($item->provider_type == 6)
                                                            <div
                                                                class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                <div class="fc-event-title-container"
                                                                    style="color: white;">
                                                                    <div class="fc-event-title fc-sticky">
                                                                        Credit telephonique</div>
                                                                </div>
                                                            </div>
                                                            {{ $item->phone ?? null }}
                                                        @elseif ($item->provider_type == 7)
                                                            <div
                                                                class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                <div class="fc-event-title-container"
                                                                    style="color: white;">
                                                                    <div class="fc-event-title fc-sticky">
                                                                        Versement bancaire</div>
                                                                </div>
                                                            </div>
                                                            {{ $item->number_compte ?? null }}
                                                        @endif
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
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <a target="_bank"
                                                                        href="{{ route('inters.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                                @endif
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
                            {{ $Internationals->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->
            </div>
        </div>
        <div id="paiement" class="tab-pane" role="tabpanel" aria-labelledby="paiement-tab">
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
                            Liste des paiements des factures
                        </h2>
                    </div>
                    <div class=" py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                <table class="table table-bordered">
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
                                            {{-- <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Montant recu
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Ref
                                    </th> --}}
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
                                        @if ($paiements->count() > 0)
                                            @foreach ($paiements as $key => $item)
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
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->payment->created_at ?? null }}</p>
                                                    </td>
                                                    {{-- <td class="w-40">
                                                <p  class="font-medium whitespace-nowrap">
                                                    {{ $item->payment->received_amount ?? null }}
                                                    {{ $usersCountry }}</p>
                                            </td>
                                            <td class="w-40">
                                                <p  class="font-medium whitespace-nowrap">
                                                    {{ $item->payment->transaction_id ?? null }}</p>
                                            </td> --}}
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            @if ($item->factureChoix_type == 1)
                                                                <div
                                                                    class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                    <div class="fc-event-title-container"
                                                                        style="color: white;">
                                                                        <div class="fc-event-title fc-sticky">
                                                                            Facture
                                                                            prépayée</div>
                                                                    </div>
                                                                </div>
                                                                {{ $item->id_invoice ?? null }}
                                                            @elseif($item->factureChoix_type == 2)
                                                                <div
                                                                    class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                    <div class="fc-event-title-container"
                                                                        style="color: white;">
                                                                        <div class="fc-event-title fc-sticky">
                                                                            Facture
                                                                            normale</div>
                                                                    </div>
                                                                </div>
                                                                {{ $item->id_invoice ?? null }}
                                                            @elseif($item->paiementChoix_phone == 1)
                                                                <div
                                                                    class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                    <div class="fc-event-title-container"
                                                                        style="color: white;">
                                                                        <div class="fc-event-title fc-sticky">

                                                                            Numero de telephone</div>
                                                                    </div>
                                                                </div>

                                                                {{ $item->number_phone ?? null }}
                                                            @elseif($item->paiementChoix_phone == 2)
                                                                <div
                                                                    class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                    <div class="fc-event-title-container"
                                                                        style="color: white;">
                                                                        <div class="fc-event-title fc-sticky">
                                                                            Numero du decodeur
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{ $item->number_decoder }}
                                                            @else
                                                                {{ $item->number_contract ?? null }}
                                                            @endif
                                                        </p>
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
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <a target="_bank"
                                                                        href="{{ route('paiements.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                                @endif
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
                            {{ $paiements->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
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
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville
                                                                :
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
                                                        @elseif ($item->type_transaction == 'Transfert')
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                            text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                transfert
                                                            </span>
                                                        @elseif ($item->type_transaction == 'Réequilibrage')
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                                 text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Réequilibrage
                                                            </span>
                                                        @else
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                            text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Retrait
                                                            </span>
                                                        @endif

                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                        <p class="font-medium whitespace-nowrap mt-3">
                                                            Solde déposant :
                                                            {{ number_format($item->amount_solde, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>

                                                        @if ($item->type_transaction == 'Réequilibrage')
                                                            @php
                                                                $wallet_info = App\Models\wallets\Wallet::where('wallet_id', $item->num_wallet)->first();

                                                            @endphp
                                                
                                                            <p class="font-medium whitespace-nowrap mt-3">
                                                                Solde bénéficiaire :
                                                                {{ number_format($item->amount_solde_drawal, 2, ',', ' ') ?? null }}
                                                                {{ $wallet_info->currencie->code ?? null }}
                                                            </p>
                                                        @else
                                                            <p class="font-medium whitespace-nowrap mt-3">
                                                                Solde bénéficiaire :
                                                                {{ number_format($item->amount_solde_drawal, 2, ',', ' ') ?? null }}
                                                                {{ $item->wallet->currencie->code ?? null }}</p>
                                                        @endif


                                                    </td>
                                                    <td class="w-40">

                                                        @if ($item->wallet && $item->wallet->wallet_id !== $item->num_wallet)
                                                            <span
                                                                class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                        text-indigo-100 bg-indigo-700 rounded"
                                                                style="color: white;">
                                                                Transfers d'argent {{ $item->wallet->wallet_id ?? null }}
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
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->phone ?? null }} {{ $item->num_wallet ?? null }}
                                                        </p>
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
        <div id="virement" class="tab-pane" role="tabpanel" aria-labelledby="virement-tab">
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
                            Liste des demandes de virements
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
                                            {{-- <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Montant recu
                                    </th>
                                    <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                        Ref
                                    </th> --}}
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
                                        @if ($virements->count() > 0)
                                            @foreach ($virements as $key => $item)
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
                                                                {{ $item->wallet->sidUser->country->name ?? null }} ville
                                                                :
                                                                {{ $item->wallet->sidUser->city->name ?? null }}
                                                            </span>
                                                        </div>

                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                        <p class="font-medium whitespace-nowrap mt-3">
                                                            Solde déposant :
                                                            {{ number_format($item->amount_solde, 2, ',', ' ') ?? null }}
                                                            {{ $item->wallet->currencie->code ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->created_at ?? null }}</p>
                                                    </td>
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->paymentBank->created_at ?? null }}</p>
                                                    </td>
                                                    {{-- <td class="w-40">
                                                <p class="font-medium whitespace-nowrap">
                                                    {{ $item->paymentBank->received_amount ?? null }}
                                                    {{ $usersCountry }}</p>
                                            </td>
                                            <td class="w-40">
                                                <p class="font-medium whitespace-nowrap">
                                                    {{ $item->paymentBank->transaction_id ?? null }}</p>
                                            </td> --}}
                                                    <td class="w-40">
                                                        <p class="font-medium whitespace-nowrap">
                                                            {{ $item->phone ?? null }}
                                                        </p>
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
                                                                @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                    <a target="_bank"
                                                                        href="{{ route('virements.invoice.print', $item->id) }}"
                                                                        class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                        title="Facture">
                                                                        Facture
                                                                    </a>
                                                                @endif
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
                            {{ $virements->links('vendor.pagination.custum') }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>
        </div>

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
    {{-- <script>
    window.Laravel = {'csrfToken': '{{csrf_token()}}'}

    window.Echo.private(`items.{{{$item->id}}}`)
    .listen('ItemUpdated', function(e) {
        console.log("It is working!");
    });

</script> --}}
@endpush
