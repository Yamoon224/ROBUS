<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des questions & reponse
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
        @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-faqs'))
            <button wire:click="$emit('modal-show')" class="btn btn-primary shadow-md mr-2">Ajouter</button>
        @endif


        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">

            <div class="hidden md:block mx-auto text-slate-500"></div>
            <div class="hidden md:block ml-8 text-slate-500">
            </div>
            <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500">
                    <input type="text" name="search" wire:model.debounce.250ms="search"
                        class="form-control  w-56 box pr-10" style="height: 3rem;" placeholder="Rechercher">
                    <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                        data-feather="search"></i>
                </div>
            </div>
        </div>
        @if ($faqs->count() > 0)
            @foreach ($faqs as $item)
                <div class="intro-y col-span-12 lg:col-span-6">
                    <div class="box">
                        <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">{{ $item->question ?? null }}</h2>

                            <div class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-5 sm:mt-0">

                                @if ($item->status == '1')
                                    @if (Auth::guard('web')->user()->hasPermissionTo('modifier-faqs'))
                                        <div class="flex items-center justify-center text-success">

                                            <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                href="javascript:;"
                                                title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                            </a>

                                        </div>
                                    @endif
                                @else
                                    @if (Auth::guard('web')->user()->hasPermissionTo('modifier-faqs'))
                                        <div class="flex items-center justify-center text-danger">
                                            <a wire:click="edit({{ $item->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                                class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                href="javascript:;"
                                                title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                            </a>

                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="accordion p-5">
                            <div class="accordion-item">
                                {{-- <div id="faq-accordion-content-1" class="accordion-header">
                                    <button class="accordion-button" type="button" data-tw-toggle="collapse"
                                        data-tw-target="#faq-accordion-collapse-1" aria-expanded="true"
                                        aria-controls="faq-accordion-collapse-1">
                                        OpenSSL Essentials: Working with SSL Certificates, Private Keys
                                    </button>
                                </div> --}}
                                <div id="faq-accordion-collapse-1" class="accordion-collapse collapse show"
                                    aria-labelledby="faq-accordion-content-1" data-tw-parent="#faq-accordion-1">
                                    <div class="accordion-body text-slate-600 dark:text-slate-500 leading-relaxed">
                                        {{ $item->answer ?? null }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="intro-y flex text-xs sm:text-sm flex-col sm:flex-row items-center mt-5 pt-5 border-t border-slate-200/60 dark:border-darkmode-400">


                            <div class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-5 sm:mt-0">

                                @if (Auth::guard('web')->user()->hasPermissionTo('modifier-faqs'))
                                    <a wire:click="edit({{ $item->id }}, 'update-faqs-modal')"
                                        class="btn btn-warning   mr-1 mb-2 btn-sm" href="javascript:;" title="Modifier">
                                        Modifier
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>



                </div>
            @endforeach

            <!-- BEGIN: Pagination -->
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
                <nav class="w-full sm:w-auto sm:mr-auto">
                    <ul class="pagination">
                        {{ $faqs->links() }}
                    </ul>
                </nav>
            </div>
            <!-- END: Pagination -->
        @endif

    </div>
    @include('livewire.commissions.modals.faqs.addFaqs')
    @include('livewire.commissions.modals.faqs.disabledFaqs')
    @include('livewire.commissions.modals.faqs.updateFaqs')
    @include('livewire.commissions.modals.faqs.deleteFaqs')


</div>
