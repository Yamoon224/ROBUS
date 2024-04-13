$(document).ready(function() {
        const amountInput = $('input[name="amount"]');
        var totalPercent;
    
        amountInput.on('input', function() {
            updateResult();
        });
    
        $('#country_id').on('change', function() {
            var country_id = $('#country_id').val();
            provider_id = false;
            ProviderUpdate(country_id);
            ProviderPercent(country_id);
        });
    
        function ProviderUpdate(countryId) {
            $.get(searchCountryUrl + countryId + "'", function(data) {
                $('#provider').empty();
                $.each(data, function(index, providers) {
                    $('#provider').append($('<option>', {
                        value: providers,
                        text: providers
                    }));
                });
                if (provider_id) {
                    $('#provider').val(provider_id).prop('selected', true);
                }
            });
        }
    
        function ProviderPercent(countryId) {
            $.get(searchPercentUrl + countryId, function(data) {
                if (data) {
                    var percentValue = data.percent * 100;
                    var adminPercentValue = data.percent_admin * 100;
                    totalPercent = percentValue + adminPercentValue;
                    $('#total-percent-inters').text('Frais Total : ' + totalPercent + ' % ');
                    $('#total-percent-wave').text(percentValue + "%");
                    $('#total-percent-robus').text(adminPercentValue + "%");
                } else {
                    $('#total-percent-inters').text('Aucune information disponible');
                    $('#total-percent-wave').text('Aucune information disponible');
                    $('#total-percent-robus').text('Aucune information disponible');
                }
            });
        }
    
        function updateResult() {
            const montantSaisi = parseFloat(amountInput.val());
            if (!isNaN(montantSaisi)) {
                const montantCalcule = montantSaisi * (totalPercent / 100);
                $('#total-percent-cumuls').text(montantCalcule.toFixed(2) + " FCFA");
            } else {
                $('#total-percent-cumuls').text('Aucune information disponible');
            }
        }
    });
    