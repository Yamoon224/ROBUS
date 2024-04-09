<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        Commissions pour les depots 
    </h2>
    <div class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-6 sm:mt-0">

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('levels.browse_levels_deposit') }}" class="btn btn-warning  mt-6 mr-6 mb-2 btn-sm"
                href="javascript:;" title="Modifier">
                Modifier les pourcentages inter
            </a>
        @endif

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('levels.browse_levels_deposit_ivoire') }}"
                class="btn btn-warning  mt-6  mr-6 ml-6 mb-2 btn-sm" href="javascript:;" title="Modifier">
                Modifier les montant en CIV
            </a>
        @endif
        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('levels.browse_levels_deposit_ivoire_under') }}"
                class="btn btn-warning  mt-6  mr-6 ml-6 mb-2 btn-sm" href="javascript:;" title="Modifier">
                Modifier les montant en CIV Sous-distriuter
            </a>
        @endif
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>
        @if ($deposits->count() > 0)
            @foreach ($deposits->where('is_web', false) as $item)
                <div class="intro-y col-span-12 lg:col-span-6">

                    <div class="box">

                        <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">{{ $item->name ?? null }}</h2>

                        </div>

                        <div class="accordion p-5">
                            @foreach ($item->levelDepositIvories as $itemLevelbank)
                                <div class="accordion-item">
                                    <p class="mr-6">{{ 'Paliers des montant ' }} | {{ 'Frais en Montant/Pourcentage' }}</p>

                                    <div id="faq-accordion-collapse-1" class="accordion-collapse collapse show"
                                        aria-labelledby="faq-accordion-content-1" data-tw-parent="#faq-accordion-1">
                                        <div class="accordion-body text-slate-600 dark:text-slate-500 leading-relaxed">
                                            {{ $itemLevelbank->amount_begin }} --
                                            {{ $itemLevelbank->amount_end }}
                                            @if ($itemLevelbank->admin_percent == null)
                                                <span class="mr-6 ml-6">
                                                    Montant client :
                                                    {{ $itemLevelbank->amount ?? null }} Fcfa
                                                </span>
                                            @else
                                                <span class="alert alert-dark show mb-2 mr-6 ml-6">
                                                    Montant distributeur(% / CFA):
                                                    {{ $itemLevelbank->amount  ?? null }} {{ $itemLevelbank->percent  ?? null }} 
                                                </span>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach


                            @foreach ($item->levelDeposits as $itemLevelbank)
                                <div class="accordion-item">
                                    <p class="mr-6">{{ 'Paliers des montant ' }} | {{ 'Frais en Pourcentage' }}</p>

                                    <div id="faq-accordion-collapse-1" class="accordion-collapse collapse show"
                                        aria-labelledby="faq-accordion-content-1" data-tw-parent="#faq-accordion-1">
                                        <div class="accordion-body text-slate-600 dark:text-slate-500 leading-relaxed">

                                            {{ $itemLevelbank->amount_begin ?? null }} --
                                            {{ $itemLevelbank->amount_end ?? null }} <span class="mr-6 ml-6">
                                                Pourcentage/Montant(Frcfa) : {{ $itemLevelbank->percent ?? null }}
                                                {{ $itemLevelbank->amount ?? null }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>

                    </div>



                </div>
            @endforeach
        @endif
        <!-- END: Data List -->
    </div>
    <div class="intro-y col-span-12 overflow-auto lg:overflow-visible mt-6">
        <h2 class="alert alert-dark  intro-y text-lg font-medium">
           Rechargement de carte Visiteurs
        </h2>

        <table class="table table-bordered table-hover -intro-x mt-6">
            <thead class="table-dark">
                <tr>
                    <th class="whitespace-nowrap">Nom</th>
                    <th class="whitespace-nowrap text-center">Pourcentage Wave</th>
                    <th class="whitespace-nowrap text-center">Pourcentage Admin</th>
                    <th class="text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($deposits->count() > 0)
                    @foreach ($deposits->where('is_web', true) as $item)
                        <tr class="intro-x">
                            <td class="w-65">
                                {{ $item->name ?? null }}

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

                            <td class="w-56">
                                <div class="flex justify-center items-center">
                                    @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
                                        <a wire:click="edit({{ $item->id }}, 'show-depositWeb-modal')"
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
    @include('livewire.commissions.modals.addDeposit')
    @include('livewire.commissions.modals.disabledDeposit')
    @include('livewire.commissions.modals.updateDeposit')
    @include('livewire.commissions.modals.updateDepositWeb')

</div>
