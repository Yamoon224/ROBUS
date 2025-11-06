<div>
    <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>
        <div class="hidden md:block mx-auto text-slate-500"></div>


        <div class="hidden md:block mx-auto text-slate-500"></div>

        <div class="input-group input-group-md hidden" style="margin-top: 13px;width: 250px;   ">
            <div class="w-56 relative text-slate-500">

                <input type="text" name="table_search" class="form-control  w-56 box pr-10"
                    style="height: 3rem; background-color: #eff3f7;" placeholder="Rechercher">
                <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                    data-feather="searchPaiment"></i>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6 mt-6">
        <div class="intro-y box col-span-12" style="background-color: #eff3f7;">

            <div
                class="flex items-center  border-b border-slate-200/60 dark:border-darkmode-400 bg-white dark:bg-darkmode-700">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-8 bg-orange-500 rounded-r-full"></div>
                    <div>
                        <h2 class="font-medium text-base mr-auto text-slate-900 dark:text-white text-xl">
                            Liste des transactions du tresort Money
                        </h2>
                        <div class="flex items-center gap-4 mt-2 mb-2 ">
                            <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">
                                Dernière mise à jour: {{ now()->format('d/m/Y H:i') }}
                            </span>
                            <span class="w-1 h-1 bg-slate-400 rounded-full"></span>
                            <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">
                                {{ $tresortMoney_historicals->count() }} éléments
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" py-5">
                <div class="px-5">
                    <div class="overflow-x-auto">
                        @include('components.tableTresort')
                    </div>
                </div>
            </div>
        </div>
        <!-- BEGIN: Pagination -->
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
            <nav class="w-full sm:w-auto sm:mr-auto">
                <ul class="pagination">
                    {{ $tresortMoney_historicals->links('vendor.livewire.tailwind-paiements') }}
                </ul>
            </nav>
        </div>
        <!-- END: Pagination -->
    </div>
</div>
