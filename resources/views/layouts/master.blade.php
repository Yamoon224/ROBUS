<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ROBUS">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - ROBUS</title>

    @include('layouts.shared.styles')

    <script type="module" src="{{ asset('public/build/assets/app.fe6fedf5.js') }}"></script>
    {{-- <script type="module"  src="{{ asset('public/build/assets/app.0a422702.js') }}"></script>
    {{-- <script type="module" crossorigin src="/boostrap/assets/app.0a422702.js"></script> --}}
    {{-- <link rel="stylesheet" href="/build/assets/app2.mjs">
    <link href="{{ asset('public/build/assets/app.b4f7846d.css') }}" rel="stylesheet"> --}}
    {{-- @vite('ressource/js/app.js') --}}
    {{-- <style>
        [x-cloak] {
            display: none !important;
        }
    </style> --}}


    @stack('styles')

    @livewireStyles
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css"> --}}

</head>


<body class="dark:bg-transparent">

    <!-- BEGIN: Content-->
    <div class="mobile-menu md:hidden">
        @include('layouts.shared.navMobile')
    </div>
    <div class="flex">
        @include('layouts.shared.nav')
        <div class="content">
            <div class="top-bar">
                @include('layouts.shared.side')
            </div>
            @yield('content')
            {{-- {{ $slot }} --}}
            {{-- @include('layouts.shared.footer') --}}
        </div>
    </div>
    @include('layouts.shared.scripts')

    @stack('scripts')
    @livewireScripts

    @include('layouts.scripts_toastr')

    <script>
        Livewire.on('activate-tab', (tabId) => {

            // Fonction pour activer l'onglet
            function activateTab(id) {
                // Désactiver tous les onglets
                $('.nav-link').removeClass('active').attr('aria-selected', false);
                $('.tab-pane').removeClass('active');

                // Activer l'onglet spécifié
                $(`#${id}-nav`).addClass('active').attr('aria-selected', true);
                $(`#${id}`).addClass('active');

                console.log(`✅ Onglet ${id} activé`);
            }

            // Activation immédiate
            activateTab(tabId);

            // Protection contre les désactivations (3 secondes max)
            let protectionCount = 0;
            const protectionInterval = setInterval(() => {
                protectionCount++;

                // Vérifier si l'onglet est toujours actif
                if (!$(`#${tabId}-nav`).hasClass('active')) {

                    activateTab(tabId);
                }

                // Arrêter après 3 secondes (30 tentatives à 100ms)
                if (protectionCount >= 30) {
                    clearInterval(protectionInterval);
                    console.log(`🛡️ Protection arrêtée pour ${tabId}`);
                }
            }, 100);
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-user_admin-modal').removeClass('hide');
            $('#disabled-user_admin-modal').addClass('show');
        });

        Livewire.on('show-disabled-users-modal', () => {
            $('#disabled-user_unders-modal').removeClass('hide');
            $('#disabled-user_unders-modal').addClass('show');
        });

        Livewire.on('show-drawals-modal', () => {
            $('#drawal-unders-modal').removeClass('hide');
            $('#drawal-unders-modal').addClass('show');
        });

        Livewire.on('show-password-modal', () => {
            $('#validated-password-modal').removeClass('hide');
            $('#validated-password-modal').addClass('show');
        });

        // Livewire.on('reloadModal', function () {
        //     $('#reload-modal').modal('show');
        // });

        Livewire.on('display-noney-modal', () => {
            $('#transactions').css('display', 'none');
        })

        Livewire.on('modal-hide', () => {

            //Update new Companie Modal
            $('#drawal-unders-modal').removeClass('show');
            $('#drawal-unders-modal').addClass('hide');

            $('#update-percent-companie-modal').removeClass('show');
            $('#update-percent-companie-modal').addClass('hide');

            $('#remonte-ccommission-modal').removeClass('show');
            $('#remonte-ccommission-modal').addClass('hide');

            $('#create-reload-company-modal').removeClass('show');
            $('#create-reload-company-modal').addClass('hide');

            $('#add-percent-companie-modal').removeClass('show');
            $('#add-percent-companie-modal').addClass('hide');

            $('#connected-company-under-modal').removeClass('show');
            $('#connected-company-under-modal').addClass('hide');

            $('#connected-company-modal').removeClass('show');
            $('#connected-company-modal').addClass('hide');

            $('#connected-master-modal').removeClass('show');
            $('#connected-master-modal').addClass('hide');

            $('#validated-password-modal').removeClass('show');
            $('#validated-password-modal').addClass('hide');

            $('#deconnected-company-modal').removeClass('show');
            $('#deconnected-company-modal').addClass('hide');

            $('#deleted-users-modal').removeClass('show');
            $('#deleted-users-modal').addClass('hide');

            // desabled master
            $('#disabled-master-admin-modal').removeClass('show');
            $('#disabled-master-admin-modal').addClass('hide');

            $('#disabled-countrieIntegrate-modal').removeClass('show');
            $('#disabled-countrieIntegrate-modal').addClass('hide');

            $('#disabled-company-admin-modal').removeClass('show');
            $('#disabled-company-admin-modal').addClass('hide');

            // desabled application
            $('#disabled-appRobus-modal').removeClass('show');
            $('#disabled-appRobus-modal').addClass('hide');

            $('#update-appRobus-modal').removeClass('show');
            $('#update-appRobus-modal').addClass('hide');

            // OnXbet Transaction
            $('#update-1xbet-transaction-modal').removeClass('show');
            $('#update-1xbet-transaction-modal').addClass('hide');

            $('#update-virement-bank-modal').removeClass('show');
            $('#update-virement-bank-modal').addClass('hide');

            $('#disabled-user_admin-modal').removeClass('show');
            $('#disabled-user_admin-modal').addClass('hide');

            $('#disabled-user_unders-modal').removeClass('show');
            $('#disabled-user_unders-modal').addClass('hide');

            // Levels inter
            $('#create-level-inter-modal').removeClass('show');
            $('#create-level-inter-modal').addClass('hide');

            $('#update-level-inter-modal').removeClass('show');
            $('#update-level-inter-modal').addClass('hide');

            // Levels inter desposit
            $('#update-level-deposit-inter-modal').removeClass('show');
            $('#update-level-deposit-inter-modal').addClass('hide');

            $('#update-level-deposit-ivoire-modal').removeClass('show');
            $('#update-level-deposit-ivoire-modal').addClass('hide');

            $('#update-level-bank-modal').removeClass('show');
            $('#update-level-bank-modal').addClass('hide');

            // Levels ivoires
            $('#create-level-ivoire-modal').removeClass('show');
            $('#create-level-ivoire-modal').addClass('hide');

            $('#update-level-ivoire-modal').removeClass('show');
            $('#update-level-ivoire-modal').addClass('hide');

            // Bank Cost
            $('#create-bank-modal').removeClass('show');
            $('#create-bank-modal').addClass('hide');

            $('#disabled-bank-modal').removeClass('show');
            $('#disabled-bank-modal').addClass('hide');

            $('#update-bank-modal').removeClass('show');
            $('#update-bank-modal').addClass('hide');

            // Order Card cost
            $('#create-order-card-modal').removeClass('show');
            $('#create-order-card-modal').addClass('hide');

            $('#disabled-order-card-modal').removeClass('show');
            $('#disabled-order-card-modal').addClass('hide');

            $('#update-order-card-modal').removeClass('show');
            $('#update-order-card-modal').addClass('hide');


            // Faqs
            $('#create-faqs-modal').removeClass('show');
            $('#create-faqs-modal').addClass('hide');

            $('#disabled-faqs-modal').removeClass('show');
            $('#disabled-faqs-modal').addClass('hide');

            $('#update-faqs-modal').removeClass('show');
            $('#update-faqs-modal').addClass('hide');

            $('#disabled-pubs-modal').removeClass('show');
            $('#disabled-pubs-modal').addClass('hide');


            $('#delete-pubs-modal').removeClass('show');
            $('#delete-pubs-modal').addClass('hide');

            // Deposit Cost
            $('#create-deposit-modal').removeClass('show');
            $('#create-deposit-modal').addClass('hide');

            $('#disabled-deposit-modal').removeClass('show');
            $('#disabled-deposit-modal').addClass('hide');

            $('#update-deposit-web-modal').removeClass('show');
            $('#update-deposit-web-modal').addClass('hide');

            $('#update-1xbet-transaction-modal').removeClass('show');
            $('#update-1xbet-transaction-modal').addClass('hide');

            $('#update-virement-bank-modal').removeClass('show');
            $('#update-virement-bank-modal').addClass('hide');

            //   Update CAfrique
            $('#update-trans-cafrique-modal').removeClass('show');
            $('#update-trans-cafrique-modal').addClass('hide');

            // Inter Cost
            $('#create-inter-modal').removeClass('show');
            $('#create-inter-modal').addClass('hide');


            $('#disabled-inter-modal').removeClass('show');
            $('#disabled-inter-modal').addClass('hide');

            $('#update-inter-modal').removeClass('show');
            $('#update-inter-modal').addClass('hide');

            // Currencies
            $('#create-currencie-modal').removeClass('show');
            $('#create-currencie-modal').addClass('hide');

            $('#disabled-currencie-modal').removeClass('show');
            $('#disabled-currencie-modal').addClass('hide');

            $('#update-currencie-modal').removeClass('show');
            $('#update-currencie-modal').addClass('hide');

            $('#delete-currencie-modal').removeClass('show');
            $('#delete-currencie-modal').addClass('hide');

            // Contact list
            $('#create-contact-modal').removeClass('show');
            $('#create-contact-modal').addClass('hide');

            $('#disabled-contact-modal').removeClass('show');
            $('#disabled-contact-modal').addClass('hide');

            $('#update-contact-modal').removeClass('show');
            $('#update-contact-modal').addClass('hide');

            $('#delete-contact-modal').removeClass('show');
            $('#delete-contact-modal').addClass('hide');

            // Contact 
            $('#create-contactList-modal').removeClass('show');
            $('#create-contactList-modal').addClass('hide');

            $('#disabled-contactList-modal').removeClass('show');
            $('#disabled-contactList-modal').addClass('hide');

            $('#update-contactList-modal').removeClass('show');
            $('#update-contactList-modal').addClass('hide');

            $('#delete-contactList-modal').removeClass('show');
            $('#delete-contactList-modal').addClass('hide');


            // Payment Cost
            $('#create-payment-modal').removeClass('show');
            $('#create-payment-modal').addClass('hide');

            $('#disabled-payment-modal').removeClass('show');
            $('#disabled-payment-modal').addClass('hide');

            $('#update-payment-modal').removeClass('show');
            $('#update-payment-modal').addClass('hide');

            // Phone credit
            $('#create-phone-modal').removeClass('show');
            $('#create-phone-modal').addClass('hide');

            $('#disabled-phone-modal').removeClass('show');
            $('#disabled-phone-modal').addClass('hide');

            $('#update-phone-modal').removeClass('show');
            $('#update-phone-modal').addClass('hide');


            // Reload 
            $('#create-reload-modal').removeClass('show');
            $('#create-reload-modal').addClass('hide');

            $('#disabled-reload-modal').removeClass('show');
            $('#disabled-reload-modal').addClass('hide');

            $('#update-reload-modal').removeClass('show');
            $('#update-reload-modal').addClass('hide');

            // Transaction 
            $('#create-transaction-modal').removeClass('show');
            $('#create-transaction-modal').addClass('hide');

            $('#disabled-transaction-modal').removeClass('show');
            $('#disabled-transaction-modal').addClass('hide');

            $('#update-transaction-modal').removeClass('show');
            $('#update-transaction-modal').addClass('hide');

            // Countries 
            $('#create-countrie-modal').removeClass('show');
            $('#create-countrie-modal').addClass('hide');

            $('#disabled-countrie-modal').removeClass('show');
            $('#disabled-countrie-modal').addClass('hide');

            $('#visitors-countrie-modal').removeClass('show');
            $('#visitors-countrie-modal').addClass('hide');

            $('#update-countrie-modal').removeClass('show');
            $('#update-countrie-modal').addClass('hide');


            // Cities 
            $('#create-citie-modal').removeClass('show');
            $('#create-citie-modal').addClass('hide');

            $('#disabled-citie-modal').removeClass('show');
            $('#disabled-citie-modal').addClass('hide');

            // Provider Wallet
            $('#create-provider-modal').removeClass('show');
            $('#create-provider-modal').addClass('hide');

            $('#disabled-provider-modal').removeClass('show');
            $('#disabled-provider-modal').addClass('hide');

            $('#disabled-orderCard-modal').removeClass('show');
            $('#disabled-orderCard-modal').addClass('hide');

            $('#update-provider-modal').removeClass('show');
            $('#update-provider-modal').addClass('hide');

            $('#delete-provider-modal').removeClass('show');
            $('#delete-provider-modal').addClass('hide');

            // Active card

            $('#active-update-card-modal').removeClass('show');
            $('#active-update-card-modal').addClass('hide');

            //Offers
            $('#create-offer-modal').removeClass('show');
            $('#create-offer-modal').addClass('hide');

            $('#disabled-offer-modal').removeClass('show');
            $('#disabled-offer-modal').addClass('hide');

            $('#update-offer-modal').removeClass('show');
            $('#update-offer-modal').addClass('hide');

            $('#delete-offer-modal').removeClass('show');
            $('#delete-offer-modal').addClass('hide');

            // New Modal sends
            $('#create-xbet-modal').removeClass('show');
            $('#create-xbet-modal').addClass('hide');

            $('#create-versement-modal').removeClass('show');
            $('#create-versement-modal').addClass('hide');

            $('#create-djamo-modal').removeClass('show');
            $('#create-djamo-modal').addClass('hide');

            $('#create-push-modal').removeClass('show');
            $('#create-push-modal').addClass('hide');


            $('#create-tresort-modal').removeClass('show');
            $('#create-tresort-modal').addClass('hide');

            $('#create-bank-send-modal').removeClass('show');
            $('#create-bank-send-modal').addClass('hide');

            $('#update-xbet-modal').removeClass('show');
            $('#update-xbet-modal').addClass('hide');

            $('#update-djamo-modal').removeClass('show');
            $('#update-djamo-modal').addClass('hide');

            $('#update-tresort-modal').removeClass('show');
            $('#update-tresort-modal').addClass('hide');

            $('#update-versement-modal').removeClass('show');
            $('#update-versement-modal').addClass('hide');

            $('#update-push-modal').removeClass('show');
            $('#update-push-modal').addClass('hide');

            $('#update-bank-send-modal').removeClass('show');
            $('#update-bank-send-modal').addClass('hide');

            // Inters transfers
            $('#create-interTransfers-modal').removeClass('show');
            $('#create-interTransfers-modal').addClass('hide');

            $('#create-interCivTransfers-modal').removeClass('show');
            $('#create-interCivTransfers-modal').addClass('hide');

            $('#update-interTransfers-modal').removeClass('show');
            $('#update-interTransfers-modal').addClass('hide');

            // Paiement Invoice
            $('#create-paiementInvoice-modal').removeClass('show');
            $('#create-paiementInvoice-modal').addClass('hide');

            $('#update-paiementInvoice-modal').removeClass('show');
            $('#update-paiementInvoice-modal').addClass('hide');

            $('#update-bankSend-modal').removeClass('show');
            $('#update-bankSend-modal').addClass('hide');

            // Paiement Histo

            $('#update-paiementHisto-modal').removeClass('show');
            $('#update-paiementHisto-modal').addClass('hide');

            $('#update-virementHisto-modal').removeClass('show');
            $('#update-virementHisto-modal').addClass('hide');

            // realod Wallet
            $('#create-reloadWallet-modal').removeClass('show');
            $('#create-reloadWallet-modal').addClass('hide');

            $('#create-reloadWalletCommission-modal').removeClass('show');
            $('#create-reloadWalletCommission-modal').addClass('hide');

            $('#create-transactionWalletCommission-modal').removeClass('show');
            $('#create-transactionWalletCommission-modal').addClass('hide');

            $('#update-reloadWallet-modal').removeClass('show');
            $('#update-reloadWallet-modal').addClass('hide');

            // realod cards
            $('#create-reloadCards-modal').removeClass('show');
            $('#create-reloadCards-modal').addClass('hide');

            $('#update-reloadCards-modal').removeClass('show');
            $('#update-reloadCards-modal').addClass('hide');

            $('#update-reloadCardHisto-modal').removeClass('show');
            $('#update-reloadCardHisto-modal').addClass('hide');

            // Transfers
            $('#create-transfersUV-modal').removeClass('show');
            $('#create-transfersUV-modal').addClass('hide');

            $('#validated-inter-modal').removeClass('show');
            $('#validated-inter-modal').addClass('hide');


            $('#validated-bankSend-modal').removeClass('show');
            $('#validated-bankSend-modal').addClass('hide');

            $('#validated-transfersUV-modal').removeClass('show');
            $('#validated-transfersUV-modal').addClass('hide');

            $('#validated-paiementInvoice-modal').removeClass('show');
            $('#validated-paiementInvoice-modal').addClass('hide');

            $('#validated-virement-modal').removeClass('show');
            $('#validated-virement-modal').addClass('hide');

            $('#validated-xbet-modal').removeClass('show');
            $('#validated-xbet-modal').addClass('hide');

            $('#validated-versement-modal').removeClass('show');
            $('#validated-versement-modal').addClass('hide');

            $('#validated-push-modal').removeClass('show');
            $('#validated-push-modal').addClass('hide');

            $('#validated-djamo-modal').removeClass('show');
            $('#validated-djamo-modal').addClass('hide');

            $('#validated-tresort-modal').removeClass('show');
            $('#validated-tresort-modal').addClass('hide');


            $('#validated-wave-modal').removeClass('show');
            $('#validated-wave-modal').addClass('hide');

            $('#validated-reloadCards-modal').removeClass('show');
            $('#validated-reloadCards-modal').addClass('hide');

            $('#validated-phoneCredit-modal').removeClass('show');
            $('#validated-phoneCredit-modal').addClass('hide');

            $('#update-transfersUV-modal').removeClass('show');
            $('#update-transfersUV-modal').addClass('hide');

            $('#update-transfersHisto-modal').removeClass('show');
            $('#update-transfersHisto-modal').addClass('hide');

            $('#update-transfersInterHisto-modal').removeClass('show');
            $('#update-transfersInterHisto-modal').addClass('hide');

            $('#update-transfersInterHisto-modal').removeClass('show');
            $('#update-transfersInterHisto-modal').addClass('hide');

            // Virement Bank
            $('#create-virementBank-modal').removeClass('show');
            $('#create-virementBank-modal').addClass('hide');

            $('#update-virementBank-modal').removeClass('show');
            $('#update-virementBank-modal').addClass('hide');

            // Phone credit
            $('#create-phoneCredit-modal').removeClass('hide');
            $('#create-phoneCredit-modal').addClass('show');

            $('#update-phoneCredit-modal').removeClass('show');
            $('#update-phoneCredit-modal').addClass('hide');

            $('#update-phoneCreditHisto-modal').removeClass('show');
            $('#update-phoneCreditHisto-modal').addClass('hide');


            // New valeur of Mobile Money
            //   Update CAfrique
            $('#update-trans-level_money-modal').removeClass('show');
            $('#update-trans-level_money-modal').addClass('hide');
            // $('body').css('overflow', 'auto');
        });


        Livewire.on('modal-show-remonte-ccommission', () => {
            $('#remonte-ccommission-modal').removeClass('hide');
            $('#remonte-ccommission-modal').addClass('show');
        });

        // new modal companies
        Livewire.on('show-update-percent-companie-modal', () => {
            $('#update-percent-companie-modal').removeClass('hide');
            $('#update-percent-companie-modal').addClass('show');

        });

        Livewire.on('show-reload-under-companie-modal', () => {
            $('#create-reload-company-modal').removeClass('hide');
            $('#create-reload-company-modal').addClass('show');

        });

        Livewire.on('show-add-percent-companie-modal', () => {
            $('#add-percent-companie-modal').removeClass('hide');
            $('#add-percent-companie-modal').addClass('show');

        });

        Livewire.on('show-disabled-percent-companie-modal', () => {
            $('#disabled-percent-companie-modal').removeClass('hide');
            $('#disabled-percent-companie-modal').addClass('show');

        });


        // End modal new
        Livewire.on('show-connected-master-modal', () => {
            $('#connected-master-modal').removeClass('hide');
            $('#connected-master-modal').addClass('show');
        });

        Livewire.on('show-connected-company-modal', () => {
            $('#connected-company-modal').removeClass('hide');
            $('#connected-company-modal').addClass('show');
        });

        Livewire.on('show-deleted-users-modal', () => {
            $('#deleted-users-modal').removeClass('hide');
            $('#deleted-users-modal').addClass('show');
        });

        Livewire.on('show-desactive-company-modal', () => {
            $('#deconnected-company-modal').removeClass('hide');
            $('#deconnected-company-modal').addClass('show');
        });

        Livewire.on('show-connected-company-under-modal', () => {
            $('#connected-company-under-modal').removeClass('hide');
            $('#connected-company-under-modal').addClass('show');
        });

        Livewire.on('show-onexbetrans-modal', () => {
            $('#update-1xbet-transaction-modal').removeClass('hide');
            $('#update-1xbet-transaction-modal').addClass('show');

        });

        Livewire.on('show-virementBank-modal', () => {
            $('#update-virement-bank-modal').removeClass('hide');
            $('#update-virement-bank-modal').addClass('show');

        });

        Livewire.on('show-depositWeb-modal', () => {
            $('#update-deposit-web-modal').removeClass('hide');
            $('#update-deposit-web-modal').addClass('show');

        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-master-admin-modal').removeClass('hide');
            $('#disabled-master-admin-modal').addClass('show');
        });

        Livewire.on('show-disabled-countrieIntegrate-modal', () => {
            $('#disabled-countrieIntegrate-modal').removeClass('hide');
            $('#disabled-countrieIntegrate-modal').addClass('show');
        });

        Livewire.on('show-disabled-App-modal', () => {
            $('#disabled-appRobus-modal').removeClass('hide');
            $('#disabled-appRobus-modal').addClass('show');
        });

        Livewire.on('modal-hide-inters', () => {


            // Inters transfers
            $('#create-interTransfers-modal').removeClass('show');
            $('#create-interTransfers-modal').addClass('hide');

            $('#create-interCivTransfers-modal').removeClass('show');
            $('#create-interCivTransfers-modal').addClass('hide');

            $('#update-interTransfers-modal').removeClass('show');
            $('#update-interTransfers-modal').addClass('hide');

            $('#validated-inter-modal').removeClass('show');
            $('#validated-inter-modal').addClass('hide');

            Livewire.emit('activate-tab', 'inter');


            // $('body').css('overflow', 'auto');
        });


        Livewire.on('modal-hide-phoneCredit', () => {

            // Phone credit
            $('#create-phoneCredit-modal').removeClass('show');
            $('#create-phoneCredit-modal').addClass('hide');

            $('#update-phoneCredit-modal').removeClass('show');
            $('#update-phoneCredit-modal').addClass('hide');

            $('#validated-phoneCredit-modal').removeClass('show');
            $('#validated-phoneCredit-modal').addClass('hide');
            Livewire.emit('activate-tab', 'phone');


        });

        Livewire.on('modal-hide-inters-validated', () => {

            // Inters transfers
            $('#create-interTransfers-modal').removeClass('show');
            $('#create-interTransfers-modal').addClass('hide');

            $('#update-interTransfers-modal').removeClass('show');
            $('#update-interTransfers-modal').addClass('hide');

            $('#validated-inter-modal').removeClass('show');
            $('#validated-inter-modal').addClass('hide');

            Livewire.emit('activate-tab', 'inter');


        });

        Livewire.on('modal-hide-phoneCredit-validated', () => {

            // Phone credit
            $('#create-phoneCredit-modal').removeClass('show');
            $('#create-phoneCredit-modal').addClass('hide');

            $('#update-phoneCredit-modal').removeClass('show');
            $('#update-phoneCredit-modal').addClass('hide');

            $('#validated-phoneCredit-modal').removeClass('show');
            $('#validated-phoneCredit-modal').addClass('hide');

            Livewire.emit('activate-tab', 'phone');

        });

        Livewire.on('modal-hide-phoneCreditHisto', () => {

            $('#update-phoneCreditHisto-modal').removeClass('show');
            $('#update-phoneCreditHisto-modal').addClass('hide');

            // $('body').css('overflow', 'auto');
        });

        Livewire.on('modal-hide-reloadCard', () => {

            // Relaod cards
            $('#create-reloadCards-modal').removeClass('show');
            $('#create-reloadCards-modal').addClass('hide');

            $('#update-reloadCards-modal').removeClass('show');
            $('#update-reloadCards-modal').addClass('hide');


            Livewire.emit('activate-tab', 'carte');



            // $('body').css('overflow', 'hidden');
        });

        Livewire.on('modal-hide-reloadCardHisto', () => {
            $('#update-reloadCardHisto-modal').removeClass('show');
            $('#update-reloadCardHisto-modal').addClass('hide');

        });

        Livewire.on('modal-hide-transfers-histo', () => {
            // Transfers Histo
            $('#update-transfersHisto-modal').removeClass('show');
            $('#update-transfersHisto-modal').addClass('hide');
        });

        Livewire.on('modal-hide-transferInters-histo', () => {
            // Transfers Histo
            $('#update-transfersInterHisto-modal').removeClass('show');
            $('#update-transfersInterHisto-modal').addClass('hide');
        });

        Livewire.on('modal-hide-validated-transaction', () => {
            // Transfers Histo
            $('#create-transfersUV-modal').removeClass('show');
            $('#create-transfersUV-modal').addClass('hide');
        });

        Livewire.on('modal-hide-transfers-validated', () => {

            // Transfers

            $('#create-transfersUV-modal').removeClass('show');
            $('#create-transfersUV-modal').addClass('hide');

            $('#validated-transfersUV-modal').removeClass('show');
            $('#validated-transfersUV-modal').addClass('hide');

            $('#update-transfersUV-modal').removeClass('show');
            $('#update-transfersUV-modal').addClass('hide');

            Livewire.emit('activate-tab', 'transfers');

        });

        Livewire.on('modal-hide-reloadCard-validated', () => {

            // Relaod cards
            $('#create-reloadCards-modal').removeClass('show');
            $('#create-reloadCards-modal').addClass('hide');

            $('#update-reloadCards-modal').removeClass('show');
            $('#update-reloadCards-modal').addClass('hide');

            $('#validated-reloadCards-modal').removeClass('show');
            $('#validated-reloadCards-modal').addClass('hide');

            Livewire.emit('activate-tab', 'carte');


            // $('body').css('overflow', 'hidden');
        });

        Livewire.on('modal-hide-transfers', () => {

            // Transfers
            $('#create-transfersUV-modal').removeClass('show');
            $('#create-transfersUV-modal').addClass('hide');

            $('#update-transfersUV-modal').removeClass('show');
            $('#update-transfersUV-modal').addClass('hide');

            // $('#validated-paiementInvoice-modal').removeClass('show');
            // $('#validated-paiementInvoice-modal').addClass('hide');




            // $('body').css('overflow', 'auto');
        });

        Livewire.on('modal-hide-paiement-validated', () => {

            // Paiement Invoice
            $('#create-paiementInvoice-modal').removeClass('show');
            $('#create-paiementInvoice-modal').addClass('hide');

            $('#update-paiementInvoice-modal').removeClass('show');
            $('#update-paiementInvoice-modal').addClass('hide');

            $('#validated-paiementInvoice-modal').removeClass('show');
            $('#validated-paiementInvoice-modal').addClass('hide');



            Livewire.emit('activate-tab', 'paiement');



        });

        Livewire.on('modal-bankSend-validted-show', () => {
            $('#validated-bankSend-modal').removeClass('hide');
            $('#validated-bankSend-modal').addClass('show');
            // $('body').removeClass('overflow');
        });

        Livewire.on('modal-hide-bankSend-validated', () => {


            $('#update-bankSend-modal').removeClass('show');
            $('#update-bankSend-modal').addClass('hide');

            $('#validated-bankSend-modal').removeClass('show');
            $('#validated-bankSend-modal').addClass('hide');


            Livewire.emit('activate-tab', 'bank-send');


        });

        Livewire.on('modal-hide-virement-validated', () => {

            // Virement Bank
            $('#create-virementBank-modal').removeClass('show');
            $('#create-virementBank-modal').addClass('hide');

            $('#update-virementBank-modal').removeClass('show');
            $('#update-virementBank-modal').addClass('hide');

            $('#validated-virement-modal').removeClass('show');
            $('#validated-virement-modal').addClass('hide');


            Livewire.emit('activate-tab', 'virement');


            // $('body').css('overflow', 'auto');
        });

        Livewire.on('modal-hide-xbet-validated', () => {

            $('#create-xbet-modal').removeClass('show');
            $('#create-xbet-modal').addClass('hide');


            $('#update-xbet-modal').removeClass('show');
            $('#update-xbet-modal').addClass('hide');

            $('#validated-xbet-modal').removeClass('show');
            $('#validated-xbet-modal').addClass('hide');

            Livewire.emit('activate-tab', 'xbet');
        });

        Livewire.on('modal-hide-versement-validated', () => {

            $('#create-versement-modal').removeClass('show');
            $('#create-versement-modal').addClass('hide');


            $('#update-versement-modal').removeClass('show');
            $('#update-versement-modal').addClass('hide');

            $('#validated-versement-modal').removeClass('show');
            $('#validated-versement-modal').addClass('hide');


            Livewire.emit('activate-tab', 'versment');

            // $('body').css('overflow', 'auto');
        });


        Livewire.on('modal-hide-push-validated', () => {

            $('#create-push-modal').removeClass('show');
            $('#create-push-modal').addClass('hide');


            $('#update-push-modal').removeClass('show');
            $('#update-push-modal').addClass('hide');

            $('#validated-push-modal').removeClass('show');
            $('#validated-push-modal').addClass('hide');

            Livewire.emit('activate-tab', 'push');



            // $('body').css('overflow', 'auto');
        });

        Livewire.on('modal-hide-djamo-validated', () => {

            $('#create-djamo-modal').removeClass('show');
            $('#create-djamo-modal').addClass('hide');


            $('#update-djamo-modal').removeClass('show');
            $('#update-djamo-modal').addClass('hide');

            $('#validated-djamo-modal').removeClass('show');
            $('#validated-djamo-modal').addClass('hide');

            Livewire.emit('activate-tab', 'djamo');



            // $('body').css('overflow', 'auto');
        });



        Livewire.on('modal-hide-tresort-validated', () => {

            $('#create-tresort-modal').removeClass('show');
            $('#create-tresort-modal').addClass('hide');


            $('#update-tresort-modal').removeClass('show');
            $('#update-tresort-modal').addClass('hide');

            $('#validated-tresort-modal').removeClass('show');
            $('#validated-tresort-modal').addClass('hide');

            Livewire.emit('activate-tab', 'tresort');




            // $('body').css('overflow', 'auto');
        });


        Livewire.on('modal-hide-wave-validated', () => {


            $('#update-wave-modal').removeClass('show');
            $('#update-wave-modal').addClass('hide');


            $('#validated-wave-modal').removeClass('show');
            $('#validated-wave-modal').addClass('hide');

            Livewire.emit('activate-tab', 'wave');



        });

        Livewire.on('modal-hide-paiement', () => {

            // Paiement Invoice
            $('#create-paiementInvoice-modal').removeClass('show');
            $('#create-paiementInvoice-modal').addClass('hide');

            $('#update-paiementInvoice-modal').removeClass('show');
            $('#update-paiementInvoice-modal').addClass('hide');

            Livewire.emit('activate-tab', 'paiement');




        });

        Livewire.on('modal-hide-bankSend', () => {

            // Paiement Invoice
            $('#create-bankSend-modal').removeClass('show');
            $('#create-bankSend-modal').addClass('hide');

            $('#update-bankSend-modal').removeClass('show');
            $('#update-bankSend-modal').addClass('hide');

            $('#validated-bankSend-modal').removeClass('show');
            $('#validated-bankSend-modal').addClass('hide');

            Livewire.emit('activate-tab', 'bank-send');



        });

        Livewire.on('modal-hide-paiementHisto', () => {
            // Paiement Invoice
            $('#update-paiementHisto-modal').removeClass('show');
            $('#update-paiementHisto-modal').addClass('hide');

        });

        Livewire.on('modal-hide-reload', () => {

            // realod Wallet
            $('#create-reloadWallet-modal').removeClass('show');
            $('#create-reloadWallet-modal').addClass('hide');

            $('#create-reloadWalletCommission-modal').removeClass('show');
            $('#create-reloadWalletCommission-modal').addClass('hide');

            $('#create-transactionWalletCommission-modal').removeClass('show');
            $('#create-transactionWalletCommission-modal').addClass('hide');

            $('#update-reloadWallet-modal').removeClass('show');
            $('#update-reloadWallet-modal').addClass('hide');

            Livewire.emit('activate-tab', 'reload');




        });
        Livewire.on('modal-hide-reload', () => {

            // realod Wallet
            $('#create-reloadWallet-modal').removeClass('show');
            $('#create-reloadWallet-modal').addClass('hide');

            $('#create-reloadWalletCommission-modal').removeClass('show');
            $('#create-reloadWalletCommission-modal').addClass('hide');

            $('#create-transactionWalletCommission-modal').removeClass('show');
            $('#create-transactionWalletCommission-modal').addClass('hide');


            $('#update-reloadWallet-modal').removeClass('show');
            $('#update-reloadWallet-modal').addClass('hide');


            Livewire.emit('activate-tab', 'reload');

            // $('body').css('overflow', 'auto');
        });

        Livewire.on('modal-hide-virementHisto', () => {

            // Virement Bank
            $('#update-virementHisto-modal').removeClass('show');
            $('#update-virementHisto-modal').addClass('hide');
        });

        Livewire.on('modal-hide-virement', () => {

            // Virement Bank
            $('#create-virementBank-modal').removeClass('show');
            $('#create-virementBank-modal').addClass('hide');

            $('#update-virementBank-modal').removeClass('show');
            $('#update-virementBank-modal').addClass('hide');

            Livewire.emit('activate-tab', 'virement');


        });

        Livewire.on('modal-hide-xbet', () => {

            $('#update-xbet-modal').removeClass('show');
            $('#update-xbet-modal').addClass('hide');

            $('#create-xbet-modal').removeClass('show');
            $('#create-xbet-modal').addClass('hide');


            Livewire.emit('activate-tab', 'xbet');



        });

        Livewire.on('modal-hide-push', () => {

            $('#update-push-modal').removeClass('show');
            $('#update-push-modal').addClass('hide');

            $('#create-push-modal').removeClass('show');
            $('#create-push-modal').addClass('hide');

            Livewire.emit('activate-tab', 'push');



        });

        Livewire.on('modal-hide-versement', () => {

            $('#update-versement-modal').removeClass('show');
            $('#update-versement-modal').addClass('hide');

            $('#create-versement-modal').removeClass('show');
            $('#create-versement-modal').addClass('hide');

            Livewire.emit('activate-tab', 'versment');


        });


        Livewire.on('modal-hide-tresort', () => {

            $('#update-tresort-modal').removeClass('show');
            $('#update-tresort-modal').addClass('hide');

            $('#create-tresort-modal').removeClass('show');
            $('#create-tresort-modal').addClass('hide');


            Livewire.emit('activate-tab', 'tresort');


            // $('body').css('overflow', 'auto');
        });

        Livewire.on('modal-hide-djamo', () => {

            $('#update-djamo-modal').removeClass('show');
            $('#update-djamo-modal').addClass('hide');

            $('#create-djamo-modal').removeClass('show');
            $('#create-djamo-modal').addClass('hide');

            Livewire.emit('activate-tab', 'djamo');

        });

        Livewire.on('modal-hide-wave', () => {

            $('#update-wave-modal').removeClass('show');
            $('#update-wave-modal').addClass('hide');

            Livewire.emit('activate-tab', 'wave');


        });

        // Hide modals Visitors

        Livewire.on('modal-hide-transfers-visitors-validated', () => {

            // Transfers

            $('#validated-transfers-visitors-modal').removeClass('show');
            $('#validated-transfers-visitors-modal').addClass('hide');

            $('#update-transfers-visitors-modal').removeClass('show');
            $('#update-transfers-visitors-modal').addClass('hide');

            $('#transfert-visitors-nav').addClass('active');
            $('transfert-visitors-nav').attr('aria-selected', true);
            $('#transfert-visitors').addClass('active');
            $('#inter-visitors-nav').removeClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');
            $('#carte-visitors-nav').removeClass('active');
            $('carte-visitors-nav').attr('aria-selected', false);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');


            // $('body').css('overflow', 'auto');
        });

        Livewire.on('modal-hide-reloadCard-visitors-validated', () => {

            // Relaod cards

            $('#update-reloadCards-visitors-modal').removeClass('show');
            $('#update-reloadCards-visitors-modal').addClass('hide');

            $('#validated-reloadCards-visitors-modal').removeClass('show');
            $('#validated-reloadCards-visitors-modal').addClass('hide');

            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');
            $('#inter-visitors-nav').removeClass('active');

            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');

            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#carte-visitors-nav').addClass('active');
            $('carte-visitors-nav').attr('aria-selected', true);
            $('#carte-visitors').addClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');

            // $('body').css('overflow', 'hidden');
        });

        Livewire.on('modal-hide-phoneCredit-visitors-validated', () => {

            // Relaod cards

            $('#update-phoneCredit-visitors-modal').removeClass('show');
            $('#update-phoneCredit-visitors-modal').addClass('hide');

            $('#validated-phoneCredit-visitors-modal').removeClass('show');
            $('#validated-phoneCredit-visitors-modal').addClass('hide');

            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');
            $('#inter-visitors-nav').removeClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#carte-visitors-nav').removeClass('active');

            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');

            $('carte-visitors-nav').attr('aria-selected', false);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').addClass('active');
            $('phone-visitors-nav').attr('aria-selected', true);
            $('#phone-visitors').addClass('active');

            // $('body').css('overflow', 'hidden');
        });


        Livewire.on('modal-hide-xbet-visitors-validated', () => {

            // Relaod cards

            $('#update-xbet-visitors-modal').removeClass('show');
            $('#update-xbet-visitors-modal').addClass('hide');

            $('#validated-xbet-visitors-modal').removeClass('show');
            $('#validated-xbet-visitors-modal').addClass('hide');

            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');
            $('#inter-visitors-nav').removeClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#carte-visitors-nav').removeClass('active');
            $('carte-visitors-nav').attr('aria-selected', false);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('#xbet-visitors').addClass('active');
            $('#xbet-visitors-nav').addClass('active');
            $('xbet-visitors-nav').attr('aria-selected', true);
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');

            // $('body').css('overflow', 'hidden');
        });


        Livewire.on('modal-hide-inters-visitors-validated', () => {

            // Relaod cards
            $('#update-inters-visitors-modal').removeClass('show');
            $('#update-inters-visitors-modal').addClass('hide');

            $('#validated-inters-visitors-modal').removeClass('show');
            $('#validated-inters-visitors-modal').addClass('hide');

            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');

            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');

            $('#inter-visitors-nav').addClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').addClass('active');
            $('#carte-visitors-nav').removeClass('active');
            $('carte-visitors-nav').attr('aria-selected', true);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');

            // $('body').css('overflow', 'hidden');
        });


        // Active menu Mobile Robus
        Livewire.on('menu-virement-active', () => {

            // Virement Bank
            Livewire.emit('activate-tab', 'virement');


        });

        Livewire.on('menu-paiement-active', () => {

            Livewire.emit('activate-tab', 'paiement');


        });

        Livewire.on('menu-transaction-active', () => {


        });

        Livewire.on('menu-phoneCredit-active', () => {


            Livewire.emit('activate-tab', 'phone');



        });

        Livewire.on('menu-bankSend-active', () => {


            Livewire.emit('activate-tab', 'bank-send');



        });

        Livewire.on('activate-menu', (menu) => {

            $('.nav-link').removeClass('active').attr('aria-selected', false);
            $('.tab-pane').removeClass('active');

            $('#' + menu + '-nav').addClass('active').attr('aria-selected', true);
            $('#' + menu).addClass('active');

        });


        // Garder les anciens événements pour compatibilité, mais les rediriger
        Livewire.on('menu-push-active', () => {
            Livewire.emit('activate-tab', 'push');
        });

        Livewire.on('menu-virement-active', () => {
            Livewire.emit('activate-tab', 'virement');
        });

        Livewire.on('menu-paiement-active', () => {
            Livewire.emit('activate-tab', 'paiement');
        });

        Livewire.on('menu-transaction-active', () => {
            Livewire.emit('activate-tab', 'transfers');
        });

        Livewire.on('menu-phoneCredit-active', () => {
            Livewire.emit('activate-tab', 'phone');
        });

        Livewire.on('menu-bankSend-active', () => {
            Livewire.emit('activate-tab', 'bank-send');
        });

        Livewire.on('menu-versement-active', () => {
            Livewire.emit('activate-tab', 'versment');
        });

        Livewire.on('menu-tresort-active', () => {
            Livewire.emit('activate-tab', 'tresort');
        });

        Livewire.on('menu-djamo-active', () => {
            Livewire.emit('activate-tab', 'djamo');
        });

        Livewire.on('menu-xbet-active', () => {
            Livewire.emit('activate-tab', 'xbet');
        });

        Livewire.on('menu-wave-active', () => {
            Livewire.emit('activate-tab', 'wave');
        });

        Livewire.on('menu-reload-active', () => {
            Livewire.emit('activate-tab', 'reload');
        });

        Livewire.on('menu-reloadCarte-active', () => {
            Livewire.emit('activate-tab', 'carte');
        });

        Livewire.on('menu-inters-active', () => {
            Livewire.emit('activate-tab', 'inter');
        });

        // Cas spécial pour reloadAcompte
        Livewire.on('menu-reloadAcompte-active', () => {
            console.log('🔄 Activation spéciale: commission');

            // ✅ Activer le menu "commission"
            $('#commission-tab a.nav-link').addClass('active').attr('aria-selected', true);
            $('#commission').addClass('active show');

            // ❌ Désactiver le menu "reload"
            $('#reload-tab a.nav-link').removeClass('active').attr('aria-selected', false);
            $('#reload').removeClass('active show');
        });

        // Garder activate-menu pour compatibilité
        Livewire.on('activate-menu', (menu) => {
            Livewire.emit('activate-tab', menu);
        });



        // Active Menu Visitors Robus

        Livewire.on('menu-transaction-vis-active', () => {
            $('#transfert-visitors-nav').addClass('active');
            $('transfert-visitors-nav').attr('aria-selected', true);
            $('#transfert-visitors').addClass('active');
            $('#inter-visitors-nav').removeClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');
            $('#carte-visitors-nav').removeClass('active');
            $('carte-visitors-nav').attr('aria-selected', false);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');
        });

        Livewire.on('menu-phoneCredit-vis-active', () => {
            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');
            $('#inter-visitors-nav').removeClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#carte-visitors-nav').removeClass('active');

            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');

            $('carte-visitors-nav').attr('aria-selected', false);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').addClass('active');
            $('phone-visitors-nav').attr('aria-selected', true);
            $('#phone-visitors').addClass('active');

        });

        Livewire.on('menu-xbet-vis-active', () => {

            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');
            $('#inter-visitors-nav').removeClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#carte-visitors-nav').removeClass('active');
            $('carte-visitors-nav').attr('aria-selected', false);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('#xbet-visitors').addClass('active');
            $('#xbet-visitors-nav').addClass('active');
            $('xbet-visitors-nav').attr('aria-selected', true);
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');

        });


        Livewire.on('menu-reloadCarte-vis-active', () => {

            // realod cards
            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');
            $('#inter-visitors-nav').removeClass('active');

            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');

            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#carte-visitors-nav').addClass('active');
            $('carte-visitors-nav').attr('aria-selected', true);
            $('#carte-visitors').addClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');

        });


        Livewire.on('menu-inters-vis-active', () => {

            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');

            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');

            $('#inter-visitors-nav').addClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').addClass('active');
            $('#carte-visitors-nav').removeClass('active');
            $('carte-visitors-nav').attr('aria-selected', true);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');
        });

        // Levels inter

        Livewire.on('modal-show', () => {
            $('#create-level-inter-modal').removeClass('hide');
            $('#create-level-inter-modal').addClass('show');
        });


        Livewire.on('update-level-inter-modal', () => {
            $('#update-level-inter-modal').removeClass('hide');
            $('#update-level-inter-modal').addClass('show');
        });

        Livewire.on('update-trans-cafrique-modal', () => {
            $('#update-trans-cafrique-modal').removeClass('hide');
            $('#update-trans-cafrique-modal').addClass('show');
        });

        Livewire.on('update-level-deposit-ivoire-modal', () => {
            $('#update-level-deposit-ivoire-modal').removeClass('hide');
            $('#update-level-deposit-ivoire-modal').addClass('show');
        });


        Livewire.on('update-level-bank-modal', () => {
            $('#update-level-bank-modal').removeClass('hide');
            $('#update-level-bank-modal').addClass('show');
        });


        Livewire.on('update-level-deposit-inter-modal', () => {
            $('#update-level-deposit-inter-modal').removeClass('hide');
            $('#update-level-deposit-inter-modal').addClass('show');
        });

        // Levels ivoire

        Livewire.on('modal-show', () => {
            $('#create-level-ivoire-modal').removeClass('hide');
            $('#create-level-ivoire-modal').addClass('show');
        });

        Livewire.on('update-level-ivoire-modal', () => {
            $('#update-level-ivoire-modal').removeClass('hide');
            $('#update-level-ivoire-modal').addClass('show');
        });

        // Faqs
        Livewire.on('modal-show', () => {
            $('#create-faqs-modal').removeClass('hide');
            $('#create-faqs-modal').addClass('show');
        });


        Livewire.on('show-disabled-modal', () => {
            $('#disabled-faqs-modal').removeClass('hide');
            $('#disabled-faqs-modal').addClass('show');
        });

        Livewire.on('show-disabled-pub-modal', () => {
            $('#disabled-pubs-modal').removeClass('hide');
            $('#disabled-pubs-modal').addClass('show');
        });

        Livewire.on('show-deleted-pub-modal', () => {
            $('#delete-pubs-modal').removeClass('hide');
            $('#delete-pubs-modal').addClass('show');
        });

        Livewire.on('show-disabled-card-modal', () => {
            $('#active-update-card-modal').removeClass('hide');
            $('#active-update-card-modal').addClass('show');
        });

        Livewire.on('update-faqs-modal', () => {
            $('#update-faqs-modal').removeClass('hide');
            $('#update-faqs-modal').addClass('show');
        });


        // Orders card Costs

        Livewire.on('modal-show', () => {
            $('#create-order-card-modal').removeClass('hide');
            $('#create-order-card-modal').addClass('show');
        });


        Livewire.on('show-disabled-modal', () => {
            $('#disabled-order-card-modal').removeClass('hide');
            $('#disabled-order-card-modal').addClass('show');
        });

        Livewire.on('update-order-card-modal', () => {
            $('#update-order-card-modal').removeClass('hide');
            $('#update-order-card-modal').addClass('show');
        });


        // Banks Costs
        Livewire.on('modal-show', () => {
            $('#create-bank-modal').removeClass('hide');
            $('#create-bank-modal').addClass('show');
        });


        Livewire.on('show-disabled-modal', () => {
            $('#disabled-bank-modal').removeClass('hide');
            $('#disabled-bank-modal').addClass('show');
        });

        Livewire.on('show-disabled-card-modal', () => {
            $('#active-update-card-modal').removeClass('hide');
            $('#active-update-card-modal').addClass('show');
        });

        Livewire.on('update-bank-modal', () => {
            $('#update-bank-modal').removeClass('hide');
            $('#update-bank-modal').addClass('show');
        });

        // Deposit Costs
        Livewire.on('modal-show', () => {
            $('#create-deposit-modal').removeClass('hide');
            $('#create-deposit-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-deposit-modal').removeClass('hide');
            $('#disabled-deposit-modal').addClass('show');
        });

        Livewire.on('update-deposit-modal', () => {
            $('#update-deposit-modal').removeClass('hide');
            $('#update-deposit-modal').addClass('show');
        });


        // Currencies
        Livewire.on('modal-show', () => {
            $('#create-currencie-modal').removeClass('hide');
            $('#create-currencie-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-currencie-modal').removeClass('hide');
            $('#disabled-currencie-modal').addClass('show');
        });

        Livewire.on('update-currencie-modal', () => {
            $('#update-currencie-modal').removeClass('hide');
            $('#update-currencie-modal').addClass('show');
        });

        Livewire.on('delete-currencie-modal', () => {
            $('#delete-currencie-modal').removeClass('hide');
            $('#delete-currencie-modal').addClass('show');
        });

        // Contact list
        Livewire.on('modal-show', () => {
            $('#create-contact-modal').removeClass('hide');
            $('#create-contact-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-contact-modal').removeClass('hide');
            $('#disabled-contact-modal').addClass('show');
        });

        Livewire.on('update-contact-modal', () => {
            $('#update-contact-modal').removeClass('hide');
            $('#update-contact-modal').addClass('show');
        });

        Livewire.on('delete-contact-modal', () => {
            $('#delete-contact-modal').removeClass('hide');
            $('#delete-contact-modal').addClass('show');
        });

        // Contacts
        Livewire.on('modal-show', () => {
            $('#create-contactList-modal').removeClass('hide');
            $('#create-contactList-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-contactList-modal').removeClass('hide');
            $('#disabled-contactList-modal').addClass('show');
        });

        Livewire.on('update-contactList-modal', () => {
            $('#update-contactList-modal').removeClass('hide');
            $('#update-contactList-modal').addClass('show');
        });

        Livewire.on('delete-contactList-modal', () => {
            $('#delete-contactList-modal').removeClass('hide');
            $('#delete-contactList-modal').addClass('show');
        });

        // Inter Costs
        Livewire.on('modal-show', () => {
            $('#create-inter-modal').removeClass('hide');
            $('#create-inter-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-inter-modal').removeClass('hide');
            $('#disabled-inter-modal').addClass('show');
        });

        Livewire.on('update-inter-modal', () => {
            $('#update-inter-modal').removeClass('hide');
            $('#update-inter-modal').addClass('show');
        });

        // Payment Costs
        Livewire.on('modal-show', () => {
            $('#create-payment-modal').removeClass('hide');
            $('#create-payment-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-payment-modal').removeClass('hide');
            $('#disabled-payment-modal').addClass('show');
        });

        Livewire.on('update-payment-modal', () => {
            $('#update-payment-modal').removeClass('hide');
            $('#update-payment-modal').addClass('show');
        });

        // Phone Costs reload
        Livewire.on('modal-show', () => {
            $('#create-phone-modal').removeClass('hide');
            $('#create-phone-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-phone-modal').removeClass('hide');
            $('#disabled-phone-modal').addClass('show');
        });

        Livewire.on('update-phone-modal', () => {
            $('#update-phone-modal').removeClass('hide');
            $('#update-phone-modal').addClass('show');
        });


        // Reload Costs  
        Livewire.on('modal-show', () => {
            $('#create-reload-modal').removeClass('hide');
            $('#create-reload-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-reload-modal').removeClass('hide');
            $('#disabled-reload-modal').addClass('show');
        });

        Livewire.on('update-reload-modal', () => {
            $('#update-reload-modal').removeClass('hide');
            $('#update-reload-modal').addClass('show');
        });

        // Offers

        Livewire.on('modal-show', () => {
            $('#create-offer-modal').removeClass('hide');
            $('#create-offer-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-offer-modal').removeClass('hide');
            $('#disabled-offer-modal').addClass('show');
        });

        Livewire.on('update-offer-modal', () => {
            $('#update-offer-modal').removeClass('hide');
            $('#update-offer-modal').addClass('show');
        });

        Livewire.on('delete-offer-modal', () => {
            $('#delete-offer-modal').removeClass('hide');
            $('#delete-offer-modal').addClass('show');
        });

        // Countries
        Livewire.on('show-disabled-modal', () => {
            $('#disabled-countrie-modal').removeClass('hide');
            $('#disabled-countrie-modal').addClass('show');
        });

        Livewire.on('show-visitors-modal', () => {
            $('#visitors-countrie-modal').removeClass('hide');
            $('#visitors-countrie-modal').addClass('show');
        });


        Livewire.on('update-countrie-modal', () => {
            $('#update-countrie-modal').removeClass('hide');
            $('#update-countrie-modal').addClass('show');
        });

        // Cities
        Livewire.on('show-disabled-modal', () => {
            $('#disabled-citie-modal').removeClass('hide');
            $('#disabled-citie-modal').addClass('show');
        });

        // Transaction
        Livewire.on('modal-show', () => {
            $('#create-transaction-modal').removeClass('hide');
            $('#create-transaction-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-transaction-modal').removeClass('hide');
            $('#disabled-transaction-modal').addClass('show');
        });

        Livewire.on('update-transaction-modal', () => {
            $('#update-transaction-modal').removeClass('hide');
            $('#update-transaction-modal').addClass('show');
        });

        // New valeur Mobile Money

        Livewire.on('update-trans-level_money-modal', () => {
            $('#update-trans-level_money-modal').removeClass('hide');
            $('#update-trans-level_money-modal').addClass('show');
        });


        // realod Wallet

        Livewire.on('modal-reloadWallet-show', () => {
            $('#create-reloadWallet-modal').removeClass('hide');
            $('#create-reloadWallet-modal').addClass('show');
            $('#reload-nav').addClass('active');
            $('reload-nav').attr('aria-selected', true);
            $('#reload').addClass('active');
            $('#comTransaction-nav').removeClass('active');
            $('comTransaction-nav').attr('aria-selected', false);
            $('#comTransaction').removeClass('active');

        });


        Livewire.on('modal-reloadWalletCommission-show', () => {
            $('#create-reloadWalletCommission-modal').removeClass('hide');
            $('#create-reloadWalletCommission-modal').addClass('show');
            $('#commission-nav').addClass('active');
            $('commission-nav').attr('aria-selected', true);
            $('#commission').addClass('active');
            $('#reload-nav').removeClass('active');
            $('reload-nav').attr('aria-selected', false);
            $('#reload').removeClass('active');
            $('#comTransaction-nav').removeClass('active');
            $('comTransaction-nav').attr('aria-selected', false);
            $('#comTransaction').removeClass('active');
        });

        Livewire.on('modal-transactionWalletCommission-show', () => {
            $('#create-transactionWalletCommission-modal').removeClass('hide');
            $('#create-transactionWalletCommission-modal').addClass('show');
            $('#commission-nav').removeClass('active');
            $('commission-nav').attr('aria-selected', true);
            $('#commission').removeClass('active');
            $('#reload-nav').removeClass('active');
            $('reload-nav').attr('aria-selected', false);
            $('#reload').removeClass('active');
            $('#comTransaction-nav').addClass('active');
            $('comTransaction-nav').attr('aria-selected', false);
            $('#comTransaction').addClass('active');
        });


        Livewire.on('update-reloadWallet-modal', () => {
            $('#update-reloadWallet-modal').removeClass('hide');
            $('#update-reloadWallet-modal').addClass('show');
            Livewire.emit('activate-tab', 'carte');



        });

        // Phone credit

        Livewire.on('modal-phoneCredit-show', () => {

            $('#create-phoneCredit-modal').removeClass('hide');
            $('#create-phoneCredit-modal').addClass('show');


            $('.nav-link').removeClass('active').attr('aria-selected', false);
            $('.tab-pane').removeClass('active');

            $('#phone-nav').addClass('active').attr('aria-selected', true);
            $('#phone').addClass('active');

            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
            $('#xbet-nav').removeClass('active');
            $('xbet-nav').attr('aria-selected', false);
        });


        Livewire.on('update-phoneCreditHisto-modal', () => {

            $('#update-phoneCreditHisto-modal').removeClass('hide');
            $('#update-phoneCreditHisto-modal').addClass('show');

        });


        Livewire.on('update-phoneCredit-modal', () => {

            $('#update-phoneCredit-modal').removeClass('hide');
            $('#update-phoneCredit-modal').addClass('show');

            Livewire.emit('activate-tab', 'phone');


        });


        // Reload cards 

        Livewire.on('modal-reloadCards-show', () => {

            $('#create-reloadCards-modal').removeClass('hide');
            $('#create-reloadCards-modal').addClass('show');

            Livewire.emit('activate-tab', 'carte');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
            $('#xbet-nav').removeClass('active');
            $('xbet-nav').attr('aria-selected', false);

        });


        Livewire.on('update-reloadCards-modal', () => {

            $('#update-reloadCards-modal').removeClass('hide');
            $('#update-reloadCards-modal').addClass('show');

            Livewire.emit('activate-tab', 'carte');


        });


        Livewire.on('update-reloadCardHisto-modal', () => {

            $('#update-reloadCardHisto-modal').removeClass('hide');
            $('#update-reloadCardHisto-modal').addClass('show');

        });

        // Paiement Invoice

        Livewire.on('modal-paiements-show', () => {
            $('#create-paiementInvoice-modal').removeClass('hide');
            $('#create-paiementInvoice-modal').addClass('show');
            Livewire.emit('activate-tab', 'paiement');

            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-hide');
            $('#info-paie-hide-2').addClass('info-paiement-hide');
            $('#info-paie-hide-3').addClass('info-paiement-hide');
            $('#info-paie-hide-4').addClass('info-paiement-hide');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-show');
            $('#info-paie-show-2').addClass('info-paiement-show');
            $('#info-paie-show-3').addClass('info-paiement-show');
            $('#info-paie-show-4').addClass('info-paiement-show');
        });


        Livewire.on('update-paiementInvoice-modal', () => {
            $('#update-paiementInvoice-modal').removeClass('hide');
            $('#update-paiementInvoice-modal').addClass('show');
            Livewire.emit('activate-tab', 'paiement');
        });

        Livewire.on('update-bankSend-modal', () => {
            $('#update-bankSend-modal').removeClass('hide');
            $('#update-bankSend-modal').addClass('show');
            Livewire.emit('activate-tab', 'bank-send');

        });


        Livewire.on('update-xbet-modal', () => {
            $('#update-xbet-modal').removeClass('hide');
            $('#update-xbet-modal').addClass('show');

            Livewire.emit('activate-tab', 'xbet');

        });

        Livewire.on('update-wave-modal', () => {
            $('#update-wave-modal').removeClass('hide');
            $('#update-wave-modal').addClass('show');

            Livewire.emit('activate-tab', 'xbet');

        });

        Livewire.on('update-paiementHisto-modal', () => {
            $('#update-paiementHisto-modal').removeClass('hide');
            $('#update-paiementHisto-modal').addClass('show');
        });

        Livewire.on('modal-virement-validted-show', () => {
            $('#validated-virement-modal').removeClass('hide');
            $('#validated-virement-modal').addClass('show');
            // $('body').removeClass('overflow');
        });

        // Transfers UV Show validated Password

        Livewire.on('modal-transfers-validted-show', () => {
            $('#validated-transfersUV-modal').removeClass('hide');
            $('#validated-transfersUV-modal').addClass('show');
            // $('body').removeClass('overflow');
        });

        Livewire.on('modal-inter-validted-show', () => {
            $('#validated-inter-modal').removeClass('hide');
            $('#validated-inter-modal').addClass('show');
            // $('body').removeClass('overflow');
        });

        Livewire.on('modal-phoneCredit-validted-show', () => {
            $('#validated-phoneCredit-modal').removeClass('hide');
            $('#validated-phoneCredit-modal').addClass('show');
        });

        Livewire.on('modal-reloadCards-validted-show', () => {
            $('#validated-reloadCards-modal').removeClass('hide');
            $('#validated-reloadCards-modal').addClass('show');
        });

        Livewire.on('modal-paiement-validted-show', () => {
            $('#validated-paiementInvoice-modal').removeClass('hide');
            $('#validated-paiementInvoice-modal').addClass('show');
            // $('body').removeClass('overflow');
        });

        Livewire.on('modal-xbet-validted-show', () => {
            $('#validated-xbet-modal').removeClass('hide');
            $('#validated-xbet-modal').addClass('show');
            // $('body').removeClass('overflow');
        });

        Livewire.on('modal-versement-validted-show', () => {
            $('#validated-versement-modal').removeClass('hide');
            $('#validated-versement-modal').addClass('show');
            // $('body').removeClass('overflow');
        });

        Livewire.on('modal-push-validted-show', () => {
            $('#validated-push-modal').removeClass('hide');
            $('#validated-push-modal').addClass('show');
            // $('body').removeClass('overflow');
        });

        Livewire.on('modal-djamo-validted-show', () => {
            $('#validated-djamo-modal').removeClass('hide');
            $('#validated-djamo-modal').addClass('show');
            // $('body').removeClass('overflow');
        });

        Livewire.on('modal-tresort-validted-show', () => {
            $('#validated-tresort-modal').removeClass('hide');
            $('#validated-tresort-modal').addClass('show');
            // $('body').removeClass('overflow');
        });


        // Validated Show Visitors

        Livewire.on('modal-transfers-visitors-validted-show', () => {
            $('#validated-transfers-visitors-modal').removeClass('hide');
            $('#validated-transfers-visitors-modal').addClass('show');
        });

        Livewire.on('modal-inter-visitors-validted-show', () => {
            $('#validated-inters-visitors-modal').removeClass('hide');
            $('#validated-inters-visitors-modal').addClass('show');
        });

        Livewire.on('modal-phoneCredit-visitors-validted-show', () => {
            $('#validated-phoneCredit-visitors-modal').removeClass('hide');
            $('#validated-phoneCredit-visitors-modal').addClass('show');
        });

        Livewire.on('modal-reloadCards-visitors-validted-show', () => {
            $('#validated-reloadCards-visitors-modal').removeClass('hide');
            $('#validated-reloadCards-visitors-modal').addClass('show');
        });


        Livewire.on('modal-xbet-visitors-validted-show', () => {
            $('#validated-xbet-visitors-modal').removeClass('hide');
            $('#validated-xbet-visitors-modal').addClass('show');
        });

        // Update informations Mobile

        Livewire.on('modal-transfers-show', () => {
            $('#create-transfersUV-modal').removeClass('hide');
            $('#create-transfersUV-modal').addClass('show');

            Livewire.emit('activate-tab', 'transfers');

            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-hide');
            $('#info-paie-hide-2').addClass('info-paiement-hide');
            $('#info-paie-hide-3').addClass('info-paiement-hide');
            $('#info-paie-hide-4').addClass('info-paiement-hide');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-show');
            $('#info-paie-show-2').addClass('info-paiement-show');
            $('#info-paie-show-3').addClass('info-paiement-show');
            $('#info-paie-show-4').addClass('info-paiement-show');
            $('#xbet-nav').removeClass('active');
            $('xbet-nav').attr('aria-selected', false);
            // $('body').removeClass('overflow');

        });

        Livewire.on('update-transfersUV-modal', () => {
            $('#update-transfersUV-modal').removeClass('hide');
            $('#update-transfersUV-modal').addClass('show');

            Livewire.emit('activate-tab', 'transfers');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-hide');
            $('#info-paie-hide-2').addClass('info-paiement-hide');
            $('#info-paie-hide-3').addClass('info-paiement-hide');
            $('#info-paie-hide-4').addClass('info-paiement-hide');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-show');
            $('#info-paie-show-2').addClass('info-paiement-show');
            $('#info-paie-show-3').addClass('info-paiement-show');
            $('#info-paie-show-4').addClass('info-paiement-show');
            $('#xbet-nav').removeClass('active');
            $('xbet-nav').attr('aria-selected', false);
        });

        Livewire.on('update-transfersHisto-modal', () => {
            $('#update-transfersHisto-modal').removeClass('hide');
            $('#update-transfersHisto-modal').addClass('show');
        });


        Livewire.on('update-transfersInterHisto-modal', () => {
            $('#update-transfersinterHisto-modal').removeClass('hide');
            $('#update-transfersInterHisto-modal').addClass('show');
        });

        // Update details valeurs visitors
        Livewire.on('update-transfers-visitors-modal', () => {
            $('#update-transfers-visitors-modal').removeClass('hide');
            $('#update-transfers-visitors-modal').addClass('show');
            $('#transfert-visitors-nav').addClass('active');
            $('transfert-visitors-nav').attr('aria-selected', true);
            $('#transfert-visitors').addClass('active');
            $('#inter-visitors-nav').removeClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');
            $('#carte-visitors-nav').removeClass('active');
            $('carte-visitors-nav').attr('aria-selected', false);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');
        });


        Livewire.on('update-phoneCredit-visitors-modal', () => {
            $('#update-phoneCredit-visitors-modal').removeClass('hide');
            $('#update-phoneCredit-visitors-modal').addClass('show');
            $('#transfert-visitors-nav').addClass('active');
            $('transfert-visitors-nav').attr('aria-selected', true);
            $('#transfert-visitors').addClass('active');
            $('#inter-visitors-nav').removeClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');
            $('#carte-visitors-nav').removeClass('active');
            $('carte-visitors-nav').attr('aria-selected', false);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');
        });

        Livewire.on('update-xbet-visitors-modal', () => {
            $('#update-xbet-visitors-modal').removeClass('hide');
            $('#update-xbet-visitors-modal').addClass('show');
            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');
            $('#inter-visitors-nav').removeClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#carte-visitors-nav').removeClass('active');
            $('carte-visitors-nav').attr('aria-selected', false);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('#xbet-visitors').addClass('active');
            $('#xbet-visitors-nav').addClass('active');
            $('xbet-visitors-nav').attr('aria-selected', true);
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');
        });

        Livewire.on('update-inters-visitors-modal', () => {
            $('#update-inters-visitors-modal').removeClass('hide');
            $('#update-inters-visitors-modal').addClass('show');
            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');
            $('#inter-visitors-nav').removeClass('active');
            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#carte-visitors-nav').removeClass('active');
            $('carte-visitors-nav').attr('aria-selected', false);
            $('#carte-visitors').removeClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('#xbet-visitors').addClass('active');
            $('#xbet-visitors-nav').addClass('active');
            $('xbet-visitors-nav').attr('aria-selected', true);
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');
        });

        Livewire.on('update-reloadCarte-visitors-modal', () => {
            $('#update-reloadCards-visitors-modal').removeClass('hide');
            $('#update-reloadCards-visitors-modal').addClass('show');
            $('#transfert-visitors-nav').removeClass('active');
            $('transfert-visitors-nav').attr('aria-selected', false);
            $('#transfert-visitors').removeClass('active');
            $('#inter-visitors-nav').removeClass('active');

            $('#xbet-visitors-nav').removeClass('active');
            $('xbet-visitors-nav').attr('aria-selected', false);
            $('#xbet-visitors').removeClass('active');

            $('inter-visitors-nav').attr('aria-selected', false);
            $('#inter-visitors').removeClass('active');
            $('#carte-visitors-nav').addClass('active');
            $('carte-visitors-nav').attr('aria-selected', true);
            $('#carte-visitors').addClass('active');
            $('#phone-visitors-nav').removeClass('active');
            $('phone-visitors-nav').attr('aria-selected', false);
            $('#phone-visitors').removeClass('active');
        });

        // Inters Transfers

        Livewire.on('modal-inters-show', () => {
            $('#create-interTransfers-modal').removeClass('hide');
            $('#create-interTransfers-modal').addClass('show');

            Livewire.emit('activate-tab', 'inter');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });

        Livewire.on('modal-interCivs-show', () => {
            $('#create-interCivTransfers-modal').removeClass('hide');
            $('#create-interCivTransfers-modal').addClass('show');
            $('#reload-nav').removeClass('active');
            $('reload-nav').attr('aria-selected', false);
            $('#reload').removeClass('active');
            $('#transfers-nav').removeClass('active');
            $('transfers-nav').attr('aria-selected', false);
            $('#transfers').removeClass('active');
            $('#paiement-nav').removeClass('active');
            $('paiement-nav').attr('aria-selected', false);
            $('#paiement').removeClass('active');
            $('#virement-nav').removeClass('active');
            $('virement-nav').attr('aria-selected', false);
            $('#virement').removeClass('active');
            $('#inter-nav').addClass('active');
            $('inter-nav').attr('aria-selected', false);
            $('#inter').addClass('active');
            $('#carte-nav').removeClass('active');
            $('carte-nav').attr('aria-selected', false);
            $('#carte').removeClass('active');
            $('#phone-nav').removeClass('active');
            $('phone-nav').attr('aria-selected', false);
            $('#phone').removeClass('active');
            $('#xbet-nav').removeClass('active');
            $('xbet-nav').attr('aria-selected', false);

            $('#wave-nav').removeClass('active');
            $('wave-nav').attr('aria-selected', false);
            $('#wave').removeClass('active');

            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });

        // New Modal sends
        Livewire.on('modal-xbet-show', () => {

            $('#create-xbet-modal').removeClass('hide');
            $('#create-xbet-modal').addClass('show');

            Livewire.emit('activate-tab', 'xbet');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');

        });

        Livewire.on('modal-djamo-show', () => {

            $('#create-djamo-modal').removeClass('hide');
            $('#create-djamo-modal').addClass('show');


            Livewire.emit('activate-tab', 'djamo');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');

        });

        Livewire.on('modal-versement-show', () => {

            $('#create-versement-modal').removeClass('hide');
            $('#create-versement-modal').addClass('show');

            Livewire.emit('activate-tab', 'versment');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');

        });

        Livewire.on('modal-bank-send-show', () => {

            $('#create-bank-send-modal').removeClass('hide');
            $('#create-bank-send-modal').addClass('show');

            Livewire.emit('activate-tab', 'bank-send');

            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');

        });

        Livewire.on('modal-push-show', () => {

            $('#create-push-modal').removeClass('hide');
            $('#create-push-modal').addClass('show');

            Livewire.emit('activate-tab', 'push');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');

        });

        Livewire.on('modal-tresort-show', () => {

            $('#create-tresort-modal').removeClass('hide');
            $('#create-tresort-modal').addClass('show');

            Livewire.emit('activate-tab', 'tresort');

            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').removeClass('info-paiement-show');
            $('#info-paie-hide-2').removeClass('info-paiement-show');
            $('#info-paie-hide-3').removeClass('info-paiement-show');
            $('#info-paie-hide-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-hide');
            $('#info-paie-show-2').removeClass('info-paiement-hide');
            $('#info-paie-show-3').removeClass('info-paiement-hide');
            $('#info-paie-show-4').removeClass('info-paiement-hide');

        });

        Livewire.on('update-xbet-modal', () => {

            $('#update-xbet-modal').removeClass('hide');
            $('#update-xbet-modal').addClass('show');

            Livewire.emit('activate-tab', 'xbet');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });

        Livewire.on('update-djamo-modal', () => {

            $('#update-djamo-modal').removeClass('hide');
            $('#update-djamo-modal').addClass('show');


            Livewire.emit('activate-tab', 'djamo');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });

        Livewire.on('update-versement-modal', () => {

            $('#update-versement-modal').removeClass('hide');
            $('#update-versement-modal').addClass('show');

            Livewire.emit('activate-tab', 'versment');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });

        Livewire.on('update-push-modal', () => {

            $('#update-push-modal').removeClass('hide');
            $('#update-push-modal').addClass('show');

            Livewire.emit('activate-tab', 'push');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });

        Livewire.on('update-tresort-modal', () => {

            $('#update-tresort-modal').removeClass('hide');
            $('#update-tresort-modal').addClass('show');


            Livewire.emit('activate-tab', 'tresort');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });

        Livewire.on('update-bank-send-modal', () => {

            $('#update-bank-send-modal').removeClass('hide');
            $('#update-bank-send-modal').addClass('show');


            Livewire.emit('activate-tab', 'bank-send');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });

        // Update Inter Modal
        Livewire.on('update-interTransfers-modal', () => {

            $('#update-interTransfers-modal').removeClass('hide');
            $('#update-interTransfers-modal').addClass('show');


            Livewire.emit('activate-tab', 'inter');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });

        // Virement Bank

        Livewire.on('modal-virements-show', () => {
            $('#create-virementBank-modal').removeClass('hide');
            $('#create-virementBank-modal').addClass('show');

            Livewire.emit('activate-tab', 'virement');


            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });


        Livewire.on('update-virementBank-modal', () => {

            $('#update-virementBank-modal').removeClass('hide');
            $('#update-virementBank-modal').addClass('show');
            Livewire.emit('activate-tab', 'virement');



            $('#info-paie-hide-1').removeClass('info-paiement-hide');
            $('#info-paie-hide-2').removeClass('info-paiement-hide');
            $('#info-paie-hide-3').removeClass('info-paiement-hide');
            $('#info-paie-hide-4').removeClass('info-paiement-hide');
            $('#info-paie-hide-1').addClass('info-paiement-show');
            $('#info-paie-hide-2').addClass('info-paiement-show');
            $('#info-paie-hide-3').addClass('info-paiement-show');
            $('#info-paie-hide-4').addClass('info-paiement-show');
            $('#info-paie-show-1').removeClass('info-paiement-show');
            $('#info-paie-show-2').removeClass('info-paiement-show');
            $('#info-paie-show-3').removeClass('info-paiement-show');
            $('#info-paie-show-4').removeClass('info-paiement-show');
            $('#info-paie-show-1').addClass('info-paiement-hide');
            $('#info-paie-show-2').addClass('info-paiement-hide');
            $('#info-paie-show-3').addClass('info-paiement-hide');
            $('#info-paie-show-4').addClass('info-paiement-hide');
        });


        Livewire.on('update-virementHisto-modal', () => {
            $('#update-virementHisto-modal').removeClass('hide');
            $('#update-virementHisto-modal').addClass('show');
        });

        Livewire.on('update-AppRobus-modal', () => {
            $('#update-appRobus-modal').removeClass('hide');
            $('#update-appRobus-modal').addClass('show');
        });

        // Provider Wallet
        Livewire.on('modal-show', () => {
            $('#create-provider-modal').removeClass('hide');
            $('#create-provider-modal').addClass('show');
        });

        Livewire.on('show-disabled-modal', () => {
            $('#disabled-provider-modal').removeClass('hide');
            $('#disabled-provider-modal').addClass('show');
        });

        Livewire.on('show-disabledOrder-modal', () => {
            $('#disabled-orderCard-modal').removeClass('hide');
            $('#disabled-orderCard-modal').addClass('show');
        });


        Livewire.on('update-provider-modal', () => {
            $('#update-provider-modal').removeClass('hide');
            $('#update-provider-modal').addClass('show');
        });

        Livewire.on('delete-provider-modal', () => {
            $('#delete-provider-modal').removeClass('hide');
            $('#delete-provider-modal').addClass('show');
        });
    </script>

</body>

</html>
