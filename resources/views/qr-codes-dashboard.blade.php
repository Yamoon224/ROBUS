<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire QR Codes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Gestionnaire de QR Codes</h1>
        
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8" id="stats">
            <!-- Rempli par JS -->
        </div>

        <!-- Contrôles -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">Lancer la génération</h2>
            <div class="flex gap-4">
                <input type="number" id="chunkSize" value="50" class="border rounded px-3 py-2 w-32">
                <input type="number" id="sleepTime" value="1" class="border rounded px-3 py-2 w-32">
                <button onclick="startGeneration()" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Démarrer
                </button>
                <button onclick="cancelGeneration()" class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600">
                    Annuler
                </button>
            </div>
        </div>

        <!-- Barre de progression -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between mb-2">
                <span class="font-medium">Progression</span>
                <span id="percentage">0%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                <div id="progressBar" class="bg-blue-500 h-4 rounded-full" style="width: 0%"></div>
            </div>
            <div id="details" class="text-sm text-gray-600"></div>
        </div>
    </div>

    <script>
        function updateStats() {
            fetch('/qr-codes/stats')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('stats').innerHTML = `
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="text-sm text-gray-600">Total wallets</div>
                                <div class="text-2xl font-bold">${data.data.total_wallets}</div>
                            </div>
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="text-sm text-gray-600">Avec QR code</div>
                                <div class="text-2xl font-bold text-green-600">${data.data.with_qr_code}</div>
                            </div>
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="text-sm text-gray-600">Sans QR code</div>
                                <div class="text-2xl font-bold text-red-600">${data.data.without_qr_code}</div>
                            </div>
                            <div class="bg-white rounded-lg shadow p-4">
                                <div class="text-sm text-gray-600">Espace utilisé</div>
                                <div class="text-2xl font-bold">${data.data.storage_used}</div>
                            </div>
                        `;
                    }
                });
        }

        function updateProgress() {
            fetch('/qr-codes/progress')
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('progressBar').style.width = data.data.percentage + '%';
                        document.getElementById('percentage').textContent = data.data.percentage + '%';
                        
                        document.getElementById('details').innerHTML = `
                            Traité: ${data.data.processed}/${data.data.total} | 
                            Erreurs: ${data.data.errors} | 
                    Mémoire: ${data.data.memory_usage} | 
                            Temps: ${data.data.execution_time} | 
                            ETA: ${data.data.eta}
                        `;
                    }
                });
        }

        function startGeneration() {
            const chunk = document.getElementById('chunkSize').value;
            const sleep = document.getElementById('sleepTime').value;
            
            fetch('/qr-codes/generate', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({chunk: chunk, sleep: sleep})
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Génération démarrée !');
                    setInterval(updateProgress, 2000);
                }
            });
        }

        function cancelGeneration() {
            fetch('/qr-codes/cancel', {method: 'POST'})
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('Génération annulée');
                    }
                });
        }

        // Mise à jour initiale
        updateStats();
        setInterval(updateStats, 5000);
        setInterval(updateProgress, 2000);
    </script>
</body>
</html>