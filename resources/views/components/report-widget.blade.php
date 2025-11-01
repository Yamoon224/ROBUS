@props([
    'formRoute',
    'downloadRoute',
    'title' => 'Rapport',
    'totalText' => 'Total',
    'pendingText' => 'En attente',
    'successText' => 'Success',
    'errorText' => 'Echec',
    'totalCount' => 0,
    'pendingCount' => 0,
    'successCount' => 0,
    'errorCount' => 0,
    'country' => null,
])

<div class="intro-y grid grid-cols-12 gap-5 mt-5">

    <!-- Formulaire dates -->
    <div class="intro-y col-span-12 lg:col-span-12">
        <div class="intro-y col-span-12 grid grid-cols-12 gap-5 items-center mt-2 mb-6">

            <div class="col-span-12 sm:col-span-4">
                <form method="GET" action="{{ $formRoute }}">
                    <label for="search_begin" class="block mb-1">Date de début</label>
                    <input type="date" name="search_begin" id="search_begin"
                        class="form-control w-full sm:w-auto box mt-1 @error('search_begin') is-invalid @enderror"
                        placeholder="Date de début" onchange="sortDate()">
                    @error('search_begin')
                        <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
                    @enderror
            </div>

            <div class="col-span-12 sm:col-span-4">
                <label for="search_end" class="block mb-1">Date de fin</label>
                <input type="date" name="search_end" id="search_end"
                    class="form-control w-full sm:w-auto box mt-1 @error('search_end') is-invalid @enderror"
                    placeholder="Date de fin" onchange="sortDate()">
                @error('search_end')
                    <span class="text-red-500 text-sm block mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-span-12 sm:col-span-4 flex items-end mt-3 sm:mt-0">
                <button type="submit" class="btn btn-primary w-full sm:w-auto">Télécharger en pdf</button>
                </form>
            </div>
        </div>

        <!-- Titre et bouton téléchargement -->
        <div class="intro-y block sm:flex items-center h-10 mb-5">
            <h2 class="text-lg font-medium truncate mr-5">{{ $title }}</h2>
            @if ($downloadRoute)
                <div class="flex items-center sm:ml-auto mt-3 sm:mt-0">
                    <a href="{{ $downloadRoute }}" class="btn btn-primary flex items-center ml-3" style="color:white;">
                        <i data-lucide="file-text" class="hidden sm:block w-4 h-4 mr-2"></i> Télécharger le rapport
                    </a>
                </div>
            @endif
        </div>

        <!-- Statistiques -->
        <div class="intro-y grid grid-cols-12 gap-5 mt-5">

            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">
                 <div>

                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-trending-down font-medium-5">
                            <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                            <polyline points="17 18 23 18 23 12"></polyline>
                        </svg>
                    </div>
                <div class="font-medium mt-6">{{ $totalText }}</div>
                <div class="text-slate-500 mt-3">{{ $totalCount }}
                    @if ($country)
                        <span class="text-slate-400 text-sm ml-1">({{ $country }})</span>
                    @endif

                </div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-pendd">
                <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-alert-circle font-medium-5">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                <div class="font-medium mt-6">{{ $pendingText }}</div>
                <div class="text-slate-500 mt-3">{{ $pendingCount }}</div>
            </div>

            <div
                class="col-span-12 sm:col-span-4 2xl:col-span-3 box bg-primary p-5 cursor-pointer zoom-in style-success">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-check font-medium-5">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                <div class="font-medium text-white mt-6">{{ $successText }}</div>
                <div class="text-white text-opacity-80 mt-3">{{ $successCount }}</div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-error">
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" class="feather feather-x-circle font-medium-5">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
               
                <div class="font-medium mt-6">{{ $errorText }}</div>
                <div class="text-slate-500 mt-3">{{ $errorCount }}</div>
            </div>

        </div>
    </div>
</div>
