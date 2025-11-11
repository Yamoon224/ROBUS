<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des publicités
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">
        @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-pub'))
            <a class="btn btn-primary shadow-md mr-2" href="{{ route('pubs.add') }}">Ajouter</a>
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
        @if ($pubs->count() > 0)
            @foreach ($pubs as $item)
                <div class="intro-y col-span-12 lg:col-span-4">
                    <div class="box">
                        <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                            <h2 class="font-medium text-base mr-auto">{{ $item->title ?? null }}</h2>

                            <div class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-5 sm:mt-0">

                                @if ($item->status == '1')
                                    @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-pub'))
                                        <div class="flex items-center justify-center text-success">

                                            <a wire:click="edit({{ $item->id }}, 'show-disabled-pub-modal'); $emit('show-disabled-pub-modal')"
                                                class="flex items-center mr-3 @if ($item->status == 1) @else @endif"
                                                href="javascript:;"
                                                title="@if ($item->status == 1) Désactiver @else Activer @endif">
                                                <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                            </a>

                                        </div>
                                    @endif
                                @else
                                    @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-pub'))
                                        <div class="flex items-center justify-center text-danger">
                                            <a wire:click="edit({{ $item->id }}, 'show-disabled-pub-modal'); $emit('show-disabled-pub-modal')"
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
                        <div class="flex-none image-container relative">
                            <img alt="Midone - HTML Admin Template"
                                src="{{ asset($item->images ?? 'public/dist/images/profil.png') }}">
                        </div>
                        <div class="accordion p-5">
                            <div class="accordion-item">

                                <div id="faq-accordion-collapse-1" class="accordion-collapse collapse show"
                                    aria-labelledby="faq-accordion-content-1" data-tw-parent="#faq-accordion-1">
                                    <div class="accordion-body text-slate-600 dark:text-slate-500 leading-relaxed">
                                        {{ $item->messages ?? null }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="intro-y flex text-xs sm:text-sm flex-col sm:flex-row items-center mt-5 pt-5 border-t border-slate-200/60 dark:border-darkmode-400">


                            <div class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-5 sm:mt-0">

                                @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-pub'))
                                    <a class="flex items-center mr-3 mb-6"
                                        href="{{ route('pubs.edit_pubs', ['id' => $item->id]) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-edit block mx-auto h-15 mr-1">
                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                        </svg>
                                        Modifier
                                    </a>
                                @endif

                                @if (Auth::guard('web')->user()->hasPermissionTo('ajouter-pub'))
                                    <div class="flex items-center mr-3 mb-6 bg-danger text-white">
                                        <button type="button"
                                            wire:click="edit({{ $item->id }}, 'show-deleted-pub-modal'); $emit('show-deleted-pub-modal')"
                                            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800 
               hover:bg-red-200 transition-colors duration-200 shadow-sm hover:shadow-md
               focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-500">
                                            <i class="fas fa-trash w-4 h-4 mr-2"></i> Supprimer
                                        </button>
                                    </div>
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
                        {{ $pubs->links() }}
                    </ul>
                </nav>

            </div>
            <!-- END: Pagination -->
        @else
            <div
                class="intro-y col-span-12 flex items-center justify-center mt-10 p-6 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800 text-center">
                Aucune publicité disponible pour le moment.
            </div>
        @endif

    </div>
    @include('livewire.commissions.modals.faqs.disabledPubs')
    @include('livewire.commissions.modals.faqs.deletePubs')


</div>
