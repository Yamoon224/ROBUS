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

         @include('components.info_detail_wallet')

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
   <div class="grid grid-cols-12 gap-6 mt-3">

        <div class="intro-y col-span-4 lg:col-span-12">
            <div class="intro-y box">

                <div class="p-5">
                    <h2 class="text-lg mt-3 font-medium truncate mr-5 mt-6">Modification du Numero</h2>

                    <div class="" id="step-2">
                        @include('components.update_phone_form')

                    </div>
                </div>

            </div>
        </div>

        <div class="intro-y col-span-4 lg:col-span-12">
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
         <div class="intro-y col-span-4 lg:col-span-12">
            <div class="intro-y box">

                <div class="p-5">
                    <h2 class="text-lg mt-3 font-medium truncate mr-5 mt-6">Envoie des notification</h2>

                    <div class="" id="step-2">
                        @include('components.send_notfication_form')

                    </div>
                </div>

            </div>
        </div>
    </div>

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

         <div id="push" class="tab-pane" role="tabpanel" aria-labelledby="push-tab">
            @livewire('transaction-details.pushs', ['walletPushs' => $user->id])

        </div>
        <div id="tresort" class="tab-pane" role="tabpanel" aria-labelledby="tresort-tab">
            @livewire('transaction-details.tresort-moneys', ['walletTresorts' => $user->id])

        </div>
        <div id="djamo" class="tab-pane" role="tabpanel" aria-labelledby="djamo-tab">
            @livewire('transaction-details.djamos', ['walletDjamos' => $user->id])
        </div>
        <div id="versments" class="tab-pane" role="tabpanel" aria-labelledby="versments-tab">
            @livewire('transaction-details.versments', ['walletVersements' => $user->id])
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
