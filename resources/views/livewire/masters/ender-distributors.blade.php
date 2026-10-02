<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Liste des sous distributeurs
    </h2>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        @if (master())
            <button wire:click="$emit('modal-show')" class="btn btn-primary shadow-md mr-2"> <svg
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>Ajouter</button>
        @endif
        <div class="hidden md:block mx-auto text-slate-500"></div>

        @if (master())
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6 mr-3">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
            </svg>
            <a class="btn btn-primary shadow-md mr-2" href="{{ route('masters.underdistributors.add') }}"> Creer un sous
                distributeur</a>
        @endif



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
        @php
            // Soldes de tous les utilisateurs de la liste en une seule requête
            $soldes = \App\Models\Wallets\Wallet::withTrashed()
                ->whereIn('sid_user_id', collect($users instanceof \Illuminate\Contracts\Pagination\Paginator ? $users->items() : $users)->pluck('id'))
                ->groupBy('sid_user_id')
                ->selectRaw('sid_user_id, SUM(amount) as total')
                ->pluck('total', 'sid_user_id');
        @endphp
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
                        @if ($item->master_id == master()->id)
                            <div class="flex items-center justify-center text-success">

                                <a wire:click="edit({{ $item->wallet->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                    class="flex items-center mr-3 @if ($item->master_id == master()->id) @else @endif"
                                    href="javascript:;"
                                    title="@if ($item->master_id == master()->id) Désactiver @else Activer @endif">
                                    <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activé
                                </a>

                            </div>
                        @else
                            <div class="flex items-center justify-center text-danger">
                                <a wire:click="edit({{ $item->wallet->id }}, 'show-disabled-modal'); $emit('show-disabled-modal')"
                                    class="flex items-center mr-3 @if ($item->master_id == master()->id) @else @endif"
                                    href="javascript:;"
                                    title="@if ($item->master_id == master()->id) Désactiver @else Activer @endif">
                                    <i class="fas fa-lock w-4 h-4 mr-2"></i>Désactivé
                                </a>

                            </div>
                        @endif

                    </div>
                    <div class="text-center lg:text-left p-5">
                        <div class="intro-x relative mr-3 sm:mr-6 text-center  mt-6">

                            <span
                                class="alert alert-primary inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                {{ $item->country->name ?? null }} ; {{ $item->city->name ?? null }}
                            </span>

                        </div>

                        @php
                            $solde = $soldes[$item->id] ?? 0;
                        @endphp

                        @if ($solde >= 100000)
                            <div
                                class="mt-6 lg:mt-0 flex-1 flex items-center justify-center px-5 border-t lg:border-0 mr-3 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0 color-wallet">
                                <div class="text-center rounded-md  py-3" style="color: white; font-size: 16px;">
                                    <div class="font-medium text-lg mb-3">Solde Wallet</div>

                                    <div class="font-medium text-primary text-xl" style="color: white;">
                                        {{ number_format($solde, 2, ',', ' ') ?? null }}
                                        {{ $item->country->currencie->code ?? null }}</div>
                                </div>

                            </div>
                        @elseif($solde >= 50000 && $solde <= 99999)
                            <div
                                class="mt-6 lg:mt-0 flex-1 flex items-center justify-center px-5 border-t lg:border-0 mr-3 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0 color-wallet2">
                                <div class="text-center rounded-md  py-3" style="color: white; font-size: 16px;">
                                    <div class="font-medium text-lg mb-3">Solde Wallet</div>

                                    <div class="font-medium text-primary text-xl" style="color: white;">
                                        {{ number_format($solde, 2, ',', ' ') ?? null }}
                                        {{ $item->country->currencie->code ?? null }}</div>
                                </div>

                            </div>
                        @elseif($solde >= 0 && $solde <= 49999)
                            <div
                                class="mt-6 lg:mt-0 flex-1 flex items-center justify-center px-5 border-t lg:border-0 mr-3 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0 color-wallet3">
                                <div class="text-center rounded-md  py-3" style="color: white; font-size: 16px;">
                                    <div class="font-medium text-lg mb-3">Solde Wallet</div>

                                    <div class="font-medium text-primary text-xl" style="color: white;">
                                        {{ number_format($solde, 2, ',', ' ') ?? null }}
                                        {{ $item->country->currencie->code ?? null }}</div>
                                </div>

                            </div>
                        @endif
                        <div
                            class="flex font-medium text-lg items-center justify-center lg:justify-start text-slate-500 mt-5">
                            Code wallet : {{ $item->wallet->wallet_id ?? null }}
                        </div>
                        <div class="flex items-center justify-center lg:justify-start text-slate-500 mt-5">
                            <span
                                class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                Plafond : {{ $item->silver_ceiling ?? null }}
                                {{ $item->country->currencie->code ?? null }}
                            </span>
                        </div>
                        <div class="flex items-center justify-center lg:justify-start text-slate-500 mt-5"> <svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="mail" data-lucide="mail"
                                class="lucide lucide-mail w-3 h-3 mr-2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                </path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg> {{ $item->email ?? null }}</div>

                        <div class="flex items-center justify-center lg:justify-start text-slate-500 mt-1"> <svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" icon-name="instagram" data-lucide="instagram"
                                class="lucide lucide-instagram w-3 h-3 mr-2">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5">
                                </rect>
                                <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg> {{ $item->phone ?? null }} </div>
                    </div>
                    <div class="text-center lg:text-right p-5 border-t border-slate-200/60 dark:border-darkmode-400">


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
                            href="{{ route('underdistributors.show', ['id' => $item->id]) }}">
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
        <!-- END: Pagination -->
    </div>

    @include('livewire.modals.masters.addmaster')
    @include('livewire.modals.masters.disabledmaster')

</div>
