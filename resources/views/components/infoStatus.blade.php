        <!-- BEGIN: Item List -->
        <div class="intro-y grid grid-cols-12 gap-5 mt-5">
            <div class="col-span-12 sm:col-span-6 2xl:col-span-6 box p-5 cursor-pointer zoom-in style_table_factures">
                <div><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                        <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                        <polyline points="17 18 23 18 23 12"></polyline>
                    </svg>
                </div>
                <div class="font-medium text-historique mt-6">Montant total</div>
                <div class="text-slate-500 mt-3">
                    {{ $valeur_payments ?? null }} {{ $countrie->currencie->code ?? null }}
                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-pendd">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-alert-circle font-medium-5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div class="font-medium text-historique mt-6">En attente</div>
                <div class="text-slate-500 mt-3">
                    {{ $valeur_pending ?? null }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box bg-primary p-5 cursor-pointer zoom-in style-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-check font-medium-5">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <div class="font-medium text-historique text-white mt-6">Success</div>
                <div class="text-white text-opacity-80 dark:text-slate-500 mt-3">
                    {{ $valeur_success ?? null }}
                </div>
            </div>
            <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in style-error ">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                    class="feather feather-x-circle font-medium-5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                <div class="font-medium text-historique mt-6">Echec</div>
                <div class="text-slate-500 mt-3">
                    {{ $valeur_error ?? null }}
                </div>
            </div>

        </div>