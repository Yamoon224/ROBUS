<div class="rb-dash">
    @php
        $currency = $user_currencie ?? 'CFA';
        $canSeeCommissions = Auth::guard('web')->user()->hasPermissionTo('voir-commission-admin');

        // Statuts du jour : [service, succès, attente, échec]
        $statuses = [
            ['Transferts', $success_transaction, $pendding_transaction, $error_transaction],
            ['Paiements de factures', $success_payment, $pendding_payment, $error_payment],
            ['Crédits téléphoniques', $success_phone_credit, $pendding_phone_credit, $error_phone_credit],
            ['Cartes bancaires', $success_bank, $pendding_bank, $error_bank],
            ['Réchargements', $success_deposit, $pendding_deposit, $error_deposit],
            ['1Xbet', $success_xbet, $pendding_xbet, $error_xbet],
            ['International', $success_inter, $pendding_inter, $error_inter],
            ['Versements', $versment_success, $versment_padding, $versment_error],
            ['Push', $push_success, $push_pendding, $push_error],
            ['Djamo', $djamo_success, $djamo_padding, $djamo_error],
            ['Mobile Zone', $pixel_mobile_zone_success ?? 0, $pixel_mobile_zone_pendding ?? 0, $pixel_mobile_zone_error ?? 0],
        ];

        // Transferts par opérateur : [opérateur, couleur, montant, nombre]
        $operators = [
            ['Robus', '#155e75', $amount_transaction_robus_days ?? 0, null],
            ['Orange', '#ff7900', $amount_transaction_orange_days ?? 0, $count_transaction_orange_days ?? 0],
            ['MTN', '#ffcc00', $amount_transaction_mtn_days ?? 0, $count_transaction_mtn_days ?? 0],
            ['Moov', '#0066b3', $amount_transaction_moov_days ?? 0, $count_transaction_moov_days ?? 0],
            ['Wave', '#1dc8ff', $amount_transaction_wave_days ?? 0, $count_transaction_wave_days ?? 0],
        ];

        // Montants du jour par famille de services : [titre, lignes, [libellé du total, total]]
        $amountGroups = [
            ['Factures, cartes et paris', [
                ['CIE', $amount_payment_cie_days ?? 0],
                ['SODECI', $amount_payment_sodeci_days ?? 0],
                ['Canal', $amount_payment_canal_days ?? 0],
                ['Réchargement de cartes', $amount_deposit_days ?? 0],
                ['Achats 1Xbet', $amount_xbet_days ?? 0],
            ], ['Total des paiements', $amount_payment_days ?? 0]],
            ['International', [
                ['Mobiles', $amount_inter_moneyRobus ?? 0],
                ['Factures', $amount_inter_cie ?? 0],
                ['Crédits', $amount_inter_credit ?? 0],
                ['Virements', $amount_inter_virement ?? 0],
                ['Rechargements', $amount_inter_rechargement ?? 0],
            ], ['Total international', $amount_inter_days ?? 0]],
            ['Autres services', [
                ['Push', $amount_push_days ?? 0],
                ['Djamo', $amount_djamo_days ?? 0],
                ['Versements', $amount_versment_days ?? 0],
                ['Trésor Money', $amount_tresortMoney_days ?? 0],
                ['Mobile Zone', $amount_pixel_mobile_zone_days ?? 0],
            ], null],
        ];

        // Raccourcis : [libellé, nombre du jour, lien]
        $shortcuts = [
            ['Transferts d\'argent', $account_transaction_days ?? 0, route('transactions.browse')],
            ['Paiements de factures', $account_payment_days ?? 0, route('transactions.browse')],
            ['Virements bancaires', $account_bank_days ?? 0, route('transactions.browse')],
            ['Paiements internationaux', $account_inter_days ?? 0, route('transactions.browse')],
            ['Réchargements de carte', $account_deposit_days ?? 0, route('transactions.browse')],
            ['Réchargements', $account_reload_days ?? 0, route('transactions.uv.browse')],
            ['Djamo', $account_djamo_days ?? 0, route('transactions.browse')],
            ['Push', $account_push_days ?? 0, route('transactions.browse')],
            ['Versements', $account_versment_days ?? 0, route('transactions.browse')],
            ['Trésor Money', $account_tresort_days ?? 0, route('transactions.browse')],
            ['Mobile Zone', $account_pixel_mobile_zone_days ?? 0, route('pixel-mobile-zone.historical')],
        ];
    @endphp

    <style>
        .rb-dash {
            --rb-ink: #0f172a;
            --rb-muted: #64748b;
            --rb-line: #e2e8f0;
            --rb-surface: #ffffff;
            --rb-brand: rgb(var(--color-primary, 22 78 99));
            --rb-ok: #15803d;
            --rb-wait: #b45309;
            --rb-fail: #b91c1c;
            color: var(--rb-ink);
            padding: 1.25rem 0 2rem;
            display: grid;
            gap: 1.5rem;
            font-variant-numeric: tabular-nums;
        }

        .rb-dash h2 {
            font-size: 1.125rem;
            font-weight: 500;
            line-height: 1.3;
            margin: 0;
        }

        .rb-dash h3 {
            font-size: 0.9375rem;
            font-weight: 500;
            margin: 0;
        }

        .rb-panel {
            background: var(--rb-surface);
            border: 1px solid var(--rb-line);
            border-radius: 0.75rem;
            padding: 1.25rem;
            min-width: 0;
        }

        .rb-panel > h3 {
            margin-bottom: 0.875rem;
        }

        /* Soldes */
        .rb-balances {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr));
            gap: 1px;
            background: rgba(255, 255, 255, 0.18);
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .rb-balance {
            background: var(--rb-brand);
            color: #fff;
            padding: 1.25rem 1.5rem;
            min-width: 0;
        }

        .rb-balance-label {
            font-size: 0.875rem;
            opacity: 0.8;
        }

        .rb-balance-value {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 0.25rem 0.5rem;
            margin-top: 0.375rem;
            font-size: 1.75rem;
            font-weight: 500;
            line-height: 1.15;
            overflow-wrap: anywhere;
        }

        .rb-balance-value small {
            font-size: 0.875rem;
            font-weight: 400;
            opacity: 0.8;
        }

        .rb-balance--main .rb-balance-value {
            font-size: 2.25rem;
        }

        /* Comptes du réseau */
        .rb-people {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(7rem, 1fr));
            gap: 1rem 1.5rem;
            margin: 0;
        }

        .rb-people div {
            border-left: 2px solid var(--rb-line);
            padding-left: 0.75rem;
        }

        .rb-people dt {
            font-size: 0.8125rem;
            color: var(--rb-muted);
        }

        .rb-people dd {
            margin: 0.125rem 0 0;
            font-size: 1.5rem;
            font-weight: 500;
            line-height: 1.2;
        }

        /* Grille principale */
        .rb-grid {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: minmax(0, 1fr);
            align-items: start;
        }

        .rb-amounts {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 17rem), 1fr));
            align-items: start;
        }

        @media (max-width: 999px) {
            .rb-balance--main { grid-column: 1 / -1; }
        }

        @media (min-width: 1100px) {
            .rb-grid {
                grid-template-columns: minmax(0, 5fr) minmax(0, 7fr);
            }
        }

        /* Statuts du jour */
        .rb-status {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        .rb-status th,
        .rb-status td {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--rb-line);
            text-align: right;
            white-space: nowrap;
        }

        .rb-status tbody tr:last-child th,
        .rb-status tbody tr:last-child td {
            border-bottom: 0;
        }

        .rb-status th + th,
        .rb-status td {
            padding-left: 0.75rem;
        }

        .rb-status th:first-child {
            text-align: left;
            white-space: normal;
            font-weight: 400;
        }

        .rb-status thead th {
            font-size: 0.8125rem;
            font-weight: 400;
            color: var(--rb-muted);
            padding-top: 0;
        }

        .rb-status td {
            width: 1%;
            font-weight: 500;
        }

        .rb-status thead th span::before {
            content: '';
            display: inline-block;
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            margin-right: 0.375rem;
            background: currentColor;
        }

        .rb-ok { color: var(--rb-ok); }
        .rb-wait { color: var(--rb-wait); }
        .rb-fail { color: var(--rb-fail); }
        .rb-status td.rb-zero { color: #cbd5e1; font-weight: 400; }

        .rb-note {
            margin: 0.875rem 0 0;
            font-size: 0.8125rem;
            color: var(--rb-muted);
        }

        /* Montants */
        .rb-rows {
            margin: 0;
            display: grid;
        }

        .rb-rows div {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 1rem;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--rb-line);
        }

        .rb-rows dt {
            font-size: 0.875rem;
            color: var(--rb-muted);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            min-width: 0;
        }

        .rb-rows dd {
            margin: 0;
            font-weight: 500;
            text-align: right;
            white-space: nowrap;
        }

        .rb-rows dd small {
            display: block;
            font-size: 0.75rem;
            font-weight: 400;
            color: var(--rb-muted);
        }

        .rb-rows .rb-total {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .rb-rows .rb-total dt {
            color: var(--rb-ink);
        }

        .rb-rows .rb-total dd {
            font-size: 1.125rem;
        }

        .rb-rows div:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .rb-swatch {
            flex: none;
            width: 0.625rem;
            height: 0.625rem;
            border-radius: 2px;
        }

        .rb-unit {
            font-weight: 400;
            font-size: 0.8125rem;
            color: var(--rb-muted);
        }

        /* Raccourcis */
        .rb-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 14rem), 1fr));
            gap: 0.75rem;
        }

        .rb-links a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            min-height: 3rem;
            padding: 0.625rem 0.875rem;
            background: var(--rb-surface);
            border: 1px solid var(--rb-line);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            color: var(--rb-ink);
            text-decoration: none;
            transition: border-color 0.15s;
        }

        .rb-links a:hover {
            border-color: var(--rb-brand);
        }

        .rb-links a:focus-visible {
            outline: 2px solid var(--rb-brand);
            outline-offset: 2px;
        }

        .rb-links b {
            flex: none;
            min-width: 1.75rem;
            padding: 0.125rem 0.5rem;
            border-radius: 999px;
            background: #f1f5f9;
            font-weight: 500;
            text-align: center;
        }

        @media (max-width: 480px) {
            .rb-panel { padding: 1rem; }
            .rb-balance { padding: 1rem 1.125rem; }
            .rb-balance--main .rb-balance-value { font-size: 1.875rem; }
            .rb-status th + th, .rb-status td { padding-left: 0.5rem; }
            .rb-status thead th span::before { display: none; }
            .rb-status th:first-child { overflow-wrap: anywhere; }
        }

        @media (prefers-reduced-motion: reduce) {
            .rb-links a { transition: none; }
        }
    </style>

    <section class="rb-balances" aria-label="Soldes">
        <div class="rb-balance rb-balance--main">
            <div class="rb-balance-label">Solde actuel</div>
            <div class="rb-balance-value">
                {{ $walletSolde ?? 0 }} <small>{{ $usersCountry ?? 'CFA' }}</small>
            </div>
        </div>

        @if ($canSeeCommissions)
            <div class="rb-balance">
                <div class="rb-balance-label">Commissions des sous-distributeurs</div>
                <div class="rb-balance-value">
                    {{ $walletCommissions ?? 0 }} <small>{{ $usersCountry ?? 'CFA' }}</small>
                </div>
            </div>
            <div class="rb-balance">
                <div class="rb-balance-label">Commissions admin</div>
                <div class="rb-balance-value">
                    {{ $walletAdmin ?? 0 }} <small>{{ $usersCountry ?? 'CFA' }}</small>
                </div>
            </div>
        @endif
    </section>

    @if (sabdyHomeVerify())
        <section class="rb-panel" aria-label="Comptes du réseau">
            <dl class="rb-people">
                <div><dt>Clients</dt><dd>{{ $is_custumers ?? 0 }}</dd></div>
                <div><dt>Opératrices</dt><dd>{{ $is_distributors ?? 0 }}</dd></div>
                <div><dt>Sous-distributeurs</dt><dd>{{ $is_underdistributors ?? 0 }}</dd></div>
                <div><dt>Compagnies</dt><dd>{{ $is_compagnies ?? 0 }}</dd></div>
                <div><dt>Masters</dt><dd>{{ $is_masters ?? 0 }}</dd></div>
                <div><dt>Superviseurs</dt><dd>{{ $is_supervisors ?? 0 }}</dd></div>
            </dl>
        </section>
    @endif

    <h2>Activité du jour</h2>

    <div class="rb-grid">
        <section class="rb-panel">
            <h3>Opérations par statut</h3>
            <table class="rb-status">
                <thead>
                    <tr>
                        <th scope="col">Service</th>
                        <th scope="col"><span class="rb-ok">Succès</span></th>
                        <th scope="col"><span class="rb-wait">Attente</span></th>
                        <th scope="col"><span class="rb-fail">Échec</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($statuses as [$label, $ok, $wait, $fail])
                        <tr>
                            <th scope="row">{{ $label }}</th>
                            <td class="{{ empty($ok) ? 'rb-zero' : 'rb-ok' }}">{{ $ok ?? 0 }}</td>
                            <td class="{{ empty($wait) ? 'rb-zero' : 'rb-wait' }}">{{ $wait ?? 0 }}</td>
                            <td class="{{ empty($fail) ? 'rb-zero' : 'rb-fail' }}">{{ $fail ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="rb-note">Remboursements Mobile Zone : {{ $pixel_mobile_zone_refund ?? 0 }}</p>
        </section>

        <div class="rb-amounts">
            <section class="rb-panel">
                <h3>Transferts par opérateur <span class="rb-unit">({{ $currency }})</span></h3>
                <dl class="rb-rows">
                    @foreach ($operators as [$name, $color, $amount, $count])
                        <div>
                            <dt><span class="rb-swatch" style="background: {{ $color }}"></span>{{ $name }}</dt>
                            <dd>
                                {{ $amount }}
                                @if ($count !== null)
                                    <small>{{ $count }} transaction(s)</small>
                                @endif
                            </dd>
                        </div>
                    @endforeach
                    <div>
                        <dt>Directs (Orange, MTN, Moov, Wave)</dt>
                        <dd>
                            {{ $amount_transaction_direct_days ?? 0 }}
                            <small>{{ $count_transaction_direct_days ?? 0 }} transaction(s)</small>
                        </dd>
                    </div>
                    <div class="rb-total">
                        <dt>Total des transferts</dt>
                        <dd>{{ $amount_transaction_days ?? 0 }}</dd>
                    </div>
                </dl>
            </section>

            @foreach ($amountGroups as [$title, $rows, $total])
                <section class="rb-panel">
                    <h3>{{ $title }} <span class="rb-unit">({{ $currency }})</span></h3>
                    <dl class="rb-rows">
                        @foreach ($rows as [$label, $amount])
                            <div>
                                <dt>{{ $label }}</dt>
                                <dd>{{ $amount }}</dd>
                            </div>
                        @endforeach
                        @if ($total)
                            <div class="rb-total">
                                <dt>{{ $total[0] }}</dt>
                                <dd>{{ $total[1] }}</dd>
                            </div>
                        @endif
                    </dl>
                </section>
            @endforeach
        </div>
    </div>

    <h2>Ouvrir un service</h2>

    <ul class="rb-links">
        @foreach ($shortcuts as [$label, $count, $url])
            <li>
                <a href="{{ $url }}">
                    <span>{{ $label }}</span>
                    <b>{{ $count }}</b>
                </a>
            </li>
        @endforeach
    </ul>

    @stack('scripts')
</div>
