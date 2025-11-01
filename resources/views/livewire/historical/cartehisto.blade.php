<div>

     <x-report-widget 
        :formRoute="route('inters.historical.reporting_cartes_days_historical.admins')" 
        :downloadRoute="route('cards.historical.reporting_card_historical')" title="Les Activités des commande des cartes"
        totalText="Total Commandes cartes" 
        :totalCount="$user ?? 0" 
        :country="$usersCountry"
        :pendingCount="$card_historicals->where('status', 
        'PENDING')->count()" :successCount="$card_historicals->where('status', 
        'SUCCESS')->count()" 
        :errorCount="$card_historicals->where('status', 'ERROR')->count()" />


    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 grid grid-cols-12 gap-5 items-center mt-2">
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <div class="w-56 relative text-slate-500 mt-3">
                    <label for="">Date de debut</label>

                    <input type="date" class="form-control  w-56 box pr-10 mt -1" value="{{ $startDate }}"
                        wire:model="startDate" placeholder="Rechercher par date les commandes" id="start_date"
                        aria-label="Date">

                </div>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <div class="w-56 relative text-slate-500 mt-3">
                    <label for="">Date de fin</label>
                    <input type="date" class="form-control  w-56 box pr-10 mt-1" value="{{ $endDate }}"
                        wire:model="endDate" placeholder="Rechercher par date" id="end_date" aria-label="Date">

                </div>

            </div>

            <div class="col-span-12 sm:col-span-3 2xl:col-span-3">
                <label for="countriesFilter">Filtrer par status</label>
                <select id="statusFilter" wire:model="statusFilter"
                    class="form-select py-3 px-4 box w-full
                    dark-border-dark-3 mt-lg-0 ms-auto mt-3"
                    aria-placeholder="Rechercher par status">
                    <option selected value="!=null">Status</option>
                    <option value="SUCCESS">Success</option>
                    <option value="ERROR">Erreur</option>
                    <option value="PENDING">En attente</option>
                </select>
            </div>
            <div class="col-span-12 sm:col-span-3 2xl:col-span-3 " style="margin-top: 13px;width: 250px;">
                <div class="w-56 relative text-slate-500 mt-3">
                    <input type="text" name="table_search" wire:model.debounce.250ms="searchCarteHistorical"
                        class="form-control w-56 box pr-10" style="height: 3rem;" placeholder="Rechercher">
                    {{-- <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6  ml-10" data-feather="search"></i> --}}
                </div>
            </div>
        </div>
        <div class="intro-y box px-3 pt-1 mt-6 col-span-12 overflow-auto lg:overflow-visible">
            <div class="grid grid-cols-12 gap-6  mt-6">
                <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
                    <div class=" items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                        <h2 class="font-medium text-base mr-auto">
                            Liste des paiements rechargements des cartes
                        </h2>
                    </div>
                    <div class="tiny-slider py-5">
                        <div class="px-5">
                            <div class="overflow-x-auto">
                                                       @include('components.tableCards')

                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: Pagination -->
                <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6 mb-6">
                    <nav class="w-full sm:w-auto sm:mr-auto">
                        <ul class="pagination">
                            {{ $card_historicals->links() }}
                        </ul>
                    </nav>
                </div>
                <!-- END: Pagination -->

            </div>


        </div>
    </div>

    @include('livewire.historical.modals.updateReloadCardDefund')

</div>
