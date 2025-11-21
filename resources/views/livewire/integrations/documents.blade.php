<div>
    @php

        $documentsCollection = collect([
            [
                'name' => 'Front CNI',
                'url' => $documents->front_document ?? null,
                'status' => $documents->status === 'Validé' ? 'Validé' : 'En attente',

                'type' => 'Image/PDF',
                'file_size' => 'N/A',
                'created_at' => $documents->created_at ?? null,
                'expires_at' => null,
                'icon' => 'id-card',
                'type_color' => 'blue',
            ],
            [
                'name' => 'Verso CNI',
                'url' => $documents->piece_document ?? null,
                'status' => $documents->status === 'Validé' ? 'Validé' : 'En attente',

                'type' => 'Image/PDF',
                'file_size' => 'N/A',
                'created_at' => $documents->created_at ?? null,
                'expires_at' => null,
                'icon' => 'id-card',
                'type_color' => 'green',
            ],
            [
                'name' => 'Juridict Form',
                'url' => $documents->juridict_form ?? null,
                'status' => $documents->status === 'Validé' ? 'Validé' : 'En attente',

                'type' => 'Image/PDF',
                'file_size' => 'N/A',
                'created_at' => $documents->created_at ?? null,
                'expires_at' => null,
                'icon' => 'file',
                'type_color' => 'purple',
            ],
        ])->filter(fn($doc) => $doc['url']);
    @endphp

    <div class="min-h-screen bg-gray-50 py-8 mt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-8">
                <div class="px-6 py-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="text-center mb-8 md:mb-0">
                            <h2
                                class="text-base font-bold text-gray-900 flex flex-col items-center justify-center space-y-2">
                                <div class="flex items-center">
                                    <i class="fas fa-cloud-upload-alt text-blue-600 mr-3 text-xl text-blue-800"></i>

                                    @if ($documentsCollection->count() > 0 && $documentsCollection->contains(fn($doc) => $doc['status'] !== 'Validé'))
                                        <!-- Si documents existants mais non validés -->
                                        <a class="btn btn-warning shadow-md mr-2"
                                            href="{{ route('propects.identifications.add') }}">
                                            Mise à jour des documents
                                        </a>
                                    @else
                                        <!-- Sinon bouton par défaut pour ajouter -->
                                        <a class="btn btn-primary shadow-md mr-2"
                                            href="{{ route('propects.identifications.add') }}">
                                            Ajouter des documents
                                        </a>
                                    @endif
                                </div>
                            </h2>
                            <p class="text-gray-600 mt-2">Téléchargez vos documents pour compléter votre profil</p>
                        </div>

                        <div class="flex-1 text-center md:text-left mt-4 md:mt-0">
                            <h1
                                class="text-2xl font-bold text-gray-900 flex items-center justify-center md:justify-start">
                                <i class="fas fa-file-alt text-blue-600 mr-3 text-blue-800"></i>
                                Mes Documents
                            </h1>
                            <p class="text-gray-600 mt-2">Gérez tous vos documents en un seul endroit</p>
                        </div>
                        <div class="mt-4 md:mt-0 text-center md:text-left">
                            <div class="flex items-center space-x-4 justify-center md:justify-start">
                                <div class="bg-blue-50 rounded-lg p-3">
                                    <i class="fas fa-file text-blue-600 text-xl text-blue-800"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-2xl ">{{ $totalCount ?? 0 }}</h4>
                                    <small class="text-gray-500">Documents au total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Documents Grid -->
            <div class="grid grid-cols-12 gap-6 mt-3">
                @forelse ($documentsCollection as $document)
                    <div class="intro-y col-span-12 lg:col-span-4">
                        <div class="box">
                            <div class="flex items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                                <h2 class="font-medium text-base mr-auto">{{ $item->title ?? null }}</h2>

                                @if ($document['status'] === 'En attente')
                                    <div class="mb-4">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-2 rounded-full animate-pulse"
                                                style="width: 75%"></div>
                                        </div>
                                        <p class="text-amber-600 text-xs text-center mt-2">
                                            <i class="fas fa-sync-alt animate-spin mr-1"></i>En cours de vérification
                                        </p>
                                    </div>
                                @endif
                                <div
                                    class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-5 sm:mt-0">

                                    @if ($document['status'] === 'Validé')
                                        <span class="status-badge status-validated">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Validé
                                        </span>
                                    @else
                                        <span class="status-badge dossier-pedding">
                                            <i class="fas fa-clock mr-1"></i>
                                            En attente
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Document Header -->
                                <div class="flex items-start justify-between mb-4 ml-6 mt-6">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-blue-50 rounded-xl p-3">
                                            <i  class="fas fa-{{ $document['icon'] ?? 'file' }} border-blue-200  text-blue-800 text-lg"></i>
                                        </div>
                                        <div class="min-w-0 flex-1 mr-3 ml-3 ">
                                            <h6 class="font-bold text-gray-900 truncate" style="max-width: 200px;">
                                                {{ $document['name'] ?? 'Document sans nom' }}
                                            </h6>
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $document['status'] === 'Validé' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }} mt-1">
                                                {{ $document['status'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>


                                <!-- Progress Bar for Pending Documents -->
                                @if ($document['status'] === 'En attente')
                                    <div class="mb-3">
                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-yellow-500 h-1.5 rounded-full animate-pulse"
                                                style="width: 75%">
                                            </div>
                                        </div>
                                        <p class="text-yellow-600 text-xs text-center mt-1">
                                            <i class="fas fa-clock mr-1"></i>En cours de vérification
                                        </p>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div
                                    class="intro-y flex text-xs sm:text-sm flex-col sm:flex-row items-center mt-5 pt-5 border-t border-slate-200/60 dark:border-darkmode-400">


                                    <div
                                        class="flex items-center text-slate-600 dark:text-slate-500 sm:ml-auto mt-5 sm:mt-0">

                                        <a class="flex items-center mr-3 mb-6" href="{{ $document['url'] }}">
                                            <i class="fas fa-eye mr-2"></i>
                                            Voir les détails
                                        </a>

                                        <div class="flex items-center mr-3 mb-6 bg-success text-white">
                                            <a href="{{ $document['url'] }}" download
                                                class="flex-1 bg-blue-600 text-black hover:bg-blue-700 px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center justify-center">
                                                <i class="fas fa-download mr-2"></i>Télécharger
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                @empty
                    <div class="col-span-12">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                            <div class="px-6 py-12 text-center">
                                <div class="text-gray-300 mb-4">
                                    <i class="fas fa-file-import text-5xl"></i>
                                </div>
                                <h4 class="font-bold text-gray-900 text-xl mb-2">Aucun document trouvé</h4>
                                <p class="text-gray-600 mb-6">
                                    @if (!$documents)
                                        Aucune information de client trouvée. Veuillez compléter votre profil.
                                    @else
                                        Vous n'avez pas encore ajouté de documents à votre compte.
                                    @endif
                                </p>
                                <a href="{{ route('propects.identifications.add') }}"
                                    class="bg-blue-600 text-white hover:bg-blue-700 px-6 py-3 rounded-lg font-medium transition-colors inline-flex items-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    @if (!$documents)
                                        Compléter mon profil
                                    @else
                                        Ajouter un document
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>


            <!-- Statistics Section -->
            @if ($documentsCollection->count() > 0)
                <div class="mt-12">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div class="px-6 py-6">
                            <h5 class="font-bold text-gray-900 text-lg mb-6">Statistiques des documents</h5>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                                <!-- Validés -->
                                <div class="text-center">
                                    <div class="bg-green-50 rounded-full p-3 inline-flex mb-3">
                                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-2xl">
                                        {{ $validatedCount ?? $documentsCollection->where('status', 'Validé')->count() }}
                                    </h4>
                                    <small class="text-gray-500">Validés</small>
                                </div>

                                <!-- En attente -->
                                <div class="text-center">
                                    <div class="bg-yellow-50 rounded-full p-3 inline-flex mb-3">
                                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-2xl">
                                        {{ $pendingCount ?? $documentsCollection->where('status', 'En attente')->count() }}
                                    </h4>
                                    <small class="text-gray-500">En attente</small>
                                </div>

                                <!-- Rejetés -->
                                <div class="text-center">
                                    <div class="bg-red-50 rounded-full p-3 inline-flex mb-3">
                                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-2xl">
                                        {{ $rejectedCount ?? $documentsCollection->where('status', 'Rejeté')->count() }}
                                    </h4>
                                    <small class="text-gray-500">Rejetés</small>
                                </div>

                                <!-- Total -->
                                <div class="text-center">
                                    <div class="bg-blue-50 rounded-full p-3 inline-flex mb-3">
                                        <i class="fas fa-file text-blue-600 text-xl"></i>
                                    </div>
                                    <h4 class="font-bold text-gray-900 text-2xl">
                                        {{ $totalCount ?? $documentsCollection->count() }}</h4>
                                    <small class="text-gray-500">Total</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
