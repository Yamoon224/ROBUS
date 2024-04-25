document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.querySelector('input[name="amount"]');
    if (amountInput) {
        amountInput.addEventListener('input', updateResult);
    }

    function updateResult() {
        const montantSaisi = parseFloat(amountInput.value);
        if (isNaN(montantSaisi)) {
            clearResults('Saisir un montant');
            return;
        }

        // Mettre à jour les résultats pour chaque type de frais
        updateFieldResult('total-percent-transfert', 'resultTransfert', montantSaisi, 'totalPercentTrans');
        updateFieldResult('total-percent-phone', 'resultPhone', montantSaisi, 'totalPercentX');
        updateFieldResult('total-percent', 'result', montantSaisi, 'totalPercent');
    }

    function updateFieldResult(percentElementId, resultElementId, montantSaisi, dataAttributeSuffix) {
        const percentElement = document.getElementById(percentElementId);
        const resultElement = document.getElementById(resultElementId);

        if (percentElement && resultElement) {
            const percentValue = parseFloat(percentElement.dataset[dataAttributeSuffix]);
            if (isNaN(percentValue)) {
                console.log(`Erreur: La valeur de pourcentage pour ${percentElementId} n'est pas un nombre valide.`);
                resultElement.innerText = "Erreur de pourcentage";
                return;
            }
            const calcul = montantSaisi * (percentValue / 100);
            resultElement.innerText = `${calcul.toFixed(2)} FCFA`;
        } else {
            console.log(`Erreur: Élément non trouvé pour ${percentElementId} ou ${resultElementId}.`);
        }
    }

    function clearResults(defaultMessage) {
        // Nettoyer les champs de résultat
        ['resultTransfert', 'resultPhone' , 'result'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.innerText = defaultMessage;
            }
        });
    }
});
