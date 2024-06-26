<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des clients
    </h2>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        @if (master())
            <button wire:click="$emit('modal-show-custumer')" class="btn btn-primary shadow-md mr-2"> <svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>Ajouter</button>
        @endif
        <div class="hidden md:block mx-auto text-slate-500"></div>


        <div class="hidden md:block mx-auto text-slate-500"></div>

        <div class="hidden md:block ml-8 text-slate-500">
        </div>
        <div class="input-group input-group-md" style="margin-top: 13px;width: 250px;">
            <div class="w-56 relative text-slate-500">

                <input type="text" name="table_search" wire:model.debounce.250ms="search"
                    class="form-control  w-56 box pr-10" style="height: 3rem;" placeholder="Rechercher">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                    data-feather="search"></i>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-5">

        @if ($users->count() > 0)
            @foreach ($users as $item)
                <div class="intro-y col-span-12 md:col-span-6 lg:col-span-4">
                    <div class="box">

                        <div class="flex items-start px-5 pt-5">
                            <div class="w-full flex flex-col lg:flex-row items-center">
                                <div class="w-16 h-16 image-fit">
                                    <img alt="Midone - HTML Admin Template" class="rounded-full"
                                        src="{{ asset($item->image ?? 'public/dist/images/profil.png') }}">
                                </div>
                                <div class="lg:ml-4 text-center lg:text-left mt-3 lg:mt-0">
                                    <a href="" class="font-medium"> {{ $item->firstname ?? null }}</a>
                                    <div class="text-slate-500 text-xs mt-0.5"> {{ $item->lastname ?? null }}</div>
                                </div>

                            </div>

                        </div>
                        <div
                            class="text-center lg:text-right p-5 border-t border-slate-200/60 dark:border-darkmode-400">


                            @if ($item->is_accepted)
                                <span
                                    class="alert alert-success inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                    Vous etes lié a ce sous distibuteur
                                </span>
                            @else
                                <span
                                    class="alert alert-secondary inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                    En-cours de liaison
                                </span>
                            @endif

                            <a class="btn btn-primary  mr-1 mb-2 btn-sm"
                                href="{{ route('custumers.masters.show', ['id' => $item->id]) }}">
                                Details
                            </a>

                        </div>
                    </div>
                </div>
            @endforeach
            <!-- BEGIN: Pagination -->
            <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center">
                <nav class="w-full sm:w-auto sm:mr-auto">
                    <ul class="pagination">
                        {{ $users->links('vendor.livewire.bootstrap') }}
                    </ul>
                </nav>
            </div>
        @else

        <div class="intro-y col-span-12 md:col-span-6 lg:col-span-4 text-center">
            <h2 class="intro-y text-lg font-medium mt-10 text-center">
                Aucune donnée pour le moment 
            </h2>
        </div>
        @endif
        <!-- END: Pagination -->
    </div>

    @include('livewire.modals.masters.addCustumer')
    @include('livewire.modals.masters.disabledcustumer')

</div>
