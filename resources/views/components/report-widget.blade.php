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
    'errorCount' => 0
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
            @if($downloadRoute)
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
                <div class="font-medium mt-6">{{ $totalText }}</div>
                <div class="text-slate-500 mt-3">{{ $totalCount }}</div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-pendd">
                <div class="font-medium mt-6">{{ $pendingText }}</div>
                <div class="text-slate-500 mt-3">{{ $pendingCount }}</div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box bg-primary p-5 cursor-pointer zoom-in style-success">
                <div class="font-medium text-white mt-6">{{ $successText }}</div>
                <div class="text-white text-opacity-80 mt-3">{{ $successCount }}</div>
            </div>

            <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-error">
                <div class="font-medium mt-6">{{ $errorText }}</div>
                <div class="text-slate-500 mt-3">{{ $errorCount }}</div>
            </div>

        </div>
    </div>
</div>
