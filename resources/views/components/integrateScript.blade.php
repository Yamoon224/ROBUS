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