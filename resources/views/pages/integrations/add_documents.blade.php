@extends('layouts.master_prospect')

@section('title', 'Ajouter des Documents')

@section('content')
    <div class="grid grid-cols-12 gap-6 mt-20">
        <div class="intro-y col-span-12 lg:col-span-2"></div>
        <div class="intro-y col-span-12 lg:col-span-8">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mb-4">
                    <div class="bg-blue-100 rounded-2xl p-4 mr-4 shadow-sm">
                        <i class="fas fa-id-card text-blue-600 text-2xl text-blue-500"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Documents d'identification</h1>
                        <p class="text-gray-600 mt-2">Téléchargez les documents requis pour compléter votre profil</p>
                    </div>
                </div>
            </div>

            <div class="intro-y box">
                <div
                    class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60 dark:border-darkmode-400">
                    <h2 class="font-medium text-base mr-auto">
                        <a href="{{ URL::previous() }}" class="btn btn-sm">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2"
                                fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                        </a>
                    </h2>
                </div>

                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white/20 rounded-full p-2">
                                <i class="fas fa-file-upload text-white text-sm text-blue-500"></i>
                            </div>
                            <div>
                                <h2 class="text-black font-semibold text-lg">Documents requis</h2>
                                <p class="text-blue-100 text-sm">Tous les champs sont obligatoires</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-5">
                    <form id="send_one" class="form-vertical row" action="{{ route('propects.update_identifications') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-12 gap-6 mt-3">
                            <div class="col-span-12 bg-white dark:bg-darkmode-600 p-6 rounded-xl shadow">

                                <!-- HEADER CNI -->
                                <div class="flex items-center mb-6">
                                    <div class="bg-blue-100 rounded-xl p-3 mr-4">
                                        <i class="fas fa-id-card text-blue-600 text-xl text-blue-500"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">Carte Nationale d'Identité</h3>
                                        <p class="text-gray-600 text-sm">Téléchargez le recto et verso de votre CNI</p>
                                    </div>
                                </div>

                                <div class="border-b border-slate-200 dark:border-darkmode-400 mb-6"></div>

                                <div class="grid grid-cols-12 gap-6">

                                    <!-- RECTO -->
                                    <div class="col-span-12 lg:col-span-6 flex flex-col items-center">
                                        <div
                                            class="bg-white rounded-2xl p-6 border-2 border-dashed border-blue-300 hover:border-blue-500 transition-all duration-300 shadow-md w-full max-w-xl group">
                                            <div class="text-center">
                                                <div class="relative mb-5" id="previewContainerRecto">
                                                    <img class="w-full h-64 rounded-xl object-cover bg-gray-100"
                                                        src="{{ old('image_recto') ?? asset_url('dist/images/food-beverage-1.jpg') }}"
                                                        alt="Recto CNI" id="imageCaptureId">
                                                    <div
                                                        class="absolute inset-0 bg-black/40 rounded-xl flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                                        <i class="fas fa-camera text-white text-3xl"></i>
                                                    </div>
                                                </div>

                                                <label for="imageUploadId" class="cursor-pointer">
                                                    <div
                                                        class="bg-blue-600 text-white px-6 py-4 rounded-xl font-semibold hover:bg-blue-700 transition inline-flex items-center justify-center w-full shadow-lg">
                                                        <i class="fas fa-upload mr-2"></i>
                                                        Importer Recto
                                                    </div>
                                                </label>

                                                <input id="imageUploadId" type="file" name="image_recto"
                                                    accept="image/*,application/pdf" class="hidden" required>

                                                <p class="text-gray-600 text-sm mt-3 flex items-center justify-center mb-3">
                                                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                                    Face avant de la Carte Nationale d’Identité
                                                </p>
                                            </div>
                                        </div>
                                        @error('image_recto')
                                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- VERSO -->
                                    <div class="col-span-12 lg:col-span-6 flex flex-col items-center">
                                        <div
                                            class="bg-white rounded-2xl p-6 border-2 border-dashed border-green-300 hover:border-green-500 transition-all duration-300 shadow-md w-full max-w-xl group">
                                            <div class="text-center">
                                                <div class="relative mb-5" id="previewContainerVerso">
                                                    <img class="w-full h-64 rounded-xl object-cover bg-gray-100"
                                                        src="{{ old('image_verso') ?? asset_url('dist/images/food-beverage-1.jpg') }}"
                                                        alt="Verso CNI" id="imageCaptureForm">
                                                    <div
                                                        class="absolute inset-0 bg-black/40 rounded-xl flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                                        <i class="fas fa-camera text-white text-3xl"></i>
                                                    </div>
                                                </div>

                                                <label for="imageUploadForm" class="cursor-pointer">
                                                    <div
                                                        class="bg-green-600 text-white px-6 py-4 rounded-xl font-semibold hover:bg-green-700 transition inline-flex items-center justify-center w-full shadow-lg">
                                                        <i class="fas fa-upload mr-2"></i>
                                                        Importer Verso
                                                    </div>
                                                </label>

                                                <input id="imageUploadForm" type="file" name="image_verso"
                                                    accept="image/*,application/pdf" class="hidden" required>

                                                <p class="text-gray-600 text-sm mt-3 flex items-center justify-center mb-3">
                                                    <i class="fas fa-info-circle mr-2 text-green-500"></i>
                                                    Face arrière de la Carte Nationale d’Identité
                                                </p>
                                            </div>
                                        </div>
                                        @error('image_verso')
                                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- REGISTRE DE COMMERCE -->
                                <div class="flex items-center mb-6 mt-20">
                                    <div class="bg-purple-100 rounded-xl p-3 mr-4">
                                        <i class="fas fa-building text-purple-600 text-xl text-blue-500"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-900">Registre de Commerce</h3>
                                        <p class="text-gray-600 text-sm">Document officiel de votre entreprise</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-12 gap-6">
                                    <div class="col-span-12 flex flex-col items-center mt-8">
                                        <div
                                            class="bg-white rounded-2xl p-6 border-2 border-dashed border-purple-300 hover:border-purple-500 transition-all duration-300 shadow-md w-full max-w-xl group">
                                            <div class="text-center">
                                                <div class="relative mb-5" id="previewContainerRegistre">
                                                    <img class="w-full h-64 rounded-xl object-cover bg-gray-100"
                                                        src="{{ old('juridict_form') ?? asset_url('dist/images/food-beverage-1.jpg') }}"
                                                        alt="Registre de Commerce" id="imageCaptureRegistre">
                                                    <div
                                                        class="absolute inset-0 bg-black/40 rounded-xl flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                                        <i class="fas fa-camera text-white text-3xl"></i>
                                                    </div>
                                                </div>

                                                <label for="imageUploadRegistre" class="cursor-pointer">
                                                    <div
                                                        class="bg-purple-600 text-white px-6 py-4 rounded-xl font-semibold hover:bg-purple-700 transition inline-flex items-center justify-center w-full shadow-lg">
                                                        <i class="fas fa-upload mr-2"></i>
                                                        Importer Registre de Commerce
                                                    </div>
                                                </label>

                                                <input id="imageUploadRegistre" type="file" name="juridict_form"
                                                    accept="image/*,application/pdf" class="hidden" required>

                                                <p class="text-gray-600 text-sm mt-3 flex items-center justify-center mb-3">
                                                    <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                                                    Document officiel de l'entreprise
                                                </p>
                                            </div>
                                        </div>
                                        @error('juridict_form')
                                            <p class="text-red-500 text-sm mt-2 text-center">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" id="submitButtonOne" class="btn btn-primary w-24 mb-2 mt-6">
                                <span id="buttonTextOne">Enregistrer</span>
                                <span id="buttonLoaderOne" class="hidden">
                                    <i class="fas fa-circle-notch fa-spin"></i>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function setupFilePreview(inputId, previewContainerId) {
                    const input = document.getElementById(inputId);
                    const container = document.getElementById(previewContainerId);

                    input.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (!file) return;

                        const isPDF = file.type === 'application/pdf';

                        if (isPDF) {
                            // Affiche icône PDF + nom du fichier
                            container.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-64 bg-gray-100 rounded-xl border border-gray-300">
                        <i class="fas fa-file-pdf text-red-500 text-5xl mb-2"></i>
                        <span class="text-gray-700 text-sm text-center break-words px-2">${file.name}</span>
                    </div>
                `;
                        } else {
                            // Image preview
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                container.innerHTML =
                                    `<img class="w-full h-64 rounded-xl object-cover bg-gray-100" src="${e.target.result}" alt="Preview">`;
                            };
                            reader.readAsDataURL(file);
                        }
                    });

                    // Click sur l'image/pdf pour ouvrir le selecteur
                    container.addEventListener('click', function() {
                        input.click();
                    });
                }

                // Initialiser tous les fichiers
                setupFilePreview('imageUploadId', 'previewContainerRecto');
                setupFilePreview('imageUploadForm', 'previewContainerVerso');
                setupFilePreview('imageUploadRegistre', 'previewContainerRegistre');

                // Gestion du formulaire
                const form = document.getElementById('send_one');
                const submitBtn = document.getElementById('submitButtonOne');
                const buttonText = document.getElementById('buttonTextOne');
                const buttonLoader = document.getElementById('buttonLoaderOne');

                form.addEventListener('submit', function(e) {
                    const rectoFile = document.getElementById('imageUploadId').files[0];
                    const versoFile = document.getElementById('imageUploadForm').files[0];
                    const registreFile = document.getElementById('imageUploadRegistre').files[0];

                    if (!rectoFile || !versoFile || !registreFile) {
                        e.preventDefault();
                        const errorDiv = document.createElement('div');
                        errorDiv.className =
                            'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4';
                        errorDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span>Veuillez télécharger tous les documents requis.</span>
                </div>
            `;
                        form.prepend(errorDiv);
                        setTimeout(() => {
                            errorDiv.remove();
                        }, 5000);
                        return;
                    }

                    buttonText.classList.add('hidden');
                    buttonLoader.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-75');
                });
            });
        </script>
    @endpush
@endsection
