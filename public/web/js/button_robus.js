// Sélectionnez l'élément d'entrée et le bouton
const montantInput = document.getElementById('montant_fixe');
const montantInputDeposit = document.getElementById('montant_deposit');
const montantInputPush = document.getElementById('montant_push');
const montantInputCard = document.getElementById('montant_card');
const montantInputRobus = document.getElementById('montant_robus');
const montantInputInters = document.getElementById('montant_inter');

const submitButtons = document.getElementById('submitButton');
const submitButtonPushs = document.getElementById('submitButtonPush');
const submitButtonDeposits = document.getElementById('submitButtonDeposit');
const submitButtonCards = document.getElementById('submitButtonCard');
const submitButtonRobus = document.getElementById('submitButtonRobus');
const submitButtonInters = document.getElementById('submitButtonInter');

const montantInfo = document.getElementById('montantInfo');
const montantInfoDeposits = document.getElementById('montantInfoDeposit');
const montantInfoPushs = document.getElementById('montantInfoPush');
const montantInfoCards = document.getElementById('montantInfoCard');
const montantInfoRobus = document.getElementById('montantInfoRobus');
const montantInfoInters = document.getElementById('montantInfoInter');


if(montantInput) 
{
// Ajoutez des écouteurs d'événements pour le changement d'entrée
montantInput.addEventListener('input', updateButtonState);
montantInput.addEventListener('blur', updateButtonState);
}

if(montantInputDeposit)
{
// Ajoutez des écouteurs d'événements pour les montants de dépôt
montantInputDeposit.addEventListener('input', updateButtonDepositState);
montantInputDeposit.addEventListener('blur', updateButtonDepositState);
}


if(montantInputPush)
{
// Ajoutez des écouteurs d'événements pour les montants de dépôt
montantInputPush.addEventListener('input', updateButtonPushState);
montantInputPush.addEventListener('blur', updateButtonPushState);
}

if(montantInputCard)
{
// Ajoutez des écouteurs d'événements pour les montants de dépôt
montantInputCard.addEventListener('input', updateButtonCardState);
montantInputCard.addEventListener('blur', updateButtonCardState);
}


if(montantInputRobus)
{
// Ajoutez des écouteurs d'événements pour les montants de dépôt
montantInputRobus.addEventListener('input', updateButtonRobusState);
montantInputRobus.addEventListener('blur', updateButtonRobusState);
}

if(montantInputInters)
{
// Ajoutez des écouteurs d'événements pour les montants de dépôt
montantInputInters.addEventListener('input', updateButtonIntersState);
montantInputInters.addEventListener('blur', updateButtonIntersState);
}

// Fonction pour mettre à jour l'état du bouton en fonction du montant saisi
function updateButtonState() {
    // Récupérez la valeur du montant saisi et convertissez-la en nombre
    const montant = parseFloat(montantInput.value);

    // Vérifiez si le montant est compris entre 2000 et 500 000
    if (montant >= 500 && montant <= 500000) {

        // Si oui, activez le bouton
        submitButtons.disabled = false;
        montantInfo.style.display = 'none';

    } else {
        // Sinon, désactivez le bouton
        submitButtons.disabled = true;
        montantInfo.style.display = 'block';
    }
}


// Fonction pour afficher le Swal.fire après avoir cliqué sur le bouton
function displaySwalSuccess(message) {
    Swal.fire({
        icon: 'success',
        text: message,
        timer: 2000,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
                timer.textContent = `${Swal.getTimerLeft()}`;
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log("I was closed by the timer");
        }
    });
}

function displaySwalError(message) {
    Swal.fire({
        icon: 'error',
        text: message,
        timer: 2000,
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
                timer.textContent = `${Swal.getTimerLeft()}`;
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.timer) {
            console.log("I was closed by the timer");
        }
    });
}

// Add listeners button
if (submitButtons) {
    submitButtons.addEventListener('click', function() {
        const montant = parseFloat(montantInput.value);
        if (montant >= 500 && montant <= 500000) {
            displaySwalSuccess(`Vous allez être redirigé vers l'application Wave pour le paiement de ${montant} FCFA`);
        }else {
            displaySwalError(`Vous devez verifiez le montant  ${montant} saisie`);
        }
    });
}

if (submitButtonPushs) {
    submitButtonPushs.addEventListener('click', function() {
        const montantPush = parseFloat(montantInputPush.value);
        if (montantPush >= 2000 && montantPush <= 500000) {
            displaySwalSuccess(`Vous allez être redirigé vers l'application Wave pour le paiement de ${montantPush} FCFA`);
        }else {
            displaySwalError(`Vous devez verifiez le montant  ${montantPush} saisie`);
        }
    });
}


if (submitButtonDeposits) {
    submitButtonDeposits.addEventListener('click', function() {
        const montantDeposit = parseFloat(montantInputDeposit.value);
        if (montantDeposit >= 500 && montantDeposit <= 500000) {
            displaySwalSuccess(`Vous allez être redirigé vers l'application Wave pour le paiement de ${montantDeposit} FCFA`);
        }else {
            displaySwalError(`Vous devez verifiez le montant  ${montantDeposit} saisie`);
        }
    });
}



if (submitButtonCards) {
    submitButtonCards.addEventListener('click', function() {
        const montantCard = parseFloat(montantInputCard.value);
        if (montantCard >= 2000 && montantCard <= 500000) {
            displaySwalSuccess(`Vous allez être redirigé vers l'application Wave pour le paiement de ${montantCard} FCFA`);
        }else {
            displaySwalError(`Vous devez verifiez le montant  ${montantCard} saisie`);
        }
    });
}



if (submitButtonRobus) {
    submitButtonRobus.addEventListener('click', function() {
        const montantRobus = parseFloat(montantInputRobus.value);
        if (montantRobus >= 2000 && montantRobus <= 500000) {
            displaySwalSuccess(`Vous allez être redirigé vers l'application Wave pour le paiement de ${montantRobus} FCFA`);
        }else {
            displaySwalError(`Vous devez verifiez le montant  ${montantRobus} saisie`);
        }
    });
}


if (submitButtonInters) {
    submitButtonInters.addEventListener('click', function() {
        const montantInters = parseFloat(montantInputInters.value);
        if (montantInters >= 2000 && montantInters <= 500000) {
            displaySwalSuccess(`Vous allez être redirigé vers l'application Wave pour le paiement de ${montantInters} FCFA`);
        }else {
            displaySwalError(`Vous devez verifiez le montant  ${montantInters} saisie`);
        }
    });
}


// Fonction pour mettre à jour l'état du bouton en fonction du montant de dépôt saisi
function updateButtonDepositState() {
    // Récupérez la valeur du montant de dépôt saisi et convertissez-la en nombre
    const montantDeposit = parseFloat(montantInputDeposit.value);
    // Vérifiez si le montant de dépôt est compris entre 2000 et 500 000
    if (montantDeposit >= 500 && montantDeposit <= 500000) {
        // Si oui, activez le bouton
        submitButtonDeposits.disabled = false;
        montantInfoDeposits.style.display = 'none';
    } else {
        // Sinon, désactivez le bouton
        submitButtonDeposits.disabled = true;
        montantInfoDeposits.style.display = 'block';
    }
}


function updateButtonPushState() {
        // Récupérez la valeur du montant de dépôt saisi et convertissez-la en nombre
        const montantPush = parseFloat(montantInputPush.value);
        // Vérifiez si le montant de dépôt est compris entre 2000 et 500 000
        if (montantPush >= 2000 && montantPush <= 500000) {
            // Si oui, activez le bouton
            submitButtonPushs.disabled = false;
            montantInfoPushs.style.display = 'none';
        } else {
            // Sinon, désactivez le bouton
            submitButtonPushs.disabled = true;
            montantInfoPushs.style.display = 'block';
        }
    }

    function updateButtonCardState() {
        // Récupérez la valeur du montant de dépôt saisi et convertissez-la en nombre
        const montantCard = parseFloat(montantInputCard.value);
        // Vérifiez si le montant de dépôt est compris entre 2000 et 500 000
        if (montantCard >= 2000 && montantCard <= 500000) {
            // Si oui, activez le bouton
            submitButtonCards.disabled = false;
            montantInfoCards.style.display = 'none';
        } else {
            // Sinon, désactivez le bouton
            submitButtonCards.disabled = true;
            montantInfoCards.style.display = 'block';
        }
    }

    function updateButtonRobusState() {
        // Récupérez la valeur du montant de dépôt saisi et convertissez-la en nombre
        const montantRobus = parseFloat(montantInputRobus.value);
        // Vérifiez si le montant de dépôt est compris entre 2000 et 500 000
        if (montantRobus >= 2000 && montantRobus <= 500000) {
            // Si oui, activez le bouton
            submitButtonRobus.disabled = false;
            montantInfoRobus.style.display = 'none';
        } else {
            // Sinon, désactivez le bouton
            submitButtonRobus.disabled = true;
            montantInfoRobus.style.display = 'block';
        }
    }

    function updateButtonIntersState() {
        // Récupérez la valeur du montant de dépôt saisi et convertissez-la en nombre
        const montantInters = parseFloat(montantInputInters.value);
        // Vérifiez si le montant de dépôt est compris entre 2000 et 500 000
        if (montantInters >= 2000 && montantInters <= 500000) {
            // Si oui, activez le bouton
            submitButtonInters.disabled = false;
            montantInfoInters.style.display = 'none';
        } else {
            // Sinon, désactivez le bouton
            submitButtonInters.disabled = true;
            montantInfoInters.style.display = 'block';
        }
    }