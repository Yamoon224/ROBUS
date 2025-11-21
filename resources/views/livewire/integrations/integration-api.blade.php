<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/20 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-blue-100/50 mb-8">
            <div class="px-8 py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <!-- Title Section -->
                    <div class="text-center lg:text-left">
                        <div class="flex items-center justify-center lg:justify-start mb-3">
                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-3 mr-4 shadow-md">
                                <i class="fas fa-key text-black text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900">Gestion des Clés API</h1>
                                <p class="text-gray-600 mt-2">Gérez vos identifiants d'intégration</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div class="flex flex-col items-center lg:items-end gap-4">
                        @if ($integrationClient->status === 'En attente')
                            <div class="text-center">
                                <div class="w-48 bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-2 rounded-full animate-pulse"
                                        style="width: 75%"></div>
                                </div>
                                <p class="text-amber-600 text-xs mt-2">
                                    <i class="fas fa-sync-alt animate-spin mr-1"></i>En cours de vérification
                                </p>
                            </div>
                        @endif

                        <div class="flex items-center">
                            @if ($integrationClient->status === 'Validé')
                                <span class="status-badge status-validated">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Compte Validé
                                </span>
                            @else
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock mr-2"></i>
                                    En attente de validation
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <!-- API Information Header -->

                    <div class="p-6">
                        @if (!$integrationClient)
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <div
                                    class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl p-8 border border-orange-200">
                                    <div class="text-orange-400 mb-6">
                                        <i class="fas fa-user-plus text-6xl"></i>
                                    </div>
                                    <h3 class="font-bold text-gray-900 text-2xl mb-3">Profil Incomplet</h3>
                                    <p class="text-gray-600 text-lg mb-6 max-w-md mx-auto">
                                        Aucune information de client trouvée. Veuillez compléter votre profil pour
                                        générer vos clés API.
                                    </p>
                                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                        <a href="{{ route('propects.identifications.add') }}"
                                            class="inline-flex items-center bg-gradient-to-r from-orange-500 to-amber-500 text-white px-8 py-3 rounded-xl font-semibold hover:from-orange-600 hover:to-amber-600 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                            <i class="fas fa-user-edit mr-3"></i>
                                            Compléter mon profil
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-12 gap-6 mt-3 p-5 mb-6">

                                <!-- API Keys Section -->
                                <div class="col-span-12 bg-white dark:bg-darkmode-600 p-6 rounded-xl shadow">

                                    <!-- HEADER -->
                                    <div class="flex items-center mb-6">
                                        <div class="bg-blue-100 rounded-xl p-3 mr-4">
                                            <i class="fas fa-code text-green-600 text-lg text-blue-500"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-900">Clé API</h3>
                                            <p class="text-gray-600 text-sm">Utilisez cette clé pour authentifier vos
                                                requêtes</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-6">

                                        <!-- API KEY -->
                                        <div class="col-span-12 lg:col-span-6 flex flex-col">
                                            <div
                                                class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200 hover:shadow-md transition-all duration-300">

                                                <!-- Header -->
                                                <div class="flex items-center mb-4 mt-3">
                                                    <div class="bg-blue-100 rounded-xl p-3 mr-4">
                                                        <i class="fas fa-key text-blue-500 text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-semibold text-gray-900 text-lg">Clé API</h3>
                                                        <p class="text-gray-600 text-sm">Utilisez cette clé pour
                                                            authentifier vos requêtes</p>
                                                    </div>
                                                </div>

                                                <!-- Input + Copy Button -->

                                                <div class="relative">
                                                    <input type="text" value="{{ $integrationClient->api_key }}"
                                                        readonly id="api-key"
                                                        class="w-full bg-white border border-blue-300 rounded-xl px-4 py-3
                                                            pr-24 font-mono text-sm text-gray-700
                                                            focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer"
                                                                                                        onclick="this.select()">


                                                    <div class="inset-y-0 right-3 flex items-center space-x-3 mb-3 mt-3">

                                                        <!-- Show/Hide -->
                                                        <button onclick="toggleKeyVisibility()"
                                                            class="px-2 text-gray-400 hover:text-purple-600 transition-colors"
                                                            id="toggle-key" title="Afficher/Masquer">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        <!-- Copy -->
                                                        <button
                                                            onclick="copyToClipboard('{{ $integrationClient->api_key }}', 'api-key-copy')"
                                                            class="text-gray-400 hover:text-blue-600 transition-colors"
                                                            id="api-key-copy" title="Copier la clé API">
                                                            <i class="fas fa-copy text-lg"></i>
                                                        </button>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- API SECRET -->
                                        <div class="col-span-12 lg:col-span-6 flex flex-col">
                                            <div
                                                class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200 hover:shadow-md transition-all duration-300">

                                                <!-- Header -->
                                                <div class="flex items-center mb-4 mt-3">
                                                    <div class="bg-purple-100 rounded-xl p-3 mr-4">
                                                        <i class="fas fa-lock text-purple-600 text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <h3 class="font-semibold text-gray-900 text-lg">Clé Secrète</h3>
                                                        <p class="text-gray-600 text-sm">Gardez cette clé confidentielle
                                                            et sécurisée</p>
                                                    </div>
                                                </div>

                                                <!-- Input + Buttons -->
                                                <div class="relative">
                                                    <input type="password" value="{{ $integrationClient->api_secret }}"
                                                        readonly id="secret-key"
                                                        class="w-full bg-white border border-purple-300 rounded-xl px-4 py-3 pr-24 font-mono text-sm text-gray-700
                                                     focus:outline-none focus:ring-2 focus:ring-purple-500 cursor-pointer"
                                                        onclick="this.select()">

                                                    <div class="inset-y-0 right-3 flex items-center space-x-3 mb-3 mt-3">

                                                        <!-- Show/hide Btn -->
                                                        <button onclick="toggleSecretVisibility()"
                                                            class="px-2 text-gray-400 hover:text-purple-600 transition-colors"
                                                            id="toggle-secret" title="Afficher/Masquer">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        <!-- Copy Btn -->
                                                        <button
                                                            onclick="copyToClipboard('{{ $integrationClient->api_secret }}', 'secret-key-copy')"
                                                            class="px-2 text-gray-400 hover:text-purple-600 transition-colors"
                                                            id="secret-key-copy" title="Copier la clé secrète">
                                                            <i class="fas fa-copy"></i>
                                                        </button>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <!-- Statistics Section -->
                            <div class="grid grid-cols-12 gap-6 mt-6 p-5">

                                <!-- Transaction Limit -->
                                <div
                                    class="col-span-12 md:col-span-6 lg:col-span-4
                                            bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6
                                            border border-green-200 hover:shadow-md transition-all duration-300 ">
                                    <div class="text-center mt-6">
                                        <div class="bg-green-100 rounded-2xl p-4 inline-flex mb-4">
                                            <i class="fas fa-chart-line text-green-600 text-2xl"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-3xl mb-2">
                                            {{ number_format($integrationClient->transaction_limit ?? 0) }}
                                        </h4>
                                        <p class="text-gray-600 font-semibold">Limite de Transactions</p>
                                        <p class="text-gray-500 text-sm mt-2">Nombre maximum autorisé</p>
                                    </div>
                                </div>

                                <!-- Account Status -->
                                <div
                                    class="col-span-12 md:col-span-6 lg:col-span-4
                                            bg-gradient-to-br from-gray-50 to-slate-100 rounded-2xl p-6
                                            border border-gray-200 hover:shadow-md transition-all duration-300">
                                    <div class="text-center mt-6">
                                        <div class="bg-blue-100 rounded-2xl p-4 inline-flex mb-4">
                                            <i class="fas fa-user-check text-blue-500 text-2xl"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-xl mb-2">
                                            @if ($integrationClient->is_active)
                                                <span class="text-green-600">Actif</span>
                                            @else
                                                <span class="text-red-600">Inactif</span>
                                            @endif
                                        </h4>
                                        <p class="text-gray-600 font-semibold">Statut du Compte</p>
                                        <p class="text-gray-500 text-sm mt-2">
                                            @if ($integrationClient->is_active)
                                                Compte activé et opérationnel
                                            @else
                                                En attente d'activation
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div
                                    class="col-span-12 md:col-span-6 lg:col-span-4
                bg-gradient-to-br from-orange-50 to-amber-50 rounded-2xl p-6
                border border-orange-200 hover:shadow-md transition-all duration-300">
                                    <div class="text-center mt-6">
                                        <div class="bg-orange-100 rounded-2xl p-4 inline-flex mb-4">
                                            <i class="fas fa-bolt text-orange-600 text-2xl"></i>
                                        </div>
                                        <h4 class="font-bold text-gray-900 text-xl mb-4">Actions Rapides</h4>
                                        <div class="space-y-3">
                                            <button onclick="regenerateKeys()"
                                                class="w-full bg-orange-500 text-white hover:bg-orange-600 px-4 py-3 rounded-xl font-medium transition-colors shadow-md hover:shadow-lg">
                                                <i class="fas fa-sync-alt mr-2"></i>Regénérer les clés
                                            </button>
                                            <button onclick="showApiDocumentation()"
                                                class="w-full bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-3 rounded-xl font-medium transition-colors">
                                                <i class="fas fa-book mr-2"></i>Documentation
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <!-- Information Message -->
                            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 mb-6 mt-6 ml-3 mr-3 ">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-blue-500 text-xl mr-4 mt-1"></i>
                                    <div>
                                        <h4 class="font-semibold text-gray-900 mb-2">Comment utiliser vos clés API ?
                                        </h4>
                                        <p class="text-gray-600 text-sm mb-3">
                                            Utilisez votre API Key et Secret Key pour authentifier vos requêtes HTTP.
                                            Incluez-les dans les en-têtes de vos requêtes selon notre documentation.
                                        </p>
                                        <div class="bg-white rounded-lg p-4 border border-blue-100">
                                            <code class="text-xs text-gray-700 font-mono">
                                                Headers: {<br>
                                                &nbsp;&nbsp;"X-API-Key":
                                                "{{ substr($integrationClient->api_key, 0, 10) }}...",<br>
                                                &nbsp;&nbsp;"X-API-Secret":
                                                "{{ substr($integrationClient->api_secret, 0, 10) }}..."<br>
                                                }
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Fonction unifiée pour copier dans le presse-papier
        function copyToClipboard(text, buttonId) {
            navigator.clipboard.writeText(text).then(function() {
                const button = document.getElementById(buttonId);
                const originalIcon = button.innerHTML;

                // Feedback visuel de succès
                button.innerHTML = '<i class="fas fa-check text-green-500"></i>';
                button.classList.remove('text-gray-400', 'hover:text-blue-600', 'hover:text-purple-600');
                button.classList.add('text-green-500');

                // Réinitialiser après 2 secondes
                setTimeout(() => {
                    button.innerHTML = originalIcon;
                    button.classList.remove('text-green-500');
                    if (buttonId === 'api-key-copy') {
                        button.classList.add('text-gray-400', 'hover:text-blue-600');
                    } else {
                        button.classList.add('text-gray-400', 'hover:text-purple-600');
                    }
                }, 2000);

            }).catch(function(err) {
                console.error('Erreur lors de la copie: ', err);
                alert('Erreur lors de la copie vers le presse-papier');
            });
        }

        // Fonction pour afficher/masquer la clé secrète
        function toggleSecretVisibility() {
            const secretInput = document.getElementById('secret-key');
            const toggleButton = document.getElementById('toggle-secret');

            if (secretInput.type === 'password') {
                secretInput.type = 'text';
                toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
                toggleButton.classList.remove('hover:text-purple-600');
                toggleButton.classList.add('text-purple-600');
                toggleButton.title = "Masquer la clé secrète";
            } else {
                secretInput.type = 'password';
                toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
                toggleButton.classList.remove('text-purple-600');
                toggleButton.classList.add('hover:text-purple-600');
                toggleButton.title = "Afficher la clé secrète";
            }
        }

        function toggleKeyVisibility() {
            const secretInput = document.getElementById('api-key');
            const toggleButton = document.getElementById('toggle-key');

            if (secretInput.type === 'password') {
                secretInput.type = 'text';
                toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
                toggleButton.classList.remove('hover:text-purple-600');
                toggleButton.classList.add('text-purple-600');
                toggleButton.title = "Masquer la clé secrète";
            } else {
                secretInput.type = 'password';
                toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
                toggleButton.classList.remove('text-purple-600');
                toggleButton.classList.add('hover:text-purple-600');
                toggleButton.title = "Afficher la clé secrète";
            }
        }


        // Fonction pour regénérer les clés
        function regenerateKeys() {
            if (confirm(
                    'Êtes-vous sûr de vouloir regénérer vos clés API ? Cette action est irréversible et toutes les intégrations existantes cesseront de fonctionner.'
                )) {
                // Implémentez la logique de regénération des clés
                console.log('Regénération des clés API...');
            }
        }

        // Fonction pour afficher la documentation
        function showApiDocumentation() {
            // Implémentez l'ouverture de la documentation
            console.log('Ouvrir la documentation API...');
            // window.open('/api-documentation', '_blank');
        }

        // Sélection automatique du texte lors du clic sur les inputs
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[readonly]');
            inputs.forEach(input => {
                input.addEventListener('click', function() {
                    this.select();
                });
            });
        });
    </script>

    <style>
        /* Animations personnalisées */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .box {
            animation: fadeIn 0.5s ease-out;
        }

        /* Badges de statut */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .status-validated {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .status-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        /* Effets de hover pour les cartes */
        .bg-gradient-to-br {
            transition: all 0.3s ease;
        }

        .bg-gradient-to-br:hover {
            transform: translateY(-2px);
        }

        /* Style pour les inputs en lecture seule */
        input[readonly] {
            background-color: #f9fafb;
            cursor: pointer;
        }

        input[readonly]:focus {
            background-color: #ffffff;
            cursor: text;
        }

        /* Animation de pulse pour le statut en attente */
        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 5px rgba(245, 158, 11, 0.5);
            }

            50% {
                box-shadow: 0 0 15px rgba(245, 158, 11, 0.8);
            }
        }

        .animate-pulse-glow {
            animation: pulse-glow 2s infinite;
        }
    </style>
@endpush
