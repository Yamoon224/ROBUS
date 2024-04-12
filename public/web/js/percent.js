const totalPercentElement = document.getElementById('total-percent');
const totalPercentElementPhone = document.getElementById('total-percent-phone');
const totalPercent = parseFloat(totalPercentElement.dataset.totalPercent);
// const totalPercentPhone = parseFloat(totalPercentElementPhone.dataset.totalPercentX);
const totalPercentPhone = totalPercentElementPhone ? parseFloat(totalPercentElementPhone.dataset.totalPercentX) : 0; // Vérifie si l'élément existe avant d'accéder à ses propriétés

const percent_frais = totalPercent / 100;
const percent_frais_phone = totalPercentPhone / 100;

const amountInput = document.querySelector('input[name="amount"]');
amountInput.addEventListener('input', function() {
    updateResult();
});

function updateResult() {
    const montantSaisi = parseFloat(amountInput.value);
    if (!isNaN(montantSaisi)) {
        const montantCalcule = montantSaisi * percent_frais;
        const montantCalculePhonee = montantSaisi * percent_frais_phone;
        document.getElementById('result').innerText = montantCalcule.toFixed(2) + " FCFA";
        document.getElementById('resultPhone').innerText = montantCalculePhonee.toFixed(2) + " FCFA";
    } else {
        document.getElementById('result').innerText = 'Saisir un montant';
    }
}
