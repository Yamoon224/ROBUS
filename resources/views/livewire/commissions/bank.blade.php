<div>
    <h2 class="intro-y text-lg font-medium mt-10">
        La comisssions bancaires
    </h2>
    <div class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-6 sm:mt-0">

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('levels.browse_levels_bank') }}" class="btn btn-warning  mt-6 mr-6 mb-2 btn-sm"
                href="javascript:;" title="Modifier">
                Modifier les pourcentages inter
            </a>
        @endif

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('levels.browse_levels_ivoire') }}" class="btn btn-warning  mt-6  mr-6 ml-6 mb-2 btn-sm"
                href="javascript:;" title="Modifier">
                Modifier les montant en CIV Distributeur
            </a>
        @endif

        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-cautions'))
            <a href="{{ route('levels.browse_levels_custumers') }}" class="btn btn-warning  mt-6  mr-6 ml-6 mb-2 btn-sm"
                href="javascript:;" title="Modifier">
                Modifier les montant en CIV Client
            </a>
        @endif
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

        </div>

        @if ($banks->count() > 0)
            @foreach ($banks as $item)
                <div class="intro-y col-span-12 lg:col-span-6">

                    <div class="box">

                        <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">{{ $item->name ?? null }}</h2>

                        </div>

                        <div class="accordion p-5">
                            @foreach ($item->levelBankIvories as $itemLevelbank)
                                <div class="accordion-item">
                                    <p class="mr-6">{{ 'Paliers des montant ' }} | {{ 'Frais en Montant' }} | {{ 'S-distributeur' }} | {{ 'Compagnie' }}</p>

                                    <div id="faq-accordion-collapse-1" class="accordion-collapse collapse show"
                                        aria-labelledby="faq-accordion-content-1" data-tw-parent="#faq-accordion-1">
                                        <div class="accordion-body text-slate-600 dark:text-slate-500 leading-relaxed">
                                            {{ $itemLevelbank->amount_begin ?? null }} --
                                            {{ $itemLevelbank->amount_end ?? null }}
                                            <span class="mr-6 ml-6">
                                                Montant :
                                                {{ $itemLevelbank->amount ?? null }} Fcfa
                                            </span>
                                            <span class="mr-6 ml-6">
                                                Montant s-distruteur :
                                                {{ $itemLevelbank->comission_distributer ?? null }} Fcfa
                                            </span>
                                            <span class="mr-6 ml-6">
                                                Montant compagnie :
                                                {{ $itemLevelbank->percent_companie ?? null }} Fcfa
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @foreach ($item->levelBanks as $itemLevelbank)
                                <div class="accordion-item">
                                    <p class="mr-6">{{ 'Paliers des montant ' }} | {{ 'Frais en Pourcentage' }} | {{ 'S-distributeur' }} | {{ 'Compagnie' }}</p>

                                    <div id="faq-accordion-collapse-1" class="accordion-collapse collapse show"
                                        aria-labelledby="faq-accordion-content-1" data-tw-parent="#faq-accordion-1">
                                        <div class="accordion-body text-slate-600 dark:text-slate-500 leading-relaxed">

                                            {{ $itemLevelbank->amount_begin ?? null }} --
                                            {{ $itemLevelbank->amount_end ?? null }} 
                                           <span class="mr-6 ml-6">
                                                Pourcentage : {{ $itemLevelbank->percent ?? null }}
                                            </span>
                                            <span class="mr-6 ml-6">
                                                Montant s-distruteur :
                                                {{ $itemLevelbank->comission_distributer ?? null }}
                                            </span>
                                            <span class="mr-6 ml-6">
                                                Montant compagnie :
                                                {{ $itemLevelbank->percent_companie ?? null }}
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
    </div>
    @include('livewire.commissions.modals.addBank')
    @include('livewire.commissions.modals.disabledBank')
    @include('livewire.commissions.modals.updateBank')

</div>
