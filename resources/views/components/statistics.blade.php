    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        setInterval(function() {
            const boxes = document.querySelectorAll('.style_app');
            boxes.forEach((box, index) => {
                const colors = ['grey', 'blue', 'green',
                    '#6C0277', '#34C924', '#6600FF', '#F9429E'
                ]; // Ajoutez d'autres couleurs si nécessaire
                const color = colors[index % colors.length];
                box.style.backgroundColor = color;

            });
        }, 1000);
    </script>
    <script>
        var sumVirement = JSON.parse($('#sum').attr('virement'));
        var sumDeposit = JSON.parse($('#sum').attr('deposit'));
        var sumPayment = JSON.parse($('#sum').attr('payment'));
        var sumReload = JSON.parse($('#sum').attr('reload'));
        var sumTransaction = JSON.parse($('#sum').attr('transaction'));
        var sumInter = JSON.parse($('#sum').attr('inter'));
        var sumPhone = JSON.parse($('#sum').attr('phone'));
        var sumXbet = JSON.parse($('#sum').attr('xbet'));
        var sumPush = JSON.parse($('#sum').attr('push'));
        var sumDjamo = JSON.parse($('#sum').attr('djamo'));
        var sumVersment = JSON.parse($('#sum').attr('versment'));
        var sumTresort = JSON.parse($('#sum').attr('tresort'));

        var sum_v_month = JSON.parse($('#sum_month').attr('virement_month'));
        var sum_d_month = JSON.parse($('#sum_month').attr('deposit_month'));
        var sum_p_month = JSON.parse($('#sum_month').attr('payment_month'));
        var sum_r_month = JSON.parse($('#sum_month').attr('reload_month'));
        var sum_t_month = JSON.parse($('#sum_month').attr('transaction_month'));
        var sum_i_month = JSON.parse($('#sum_month').attr('inter_month'));
        var sum_phone_month = JSON.parse($('#sum_month').attr('phone_month'));
        var sum_1xbetw_month = JSON.parse($('#sum_month').attr('xbet_month'));
        var sum_push_month = JSON.parse($('#sum_month').attr('push_month'));
        var sum_djamo_month = JSON.parse($('#sum_month').attr('djamo_month'));
        var sum_versment_month = JSON.parse($('#sum_month').attr('versment_month'));
        var sum_tresortMoney_month = JSON.parse($('#sum_month').attr('tresort_month'));

        var _ctx = $('#sum');
        var _ctx2 = $('#sum_month');

        new Chart(_ctx, {
            type: 'pie',
            data: {
                labels: [
                    'Virements',
                    'Cartes',
                    'Paiements',
                    'Rechargement',
                    'Transactions',
                    'Inters',
                    'Credits',
                    '1Xbet',
                    'Push',
                    'Djamo',
                    'Versement',
                    'Tresort',
                ],
                datasets: [{

                    label: 'Activites du jours',
                    data: [sumVirement, sumDeposit, sumPayment, sumReload,
                        sumTransaction, sumInter,  sumPhone, sumXbet, sumPush, sumDjamo, sumVersment, sumTresort
                    ],
                    backgroundColor: [
                        '#84cc17', // ✅ sumVirement → vert clair
                        '#f97417', // ✅ sumDeposit → orange vif
                        '#ff0101', // ✅ sumPayment → rouge
                        '#e11d48', // ✅ sumReload → rouge rosé
                        '#ff6384', // ✅ sumTransaction → rose clair
                        '#b5048f', // ✅ sumInter → violet
                        '#facc15', // ✅ sumPhone → jaune
                        '#3b82f6', // ✅ sumXbet → bleu moyen
                        '#14b8a6', // ✅ sumPush → turquoise
                        '#9333ea', // ✅ sumDjamo → violet profond
                        '#a855f7', // ✅ sumVersment → mauve clair
                        '#0ea5e9' // ✅ sumTresort → bleu ciel
                    ],

                    borderColor: [
                        '#84cc17', // ✅ sumVirement → vert clair
                        '#f97417', // ✅ sumDeposit → orange vif
                        '#ff0101', // ✅ sumPayment → rouge
                        '#e11d48', // ✅ sumReload → rouge rosé
                        '#ff6384', // ✅ sumTransaction → rose clair
                        '#b5048f', // ✅ sumInter → violet
                        '#facc15', // ✅ sumPhone → jaune
                        '#3b82f6', // ✅ sumXbet → bleu moyen
                        '#14b8a6', // ✅ sumPush → turquoise
                        '#9333ea', // ✅ sumDjamo → violet profond
                        '#a855f7', // ✅ sumVersment → mauve clair
                        '#0ea5e9' // ✅ sumTresort → bleu ciel

                    ],
                    borderWidth: 2,
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
                    'Credits',
                    '1Xbet',
                    'Push',
                    'Djamo',
                    'Versement',
                    'Tresort',
                ],
                datasets: [{

                    label: 'Activites du mois',
                    data: [sum_v_month, sum_d_month,
                        sum_p_month, sum_r_month, sum_t_month, sum_i_month, sum_phone_month,
                        sum_1xbetw_month , sum_push_month , sum_djamo_month , sum_versment_month,
                        sum_tresortMoney_month
                    ],
                    backgroundColor: [
                       '#84cc17', // ✅ sumVirement → vert clair
                        '#f97417', // ✅ sumDeposit → orange vif
                        '#ff0101', // ✅ sumPayment → rouge
                        '#e11d48', // ✅ sumReload → rouge rosé
                        '#ff6384', // ✅ sumTransaction → rose clair
                        '#b5048f', // ✅ sumInter → violet
                        '#facc15', // ✅ sumPhone → jaune
                        '#3b82f6', // ✅ sumXbet → bleu moyen
                        '#14b8a6', // ✅ sumPush → turquoise
                        '#9333ea', // ✅ sumDjamo → violet profond
                        '#a855f7', // ✅ sumVersment → mauve clair
                        '#0ea5e9' // ✅ sumTresort → bleu ciel

                    ],
                    borderColor: [
                        '#84cc17', // ✅ sumVirement → vert clair
                        '#f97417', // ✅ sumDeposit → orange vif
                        '#ff0101', // ✅ sumPayment → rouge
                        '#e11d48', // ✅ sumReload → rouge rosé
                        '#ff6384', // ✅ sumTransaction → rose clair
                        '#b5048f', // ✅ sumInter → violet
                        '#facc15', // ✅ sumPhone → jaune
                        '#3b82f6', // ✅ sumXbet → bleu moyen
                        '#14b8a6', // ✅ sumPush → turquoise
                        '#9333ea', // ✅ sumDjamo → violet profond
                        '#a855f7', // ✅ sumVersment → mauve clair
                        '#0ea5e9' // ✅ sumTresort → bleu ciel

                    ],
                    borderWidth: 1,
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