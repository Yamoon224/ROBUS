@extends('layouts.master_company')

@section('title', 'Details sous distributeur')

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

                {{ number_format($reloadAmounts, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}

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
                {{ number_format($transactionAmounts, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
            </div>

            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($transaction_com, 2, ',', ' ') ?? null }}
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
            <div class="font-medium text-historique mt-6">Montant total Inter envoyé </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($InternationalAmounts, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($International_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
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
                {{ number_format($paiementAmounts, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($paiement_com, 2, ',', ' ') ?? null }}
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
            <div class="font-medium text-historique mt-6">Montant total virements</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($virementAmounts, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($virement_com, 2, ',', ' ') ?? null }}
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
            <div class="font-medium text-historique mt-6">Montant total credits telephonique</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($phoneCreditAmounts, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($phoneCredit_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
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
            <div class="font-medium text-historique mt-6">Montant total rechargements carte </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($reloadCardAmounts, 2, ',', ' ') ?? null }} {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($reloadCard_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
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
            <div class="font-medium text-historique mt-6">Montant total commande de carte </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($walletOrderCarteAmounts, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
            </div>
            <div class="font-medium text-historique mt-3">Commissions</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($walletOrderCarte_com, 2, ',', ' ') ?? null }}
                {{ $user->wallet->currencie->code ?? null }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in ">
            <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            </div>
            <div class="font-medium text-historique mt-6">Montant total commissions </div>
            <div class="text-slate-500 mt-3">
                {{ number_format($sum_wallet_transaction, 2, ',', ' ') ?? null }}
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
                        </svg> Date Mise a jour : {{ $user->updated_at ?? null }}
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
        @include('components.account_nav_master')

    </div>
    <!-- END: Profile Info -->

    <div class="tab-content mt-6">

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

        <div id="wave" class="tab-pane" role="tabpanel" aria-labelledby="wave-tab">
            @livewire('transaction-details.waves-users', ['walletWave' => $user->id])

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
