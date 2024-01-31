@extends('layouts.master')

@section('title', 'Details du master')

@section('content')
    <h2 class="text-lg font-medium truncate mr-5 mt-6">Telechargé les differents raports d'activités</h2>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('reloads.historical.received.reporting_reload_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport réchargements
            </a>
        </div>
        {{-- New --}}
        <div class="col-span-12 sm:col-span-4 2xl:col-span-3  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('reloads.historical.reporting_reload_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport des comptes réchargés
            </a>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('transfers.historical.reporting_transfer_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport transferts
            </a>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3  ">
            <a class="  flex items-center text-slate-600 dark:text-slate-300 btn btn-primary"
                href="{{ route('transfers.historical.received.reporting_transfer_historical.admins', ['id' => $user->wallet->id]) }}"
                style="color: white;">
                Rapport transferts récus
            </a>
        </div>



    </div>
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

                {{ number_format($reaload_amount, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}

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
                {{ number_format($reload_amount, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}

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
                {{ number_format($transaction_amoun, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total transactions recu </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($transaction_all_amount, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in ">
                <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-trending-down font-medium-5">
                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                        <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                </div>
                <div class="font-medium text-historique mt-6">Montant total commissions </div>
                <div class="text-slate-500 mt-3">
                    {{ number_format($transaction_all_amount, 2, ',', ' ') ?? null }}
                    {{ $user->wallet->currencie->code ?? null }}
                </div>
            </div>
    

    </div>

    <!-- BEGIN: Profile Info -->
    <div class="intro-y box px-5 pt-5 mt-5">

        <h2 class="intro-y text-lg font-medium mt-3 text-center">
            Details sur l'equipe {{ $user->firstname }}
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
        @include('components.account_masters')

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

                <!-- END: Pagination -->

            </div>
        </div>

        <div id="reload" class="tab-pane" role="tabpanel" aria-labelledby="reload-tab">
            @livewire('masters.reload-masters', ['master_reloads' => $user->id])

          
        </div>
        <div id="inter" class="tab-pane" role="tabpanel" aria-labelledby="inter-tab">

            @livewire('masters.inter-masters', ['master_inters' => $user->id])

         
        </div>
        <div id="under_distributors" class="tab-pane " role="tabpanel" aria-labelledby="under_distributors-tab">

            @livewire('masters.active-masters', ['masters' => $user->id])

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
