<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions pour les transactions
    </h2>


    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <h2 class="alert alert-dark  intro-y text-lg font-medium">
                Liste des commissions des differentes transactions d'un distributeur vers un client de son pays
            </h2>

            <table class="table table-bordered table-hover -intro-x mt-6">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                        <th class="whitespace-nowrap text-center">Pourcentage S-agent depot</th>
                        <th class="whitespace-nowrap text-center">Pourcentage S-agent retrait</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($centre_afriques->count() > 0)
                        @foreach ($centre_afriques->where('distributer_custumer', true) as $item)
                            <tr class="intro-x">
                                <td class="w-65">
                                    {{-- <p class="font-medium whitespace-nowrap"> --}}
                                    {{ $item->name ?? null }}
                                    {{-- </p> --}}

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_admin * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_deposit * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="w-40">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_with_drawal * 100 ?? null }} %</p>
                                </td>

                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit({{ $item->id }}, 'update-trans-cafrique-modal')"
                                                class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                                title="Modifier">
                                                Modifier
                                            </a>
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
        <!-- END: Data List -->
    </div>
    <div class="grid grid-cols-12 gap-6">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <h2 class="alert alert-dark  intro-y text-lg font-medium">
                Envoie d'un compte d'un compte client vers un client de son pays
            </h2>

            <table class="table table-bordered table-hover -intro-x mt-6">
                <thead class="table-dark">
                    <tr>
                        <th class="whitespace-nowrap">Nom</th>
                        <th class="whitespace-nowrap text-center">Pourcentage</th>
                        <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                        <th class="whitespace-nowrap text-center">Pourcentage S-agent retrait</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($centre_afriques->count() > 0)
                        @foreach ($centre_afriques->where('custumer_custumer', true) as $item)
                            <tr class="intro-x">
                                <td class="w-65">
                                    {{-- <p class="font-medium whitespace-nowrap"> --}}
                                    {{ $item->name ?? null }}
                                    {{-- </p> --}}

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent * 100 ?? null }} %
                                    </p>

                                </td>
                                <td class="text-center">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_admin * 100 ?? null }} %
                                    </p>

                                </td>

                                <td class="w-40">
                                    <p class="font-medium whitespace-nowrap">
                                        {{ $item->percent_with_drawal * 100 ?? null }} %</p>
                                </td>

                                <td class="w-56">
                                    <div class="flex justify-center items-center">
                                        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                            <a wire:click="edit_caf_caf({{ $item->id }}, 'update-trans-cafrique-modal')"
                                                class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                                title="Modifier">
                                                Modifier
                                            </a>
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
        <!-- END: Data List -->
    </div>
    <h2 class="alert alert-dark  intro-y text-lg font-medium mt-3">
        Transaction inter d'un compte client dans son pays vers un compte Mobil money CIV ou CIV vers autre pays
    </h2>
    <div class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-6 sm:mt-0">
        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('commissions.browse_transactions_mooney_caf_civ') }}"
                class="alert alert-success mt-6 mr-6 mb-1" href="javascript:;" title="Modifier" style="color: white;">
                Compte client CENTRE AFRIQUE -> Compte Client Mobile Mooney CIV
            </a>
        @endif

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('commissions.browse_transactions_inter_caf_civ') }}"
                class="alert alert-success mt-6 mr-6 mb-1" style="color: white;" href="javascript:;" title="Modifier">
                Transafert inter Compte client CENTRE AFRIQUE -> Compte Client CIV

            </a>
        @endif

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('commissions.browse_transactions_civ_caf') }}" class="alert alert-success mt-6 mr-6 mb-1"
                style="color: white;" href="javascript:;" title="Modifier">
                Transafert inter Compte client CIV -> CENTRE AFRIQUE

            </a>
        @endif


    </div>
    <div class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-3 sm:mt-0">
        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('commissions.browse_transactions_mooney_fr_civ') }}"
                class="alert alert-success mt-6 mr-6 mb-1" href="javascript:;" title="Modifier" style="color: white;">
                Compte client France -> Compte Client Mobile Mooney CIV
            </a>
        @endif

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('commissions.browse_transactions_inter_fr_civ') }}"
                class="alert alert-success mt-6 mr-6 mb-1" style="color: white;" href="javascript:;" title="Modifier">
                Transafert inter Compte client France -> Compte Client CIV

            </a>
        @endif

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('commissions.browse_transactions_civ_fr') }}" class="alert alert-success mt-6 mr-6 mb-1"
                style="color: white;" href="javascript:;" title="Modifier">
                Transafert inter Compte client CIV -> France

            </a>
        @endif


    </div>
    <div class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-3 sm:mt-0">

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('commissions.browse_transactions_inter_marco_civ') }}"
                class="alert alert-success mt-6 mr-6 mb-1" style="color: white;" href="javascript:;" title="Modifier">
                Transafert inter Compte client CIV -> Maroc

            </a>
        @endif

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('commissions.browse_transactions_inter_tunisie_civ') }}" class="alert alert-success mt-6 mr-6 mb-1"
                style="color: white;" href="javascript:;" title="Modifier">
                Transafert inter Compte client CIV -> Tunisie

            </a>
        @endif



    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>

        @if ($centre_afriques->count() > 0)
            @foreach ($centre_afriques->where('inter_civ_mooney', true) as $item)
                @if ($item->percent != null)
                    <div class="intro-y col-span-12 lg:col-span-6">

                        <div class="box">

                            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">{{ $item->name ?? null }}</h2>

                            </div>

                            <div class="accordion p-5">


                                Pourcentage :
                                {{ $item->percent * 100 ?? null }} %

                            </div>
                            <div class="flex justify-center items-center">
                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                    <a wire:click="edit({{ $item->id }}, 'update-trans-cafrique-modal')"
                                        class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                        title="Modifier">
                                        Modifier
                                    </a>
                                @endif
                            </div>


                        </div>

                    </div>
                @else
                    <div class="intro-y col-span-12 lg:col-span-6">

                        <div class="box">

                            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">{{ $item->name ?? null }}</h2>

                            </div>

                            <div class="accordion p-5">
                                @foreach ($item->levelTransactions as $itemLevelTransaction)
                                    <div class="accordion-item">
                                        <p class="mr-6">{{ 'Paliers des montant ' }} | {{ 'Frais en Montant' }}</p>

                                        <div id="faq-accordion-collapse-1" class="accordion-collapse collapse show"
                                            aria-labelledby="faq-accordion-content-1"
                                            data-tw-parent="#faq-accordion-1">
                                            <div
                                                class="accordion-body text-slate-600 dark:text-slate-500 leading-relaxed">
                                                {{ $itemLevelTransaction->amount_begin ?? null }} --
                                                {{ $itemLevelTransaction->amount_end ?? null }}
                                                <span class="mr-6 ml-6">

                                                    Montant :
                                                    {{ $itemLevelTransaction->amount ?? null }}
                                                    @if ($item->id == 8)
                                                        Euro
                                                    @else
                                                        Fcfa
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                            </div>


                        </div>

                    </div>
                @endif
            @endforeach

        @endif
        @if ($centre_afriques->count() > 0)
            @foreach ($centre_afriques->where('inter_auther_civ', true) as $item)
                @if ($item->percent != null)
                    <div class="intro-y col-span-12 lg:col-span-6">

                        <div class="box">

                            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">{{ $item->name ?? null }}</h2>

                            </div>

                            <div class="accordion p-5">


                                Pourcentage :
                                {{ $item->percent * 100 ?? null }} %
                                <div class="flex justify-center items-center">
                                    @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                        <a wire:click="edit({{ $item->id }}, 'update-trans-cafrique-modal')"
                                            class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                            title="Modifier">
                                            Modifier
                                        </a>
                                    @endif
                                </div>

                            </div>


                        </div>

                    </div>
                @else
                    <div class="intro-y col-span-12 lg:col-span-6">

                        <div class="box">

                            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">{{ $item->name ?? null }}</h2>

                            </div>

                            <div class="accordion p-5">
                                @foreach ($item->levelTransactionIvoires as $itemLevelTransaction)
                                    <div class="accordion-item">
                                        <p class="mr-6">{{ 'Paliers des montant ' }} | {{ 'Frais en Montant' }}</p>

                                        <div id="faq-accordion-collapse-1" class="accordion-collapse collapse show"
                                            aria-labelledby="faq-accordion-content-1"
                                            data-tw-parent="#faq-accordion-1">
                                            <div
                                                class="accordion-body text-slate-600 dark:text-slate-500 leading-relaxed">
                                                {{ $itemLevelTransaction->amount_begin ?? null }} --
                                                {{ $itemLevelTransaction->amount_end ?? null }}
                                                <span class="mr-6 ml-6">

                                                    Montant :
                                                    {{ $itemLevelTransaction->amount ?? null }}
                                                    @if ($item->id == 9)
                                                        Euro
                                                    @else
                                                        Fcfa
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach


                                <!-- BEGIN: Pagination -->

                                <!-- END: Pagination -->
                            </div>


                        </div>

                    </div>
                @endif
            @endforeach


        @endif
        @if ($centre_afriques->count() > 0)
            @foreach ($centre_afriques->where('inter_civ_auther', true) as $item)
                @if ($item->percent != null)
                    <div class="intro-y col-span-12 lg:col-span-6">

                        <div class="box">

                            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">{{ $item->name ?? null }}</h2>

                            </div>

                            <div class="accordion p-5">


                                Pourcentage :
                                {{ $item->percent * 100 ?? null }} %
                                <div class="flex justify-center items-center">
                                    @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                        <a wire:click="edit({{ $item->id }}, 'update-trans-cafrique-modal')"
                                            class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                            title="Modifier">
                                            Modifier
                                        </a>
                                    @endif
                                </div>
                            </div>


                        </div>

                    </div>
                @else
                    <div class="intro-y col-span-12 lg:col-span-6">

                        <div class="box">

                            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">{{ $item->name ?? null }}</h2>

                            </div>

                            <div class="accordion p-5">
                                @foreach ($item->levelTransactionIvoireInters as $itemLevelTransaction)
                                    <div class="accordion-item">
                                        <p class="mr-6">{{ 'Paliers des montant ' }} | {{ 'Frais en Montant' }}</p>

                                        <div id="faq-accordion-collapse-1" class="accordion-collapse collapse show"
                                            aria-labelledby="faq-accordion-content-1"
                                            data-tw-parent="#faq-accordion-1">
                                            <div
                                                class="accordion-body text-slate-600 dark:text-slate-500 leading-relaxed">
                                                {{ $itemLevelTransaction->amount_begin ?? null }} --
                                                {{ $itemLevelTransaction->amount_end ?? null }}
                                                <span class="mr-6 ml-6">

                                                    Montant :
                                                    {{ $itemLevelTransaction->amount ?? null }}

                                                    Fcfa
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- BEGIN: Pagination -->

                                <!-- END: Pagination -->

                            </div>


                        </div>

                    </div>
                @endif
            @endforeach


        @endif
    </div>
    @include('livewire.commissions.modals.transactions.transactionCosts.updateTransactionCAF')

</div>
