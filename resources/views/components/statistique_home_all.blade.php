<script>
        var sumVirement = JSON.parse($('#sum').attr('virement'));
        var sumDeposit = JSON.parse($('#sum').attr('deposit'));
        var sumPayment = JSON.parse($('#sum').attr('payment'));
        var sumReload = JSON.parse($('#sum').attr('reload'));
        var sumTransaction = JSON.parse($('#sum').attr('transaction'));
        var sumInter = JSON.parse($('#sum').attr('inter'));
        var sumPhone = JSON.parse($('#sum').attr('phone'));
        var sum_v_month = JSON.parse($('#sum_month').attr('virement_month'));
        var sum_d_month = JSON.parse($('#sum_month').attr('deposit_month'));
        var sum_p_month = JSON.parse($('#sum_month').attr('payment_month'));
        var sum_r_month = JSON.parse($('#sum_month').attr('reload_month'));
        var sum_t_month = JSON.parse($('#sum_month').attr('transaction_month'));
        var sum_i_month = JSON.parse($('#sum_month').attr('inter_month'));
        var sum_phone_month = JSON.parse($('#sum_month').attr('phone_month'));


        var relaods = JSON.parse($('#reload').attr('reload'));
        var sumReloadPaiement = JSON.parse($('#sum_paiement').attr('sum_paiement'));
        
        var _ctx = $('#sum');
        var _ctx_6 = $('#sum_paiement');
        var _ctx2 = $('#sum_month');
        var _ctx_3 = $('#reload');

        new Chart(_ctx_3, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
                    'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                    label: 'Rechargement année',
                    data: relaods,
                    backgroundColor: [
                        'rgba(255, 99, 132,1)',
                        'rgba(255, 159, 64,1)',
                        'rgba(255, 205, 86,1)',
                        'rgba(75, 192, 192,1)',
                        'rgba(54, 162, 235,1)',
                        'rgba(153, 102, 255,1)',
                        'rgba(201, 203, 207,1)',
                        'rgba(255, 0, 132,1)',
                        'rgba(255, 159, 64,1)',
                        'rgba(255, 205, 86,1)',
                        'rgba(75, 192, 192,1)',
                        'rgba(54, 162, 235,1)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132,1)',
                        'rgba(255, 159, 64,1)',
                        'rgba(255, 205, 86,1)',
                        'rgba(75, 192, 192,1)',
                        'rgba(54, 162, 235,1)',
                        'rgba(153, 102, 255,1)',
                        'rgba(201, 203, 207,1)',
                        'rgba(255, 0, 132,1)',
                        'rgba(255, 159, 64,1)',
                        'rgba(255, 205, 86,1)',
                        'rgba(75, 192, 192,1)',
                        'rgba(54, 162, 235,1)',
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    labels: {
                        render: 'percentage',
                        fontColor: ['white', 'white'],
                    }
                },
            }
        });

        new Chart(_ctx, {
            type: 'line',
            data: {
                labels: [
                    'Virements',
                    'Cartes',
                    'Paiements',
                    'Rechargement',
                    'Transactions',
                    'Inters',
                    'Credits'
                ],
                datasets: [{

                    label: 'Activites',
                    data: [sumVirement, sumDeposit, sumPayment, sumReload, sumTransaction, sumInter,
                        sumPhone
                    ],
                    backgroundColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'

                    ],
                    borderColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'
                    ],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    labels: {
                        render: 'percentage',
                        fontColor: ['white', 'white'],
                    }
                },
            }


        });


        new Chart(_ctx2, {
            type: 'bar',
            data: {
                labels: [
                    'Virements',
                    'Cartes',
                    'Paiements',
                    'Rechargement',
                    'Transactions',
                    'Inters',
                    'Credits'
                ],
                datasets: [{

                    label: 'Activites',
                    data: [sum_v_month, sum_d_month, sum_p_month, sum_r_month, sum_t_month, sum_i_month,
                        sum_phone_month
                    ],
                    backgroundColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'

                    ],
                    borderColor: [
                        '#84cc17',
                        '#f97417',
                        '#ff0101',
                        '#ff0000',
                        '#ff6384',
                        '#b5048f',
                        '#FFFF00'
                    ],
                    borderWidth: 4,
                }]
            },
            options: {
                animations: {
                    tension: {
                        duration: 1000,
                        easing: 'linear',
                        from: 1,
                        to: 0,
                        loop: true
                    }
                },

            }

        });

     

        new Chart(_ctx_6, {
            type: 'bar',
            data: {
                labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimande'
                ],
                datasets: [{
                    label: 'Rechargement',
                    data: sumReloadPaiement,
                    backgroundColor: [
                        'rgba(255, 99, 132,1)',
                        'rgba(255, 159, 64,1)',
                        'rgba(255, 205, 86,1)',
                        'rgba(75, 192, 192,1)',
                        'rgba(54, 162, 235,1)',
                        'rgba(153, 102, 255,1)',
                        'rgba(201, 203, 207,1)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132,1)',
                        'rgba(255, 159, 64,1)',
                        'rgba(255, 205, 86,1)',
                        'rgba(75, 192, 192,1)',
                        'rgba(54, 162, 235,1)',
                        'rgba(153, 102, 255,1)',
                        'rgba(201, 203, 207,1)'
                    ],
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    labels: {
                        render: 'percentage',
                        fontColor: ['white', 'white'],
                    }
                },
            }
        });
    </script>