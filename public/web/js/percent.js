const totalPercentElement = document.getElementById('total-percent');
const totalPercent = parseFloat(totalPercentElement.dataset.totalPercent);
const percent_frais = totalPercent / 100;

const amountInput = document.querySelector('input[name="amount"]');
amountInput.addEventListener('input', function() {
    updateResult();
});

function updateResult() {
    const montantSaisi = parseFloat(amountInput.value);
    if (!isNaN(montantSaisi)) {
        const montantCalcule = montantSaisi * percent_frais;
        document.getElementById('result').innerText = montantCalcule.toFixed(2) + " FCFA";
    } else {
        document.getElementById('result').innerText = 'Saisir un montant';
    }
}
