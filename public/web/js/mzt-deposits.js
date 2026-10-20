const mztState = { sender: null, recipient: null };
const mztDistinct = !!(window._MZT && window._MZT.distinctCountries);

    function mztMarkInvalid(fieldId) {
        const el = document.getElementById(fieldId);
        if (el) el.classList.add('is-invalid');
    }
    function mztClearInvalid(fieldId) {
        const el = document.getElementById(fieldId);
        if (el) el.classList.remove('is-invalid');
    }
    // Garde uniquement les chiffres — l'API PixelInnov n'accepte que des numéros purement numériques
    function mztSanitizePhone(raw) {
        return (raw || '').replace(/\D/g, '');
    }

    /* ── Sélection du pays ──────────────────────────────────────── */
    function mztPickCountry(card, side) {
        const isSender       = side === 'sender';
        const countryStepId  = isSender ? 'mztSenderCountryStep'   : 'mztRecipientCountryStep';
        const providerStepId = isSender ? 'mztSenderProviderStep'   : 'mztRecipientProviderStep';
        const flagId         = isSender ? 'mztSenderCountryFlag'    : 'mztRecipientCountryFlag';
        const labelId        = isSender ? 'mztSenderCountryLabel'   : 'mztRecipientCountryLabel';

        // Highlight country card
        card.closest('.mzt-country-grid')
            .querySelectorAll('.mzt-ctry-card')
            .forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');

        // Fill back-row label
        const el = document.getElementById(labelId);
        const fl = document.getElementById(flagId);
        if (el) el.textContent = card.dataset.countryName || '';
        if (fl) fl.textContent = card.dataset.countryFlag || '';

        // Switch to provider view
        document.getElementById(countryStepId).classList.add('d-none');
        document.getElementById(providerStepId).classList.remove('d-none');

        // Show providers for this country, hide others
        const providerStep = document.getElementById(providerStepId);
        providerStep.querySelectorAll('.mzt-op-group').forEach(g => g.classList.add('d-none'));
        const targetGroup = providerStep.querySelector(`.mzt-op-group[data-country-id="${card.dataset.countryId}"]`);
        if (targetGroup) targetGroup.classList.remove('d-none');

        // International Zone : le pays expéditeur doit différer du pays destinataire
        if (mztDistinct) {
            const oppGrid = document.getElementById(isSender ? 'mztRecipientCountryStep' : 'mztSenderCountryStep');
            if (oppGrid) {
                oppGrid.querySelectorAll('.mzt-ctry-card.mzt-excluded').forEach(c => c.classList.remove('mzt-excluded'));
                const toExclude = oppGrid.querySelector(`.mzt-ctry-card[data-country-id="${card.dataset.countryId}"]`);
                if (toExclude) toExclude.classList.add('mzt-excluded');
            }
        }

        // Clear previous operator selection for this side
        mztState[side] = null;
        const sideEl = document.getElementById(isSender ? 'mztSideSender' : 'mztSideRecipient');
        if (sideEl) sideEl.classList.remove('has-selection');

        mztRefreshRouteBar();

        // Pays expéditeur choisi : le pays destinataire est pré-sélectionné sur le même pays
        // (tant que le destinataire n'a pas déjà un opérateur choisi). Modifiable via « retour ».
        if (!mztDistinct && isSender && !mztState.recipient) {
            const recipCard = document.querySelector(
                `#mztRecipientCountryStep .mzt-ctry-card[data-country-id="${card.dataset.countryId}"]`
            );
            const recipStepVisible = !document.getElementById('mztRecipientCountryStep').classList.contains('d-none');
            if (recipCard && recipStepVisible) mztPickCountry(recipCard, 'recipient');
        }
    }

    /* ── Retour au choix du pays ────────────────────────────────── */
    function mztBackToCountry(side) {
        const isSender       = side === 'sender';
        const countryStepId  = isSender ? 'mztSenderCountryStep'   : 'mztRecipientCountryStep';
        const providerStepId = isSender ? 'mztSenderProviderStep'   : 'mztRecipientProviderStep';
        document.getElementById(providerStepId).classList.add('d-none');
        document.getElementById(countryStepId).classList.remove('d-none');

        if (mztDistinct) {
            const oppGrid = document.getElementById(isSender ? 'mztRecipientCountryStep' : 'mztSenderCountryStep');
            if (oppGrid) oppGrid.querySelectorAll('.mzt-ctry-card.mzt-excluded').forEach(c => c.classList.remove('mzt-excluded'));
        }

        // Clear selection
        mztState[side] = null;
        const sideEl = document.getElementById(isSender ? 'mztSideSender' : 'mztSideRecipient');
        if (sideEl) sideEl.classList.remove('has-selection');

        mztRefreshRouteBar();
    }

    /* ── Table de confirmation USSD / SMS / App ─────────────────── */
    const mztConfirmTable = {
        // Côte d'Ivoire
        'orange_ci':        { type:'ussdOtp',   ussdCode:'#144*82#',              shortLabel:'Code OTP Orange CI',     instruction:'Composez #144*82# sur votre téléphone Orange pour générer votre code OTP, puis validez la transaction.' },
        'mtn_ci':           { type:'ussdDebit', ussdCode:'*133#',                 shortLabel:'USSD MTN CI',            instruction:'Après réception du SMS de débit MTN, composez *133# pour confirmer le paiement.' },
        'moov_ci':          { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Moov CI',       instruction:'Vous recevrez un SMS push Moov Money. Saisissez votre code secret pour valider.' },
        'wave_ci':          { type:'app',       ussdCode:null,                    shortLabel:'App Wave CI',            instruction:"Ouvrez l'application Wave et confirmez le paiement en attente." },
        // Sénégal
        'orange_sn':        { type:'ussdOtp',   ussdCode:'#144#391#',             shortLabel:'Code OTP Orange SN',     instruction:'Composez #144#391# sur votre téléphone Orange Sénégal pour générer votre code OTP.' },
        'expresso_sn':      { type:'ussdDebit', ussdCode:'*444*3*3#',             shortLabel:'USSD Expresso SN',       instruction:'Après réception du SMS de débit, composez *444*3*3# pour confirmer.' },
        'free_sn':          { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Free SN',       instruction:'Vous recevrez un SMS push. Saisissez votre code secret Free Money pour valider.' },
        'wave_sn':          { type:'app',       ussdCode:null,                    shortLabel:'App Wave SN',            instruction:"Ouvrez l'application Wave et confirmez le paiement en attente." },
        // Mali
        'orange_ml':        { type:'ussdOtp',   ussdCode:'#144#77#',              shortLabel:'Code OTP Orange ML',     instruction:'Composez #144#77# sur votre téléphone Orange Mali pour générer votre code OTP.' },
        'moov_ml':          { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Moov ML',       instruction:'Vous recevrez un SMS push Moov Money. Saisissez votre code secret pour valider.' },
        // Togo
        'tmoney_tg':        { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push T-Money TG',    instruction:'Vous recevrez un SMS push T-Money. Saisissez votre code secret pour valider.' },
        'flooz_tg':         { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Flooz TG',      instruction:'Vous recevrez un SMS push Flooz. Saisissez votre code secret pour valider.' },
        // Bénin
        'moov_bj':          { type:'ussdDebit', ussdCode:'*855*7*8#',             shortLabel:'USSD Moov BJ',           instruction:'Composez *855*7*8# après réception du SMS pour confirmer le paiement Moov Money.' },
        'mtn_bj':           { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push MTN BJ',        instruction:'Vous recevrez un SMS push MTN MoMo. Saisissez votre code secret pour valider.' },
        // Burkina Faso
        'orange_bf':        { type:'ussdOtp',   ussdCode:'*144*4*6*Montant*PIN#', shortLabel:'Code OTP Orange BF',     instruction:'Composez *144*4*6*[Montant]*[PIN]# sur votre téléphone Orange Burkina pour valider.' },
        'moov_bf':          { type:'ussdDebit', ussdCode:'*555*6#',               shortLabel:'USSD Moov BF',           instruction:'Composez *555*6# après réception du SMS pour confirmer le paiement Moov BF.' },
        // Cameroun
        'orange_cm':        { type:'ussdDebit', ussdCode:'#150*50#',              shortLabel:'USSD Orange CM',         instruction:'Composez #150*50# après réception du SMS pour confirmer le paiement Orange CM.' },
        'express union_cm': { type:'app',       ussdCode:null,                    shortLabel:'App Express Union CM',   instruction:"Confirmez la transaction depuis l'application Express Union CM." },
        'mtn_cm':           { type:'ussdDebit', ussdCode:'*126#',                 shortLabel:'USSD MTN CM',            instruction:'Composez *126# après réception du SMS pour confirmer le paiement MTN CM.' },
        'mtnmoney_cm':      { type:'ussdDebit', ussdCode:'*126#',                 shortLabel:'USSD MTN CM',            instruction:'Composez *126# après réception du SMS pour confirmer le paiement MTN CM.' },
        // RD Congo / Congo
        'orange_cd':        { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Orange CD',     instruction:'Vous recevrez un SMS push Orange Money Congo. Saisissez votre code secret.' },
        'orange_cg':        { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Orange Congo',  instruction:'Vous recevrez un SMS push Orange Money Congo. Saisissez votre code secret.' },
        'm pesa_cd':        { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push M-Pesa CD',     instruction:'Vous recevrez un SMS push M-Pesa. Saisissez votre code secret pour valider.' },
        'mpesa_cd':         { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push M-Pesa CD',     instruction:'Vous recevrez un SMS push M-Pesa. Saisissez votre code secret pour valider.' },
        'mpesa_cg':         { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push M-Pesa Congo',  instruction:'Vous recevrez un SMS push M-Pesa. Saisissez votre code secret pour valider.' },
        'airtel_cd':        { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Airtel CD',     instruction:'Vous recevrez un SMS push Airtel Money. Saisissez votre code secret.' },
        'airtel_cg':        { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Airtel Congo',  instruction:'Vous recevrez un SMS push Airtel Money. Saisissez votre code secret.' },
        // Gambie
        'afrimoney_gm':     { type:'ussdDebit', ussdCode:'*777#',                 shortLabel:'USSD AfriMoney GM',      instruction:'Composez *777# sur votre ligne Africell/AfriMoney Gambie après réception de la demande, puis confirmez avec votre code secret.' },
        'qmoney_gm':        { type:'ussdDebit', ussdCode:'*323#',                 shortLabel:'USSD QMoney GM',         instruction:'Composez *323# sur votre ligne QCell/QMoney Gambie après réception de la demande, puis confirmez avec votre code secret.' },
        'q_money_gm':       { type:'ussdDebit', ussdCode:'*323#',                 shortLabel:'USSD QMoney GM',         instruction:'Composez *323# sur votre ligne QCell/QMoney Gambie après réception de la demande, puis confirmez avec votre code secret.' },
        // Guinée
        'orange_gn':        { type:'ussdOtp',   ussdCode:'#144#',                 shortLabel:'Code OTP Orange GN',     instruction:"Composez #144# → choisissez l'option 4 puis l'option 2 pour générer votre code OTP Orange Guinée." },
        'mtn_gn':           { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push MTN GN',        instruction:'Vous recevrez un SMS push MTN MoMo Guinée. Saisissez votre code secret.' },
    };

    function mztGetConfirmInfo(providerName, countryIso2) {
        if (!providerName || !countryIso2) return null;
        const iso2        = countryIso2.toLowerCase().trim();
        const normalized  = providerName.toLowerCase().trim();
        const noHyphen    = normalized.replace(/-/g, '');
        const compact     = normalized.replace(/[^a-z0-9]/g, '');
        const firstWord   = normalized.split(/\s+/)[0];
        const fwNoHyphen  = firstWord.replace(/-/g, '');
        const underscored = normalized.replace(/\s+/g, '_');
        const keys = [
            `${normalized}_${iso2}`,
            `${firstWord}_${iso2}`,
            `${noHyphen}_${iso2}`,
            `${compact}_${iso2}`,
            `${fwNoHyphen}_${iso2}`,
            `${underscored}_${iso2}`,
        ];
        for (const key of keys) {
            if (mztConfirmTable[key]) return mztConfirmTable[key];
        }
        return null;
    }

    function mztRenderUssdBanner(info) {
        if (!info) return '';
        const styles = {
            ussdOtp:   { bg:'#fff7ed', border:'#f97316', text:'#c2410c', icon:'bi-dialpad-fill' },
            ussdDebit: { bg:'#eff6ff', border:'#3b82f6', text:'#1d4ed8', icon:'bi-telephone-outbound-fill' },
            smsPush:   { bg:'#f0fdfa', border:'#14b8a6', text:'#0f766e', icon:'bi-chat-dots-fill' },
            app:       { bg:'#eef2ff', border:'#818cf8', text:'#4338ca', icon:'bi-phone-fill' },
            qrCode:    { bg:'#faf5ff', border:'#c084fc', text:'#7e22ce', icon:'bi-qr-code-scan' },
            smsLink:   { bg:'#fff7ed', border:'#f97316', text:'#c2410c', icon:'bi-link-45deg' },
        };
        const s = styles[info.type] || styles.smsPush;
        const ussdHtml = info.ussdCode
            ? `<div style="margin:8px 0 4px">
                   <code style="display:inline-block;padding:5px 18px;border-radius:8px;
                       background:${s.bg};border:2px solid ${s.border};color:${s.text};
                       font-size:1.1rem;font-weight:900;letter-spacing:2.5px">${info.ussdCode}</code>
               </div>` : '';
        return `<div style="padding:14px 16px;border-radius:14px;background:${s.bg};
                border:1.5px solid ${s.border};animation:mztFadeSlide .25s ease">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                <i class="bi ${s.icon}" style="color:${s.text};font-size:.95rem;flex:0 0 auto"></i>
                <strong style="font-size:.83rem;font-weight:800;color:${s.text}">${info.shortLabel}</strong>
            </div>
            ${ussdHtml}
            <p style="margin:6px 0 0;font-size:.82rem;color:${s.text};opacity:.88;line-height:1.5">${info.instruction}</p>
        </div>`;
    }

    /* ── Sélection de l'opérateur ───────────────────────────────── */
    function mztSelectOp(card, side) {
        const isSender = side === 'sender';
        const cls      = isSender ? 'mzt-selected-sender' : 'mzt-selected-recipient';
        const sideEl   = document.getElementById(isSender ? 'mztSideSender' : 'mztSideRecipient');

        // Deselect all other cards in this side
        document.querySelectorAll(`.mzt-op-card[data-side="${side}"]`)
                .forEach(c => c.classList.remove(cls));
        card.classList.add(cls);

        if (sideEl) sideEl.classList.add('has-selection');

        mztState[side] = {
            name:        card.dataset.opName           || '—',
            image:       card.dataset.opImage          || '',
            providerId:  card.dataset.providerId       || '',
            serviceId:   card.dataset.serviceId        || '',
            country:     card.dataset.country          || '',
            countryIso2: card.dataset.countryIso2      || '',
            indicatif:   card.dataset.countryIndicatif || '',
        };

        mztRefreshRouteBar();
    }

    /* ── Mise à jour de la route bar ────────────────────────────── */
    function mztRefreshRouteBar() {
        const bar = document.getElementById('mztRouteBar');
        const s   = mztState.sender;
        const r   = mztState.recipient;

        if (s) {
            document.getElementById('mztRouteSenderName').textContent    = s.name + (s.country ? ' · ' + s.country : '');
            mztSetAvatar('mztRouteSenderAvatar', s.image);
        }
        if (r) {
            document.getElementById('mztRouteRecipientName').textContent = r.name + (r.country ? ' · ' + r.country : '');
            mztSetAvatar('mztRouteRecipientAvatar', r.image);
        }

        bar.classList.toggle('d-none', !(s && r));
    }

    function mztSetAvatar(id, imgUrl) {
        const el = document.getElementById(id);
        if (!el || !imgUrl) return;
        el.innerHTML = `<img src="${imgUrl}" style="width:100%;height:100%;object-fit:contain;"
            onerror="this.parentElement.innerHTML='<i class=\\'bi bi-phone-fill\\'></i>'">`;
    }

    /* ── Continuer vers le formulaire ───────────────────────────── */
    function mztProceed() {
        const s = mztState.sender;
        const r = mztState.recipient;
        if (!s || !r) return;

        // Champs cachés service
        const si = document.getElementById('inputSenderServiceId');
        const ri = document.getElementById('inputRecipientServiceId');
        if (si) si.value = s.serviceId;
        if (ri) ri.value = r.serviceId;

        // Remplir les cartes info en lecture seule
        mztFillInfoCard('mztFormSenderOp', 'mztFormSenderCountry', 'mztFormSenderAvatar', s);
        mztFillInfoCard('mztFormRecipOp',  'mztFormRecipCountry',  'mztFormRecipAvatar',  r);

        // Bandeau USSD / confirmation pour l'opérateur expéditeur
        const confirmInfo   = mztGetConfirmInfo(s.name, s.countryIso2);
        const bannerWrap    = document.getElementById('mztUssdBannerWrap');
        const otpFieldWrap  = document.getElementById('mztOtpFieldWrap');
        const otpInput      = document.getElementById('om_otp');
        if (bannerWrap) {
            bannerWrap.innerHTML = mztRenderUssdBanner(confirmInfo);
            bannerWrap.style.display = confirmInfo ? 'block' : 'none';
        }
        const requiresOtp = confirmInfo?.type === 'ussdOtp';
        if (otpFieldWrap) otpFieldWrap.classList.toggle('d-none', !requiresOtp);
        if (otpInput)     otpInput.required = requiresOtp;

        // Afficher l'indicatif pays dans les champs téléphone
        mztSetPhonePrefix('mztSenderPhoneField', 'my_phone', s.indicatif);
        mztSetPhonePrefix('mztRecipPhoneField', 'phone_benef', r.indicatif);

        // Activer étape 2 dans la barre de progression
        document.getElementById('mztStep2Indicator')?.classList.add('active');
        document.getElementById('mztProgressLine')?.classList.add('active');

        // Révéler le formulaire
        const form = document.getElementById('mztFormReveal');
        if (form) {
            form.classList.add('visible');
            setTimeout(() => form.scrollIntoView({ behavior: 'smooth', block: 'start' }), 80);
        }
    }

    function mztSetPhonePrefix(fieldId, inputId, indicatif) {
        const field = document.getElementById(fieldId);
        const input = document.getElementById(inputId);
        if (!field || !input) return;

        // Retirer un ancien préfixe
        const oldPrefix = field.querySelector('.mzt-phone-prefix');
        if (oldPrefix) oldPrefix.remove();

        if (!indicatif) return;

        const prefix = indicatif.startsWith('+') ? indicatif : '+' + indicatif;
        const span = document.createElement('span');
        span.className = 'mzt-phone-prefix';
        span.textContent = prefix;
        field.insertBefore(span, input);
        input.placeholder = 'N° local (sans indicatif)';
    }

    function mztFillInfoCard(opId, countryId, avatarId, state) {
        const opEl      = document.getElementById(opId);
        const countryEl = document.getElementById(countryId);
        const avatarEl  = document.getElementById(avatarId);
        if (opEl)      opEl.textContent      = state.name || '—';
        if (countryEl) countryEl.textContent  = state.country || '';
        if (avatarEl && state.image) {
            avatarEl.innerHTML = `<img src="${state.image}" style="width:100%;height:100%;object-fit:contain;"
                onerror="this.parentElement.innerHTML='<i class=\\'bi bi-phone-fill\\'></i>'">`;
        }
    }

    /* ── Retour au sélecteur ────────────────────────────────────── */
    function mztBack() {
        document.getElementById('mztFormReveal')?.classList.remove('visible');
        document.getElementById('mztStep2Indicator')?.classList.remove('active');
        document.getElementById('mztProgressLine')?.classList.remove('active');
        document.getElementById('mztStep1')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    /* ═══════════════════════════════════════════════════════════════
       API — CALCUL DES FRAIS + INITIALISATION
    ═══════════════════════════════════════════════════════════════ */
    const MZT_CALC_URL   = window._MZT.calcUrl;
    const MZT_INIT_URL   = window._MZT.initUrl;
    const MZT_STATUS_URL = window._MZT.statusUrl;
    const MZT_CSRF       = window._MZT.csrf;

    let mztCalcDebounce = null;
    let mztCalcResult   = null;
    let mztPollTimer    = null;
    let mztPendingAmount = 0;

    /* ── Calcul des frais (debounced 600ms) ──────────────────────── */
    function mztOnAmountChange(val) {
        clearTimeout(mztCalcDebounce);
        mztCalcResult = null;
        mztPendingAmount = 0;
        const amount = parseFloat(val);
        if (!amount || amount <= 0) { mztFeesHide(); return; }
        if (!mztState.sender || !mztState.recipient) return;
        mztFeesState('loading');
        mztCalcDebounce = setTimeout(() => mztCalculate(amount), 600);
    }

    async function mztCalculate(amount) {
        if (!mztState.sender?.serviceId || !mztState.recipient?.serviceId) return;
        mztFeesState('loading');
        try {
            const res  = await fetch(MZT_CALC_URL, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': MZT_CSRF },
                body:    JSON.stringify({
                    sender_service_id:    parseInt(mztState.sender.serviceId),
                    recipient_service_id: parseInt(mztState.recipient.serviceId),
                    amount,
                    international: mztDistinct,
                }),
            });
            const data = await res.json();
            if (data.status) {
                mztCalcResult    = data;
                mztPendingAmount = amount;
                mztFeesRender(data);
            } else {
                mztCalcResult = null;
                mztFeesState('error', data.message || 'Impossible de calculer les frais');
            }
        } catch {
            mztCalcResult = null;
            mztFeesState('error', 'Erreur de connexion. Réessayez.');
        }
    }

    function mztFeesHide() {
        const wrap = document.getElementById('mztFeesWrap');
        if (wrap) wrap.style.display = 'none';
    }

    function mztFeesState(state, msg = '') {
        const wrap = document.getElementById('mztFeesWrap');
        const prev = document.getElementById('mztFeesPreview');
        if (!wrap || !prev) return;
        wrap.style.display = 'block';
        if (state === 'loading') {
            prev.innerHTML = `<div class="mzt-fees-loading">
                <span class="spinner-border spinner-border-sm" role="status"></span>
                Calcul des frais en cours…
            </div>`;
        } else if (state === 'error') {
            prev.innerHTML = `<div class="mzt-fees-error">
                <i class="bi bi-exclamation-circle-fill"></i>${msg}
            </div>`;
        }
    }

    function mztFeesRender(data) {
        const wrap = document.getElementById('mztFeesWrap');
        const prev = document.getElementById('mztFeesPreview');
        if (!wrap || !prev) return;
        wrap.style.display = 'block';
        const pct      = (data.total_fee_pct ?? 0).toFixed(1);
        const fees     = Number(data.fees ?? 0).toLocaleString('fr-FR');
        const received = Number(data.amount_received ?? 0).toLocaleString('fr-FR');
        const cur      = data.currency ?? 'XOF';
        const rCur     = data.recipient_currency ?? cur;
        prev.innerHTML = `<div class="mzt-fees-card">
            <div class="mzt-fees-row">
                <span><i class="bi bi-dash-circle me-1 text-danger" style="font-size:.75rem"></i>Frais (${pct}%)</span>
                <span>${fees} ${cur}</span>
            </div>
            <hr class="mzt-fees-divider">
            <div class="mzt-fees-row mzt-fees-highlight">
                <span><i class="bi bi-check-circle me-1" style="font-size:.75rem"></i>Montant reçu</span>
                <span>${received} ${rCur}</span>
            </div>
        </div>`;
        /* update hint */
        const note = document.getElementById('mztAmountLimitsNote');
        if (note) note.style.display = 'none';
    }

    /* ── Soumission du formulaire ────────────────────────────────── */
    async function mztSubmit() {
        const s = mztState.sender;
        const r = mztState.recipient;

        // Vérification des opérateurs
        if (!s || !r) {
            mztShowError('Veuillez d\'abord sélectionner les opérateurs expéditeur et destinataire.');
            return;
        }

        // Vérification des numéros (on sanitise pour valider)
        const sPhone = mztSanitizePhone(document.getElementById('my_phone')?.value);
        const rPhone = mztSanitizePhone(document.getElementById('phone_benef')?.value);
        if (!sPhone || sPhone.length < 6) {
            mztMarkInvalid('mztSenderPhoneField');
            document.getElementById('my_phone')?.focus();
            mztShowError('Numéro expéditeur invalide. Saisissez uniquement les chiffres, sans indicatif.');
            return;
        }
        if (!rPhone || rPhone.length < 6) {
            mztMarkInvalid('mztRecipPhoneField');
            document.getElementById('phone_benef')?.focus();
            mztShowError('Numéro destinataire invalide. Saisissez uniquement les chiffres, sans indicatif.');
            return;
        }

        // Vérification OTP si requis
        const otpWrap = document.getElementById('mztOtpFieldWrap');
        const otpInput = document.getElementById('om_otp');
        if (otpWrap && !otpWrap.classList.contains('d-none') && otpInput?.required && !otpInput.value.trim()) {
            otpInput.focus();
            mztShowError('Le code OTP est obligatoire pour cet opérateur.');
            return;
        }

        // Vérification montant
        const amount = parseFloat(document.getElementById('mztAmount').value);
        if (!amount || amount <= 0) {
            document.getElementById('mztAmount')?.focus();
            mztShowError('Veuillez saisir un montant valide.');
            return;
        }

        // Calcul des frais si pas encore fait ou montant changé
        mztSetSubmitLoading(true);
        if (!mztCalcResult || mztPendingAmount !== amount) {
            await mztCalculate(amount);
            if (!mztCalcResult) {
                mztSetSubmitLoading(false);
                return; // mztCalculate affiche déjà l'erreur dans mztFeesWrap
            }
        }
        mztSetSubmitLoading(false);

        mztOpenConfirmModal(amount, mztCalcResult);
    }

    function mztShowError(msg) {
        const wrap = document.getElementById('mztFeesWrap');
        const prev = document.getElementById('mztFeesPreview');
        if (!wrap || !prev) { alert(msg); return; }
        wrap.style.display = 'block';
        prev.innerHTML = `<div class="mzt-fees-error"><i class="bi bi-exclamation-circle-fill"></i>${msg}</div>`;
        wrap.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function mztOpenConfirmModal(amount, calcData) {
        const s   = mztState.sender;
        const r   = mztState.recipient;
        const cur = calcData?.currency ?? 'XOF';
        const rCur = calcData?.recipient_currency ?? cur;
        const fees     = Number(calcData?.fees ?? 0).toLocaleString('fr-FR');
        const received = Number(calcData?.amount_received ?? 0).toLocaleString('fr-FR');
        const feePct   = (calcData?.total_fee_pct ?? 0).toFixed(1);
        const amountFmt = Number(amount).toLocaleString('fr-FR');
        const sPhone = mztSanitizePhone(document.getElementById('my_phone')?.value) || '—';
        const rPhone = mztSanitizePhone(document.getElementById('phone_benef')?.value) || '—';

        const row = (icon, lbl, val, hi = false) =>
            `<div class="mzt-confirm-row${hi ? ' mzt-confirm-highlight' : ''}">
                <i class="bi ${icon} mzt-confirm-row-icon"></i>
                <span class="mzt-confirm-row-lbl">${lbl}</span>
                <span class="mzt-confirm-row-val">${val}</span>
            </div>`;

        document.getElementById('mztConfirmBody').innerHTML = `
            ${row('bi-telephone-outbound', 'Expéditeur', `${s.name} — ${s.country}`)}
            ${row('bi-phone', 'N° expéditeur', sPhone)}
            <hr style="margin:8px 0;border-color:rgba(15,23,42,.07)">
            ${row('bi-telephone-inbound', 'Destinataire', `${r.name} — ${r.country}`)}
            ${row('bi-phone-fill', 'N° destinataire', rPhone)}
            <hr style="margin:8px 0;border-color:rgba(15,23,42,.07)">
            ${row('bi-cash', 'Montant', `${amountFmt} ${cur}`)}
            ${row('bi-percent', `Frais (${feePct}%)`, `${fees} ${cur}`)}
            ${row('bi-wallet-fill', 'Montant reçu', `${received} ${rCur}`, true)}
        `;

        bootstrap.Modal.getOrCreateInstance(document.getElementById('mztConfirmModal')).show();
    }

    async function mztDoInitialize() {
        bootstrap.Modal.getOrCreateInstance(document.getElementById('mztConfirmModal')).hide();
        mztSetSubmitLoading(true);

        const s = mztState.sender;
        const r = mztState.recipient;
        const body = {
            sender_service_id:    parseInt(s.serviceId),
            sender_phone:         mztSanitizePhone(document.getElementById('my_phone')?.value),
            recipient_service_id: parseInt(r.serviceId),
            recipient_phone:      mztSanitizePhone(document.getElementById('phone_benef')?.value),
            amount:               parseFloat(document.getElementById('mztAmount').value),
            international:        mztDistinct,
        };
        const otp = document.getElementById('om_otp')?.value?.trim();
        if (otp) body.om_otp = otp;

        try {
            const res  = await fetch(MZT_INIT_URL, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': MZT_CSRF },
                body:    JSON.stringify(body),
            });
            const data = await res.json();
            mztSetSubmitLoading(false);
            if (data.status) {
                mztOpenResultModal(data, body.amount, mztCalcResult);
            } else {
                mztOpenResultModal({ status: false, message: data.message || 'Erreur lors du transfert' }, body.amount, mztCalcResult);
            }
        } catch (err) {
            mztSetSubmitLoading(false);
            mztOpenResultModal({ status: false, message: 'Erreur de connexion. Veuillez réessayer.' }, body.amount, mztCalcResult);
        }
    }

    function mztSetSubmitLoading(loading) {
        const btn  = document.getElementById('mztSubmitBtn');
        const text = btn?.querySelector('.submit-text');
        const spin = btn?.querySelector('.loading-spinner');
        if (!btn) return;
        btn.disabled  = loading;
        text?.classList.toggle('d-none', loading);
        spin?.classList.toggle('d-none', !loading);
        if (!loading) btn.disabled = false; // toujours remettre actif après
    }

    /* ── Résultat + polling ──────────────────────────────────────── */
    function mztOpenResultModal(data, amount, calcData) {
        const modal    = document.getElementById('mztResultModal');
        const titleEl  = document.getElementById('mztResultModalLabel');
        const bodyEl   = document.getElementById('mztResultBody');
        const cur      = calcData?.currency ?? 'XOF';
        const rCur     = calcData?.recipient_currency ?? cur;
        const fees     = Number(calcData?.fees ?? data.fees ?? 0).toLocaleString('fr-FR');
        const received = Number(calcData?.amount_received ?? data.amount_received ?? 0).toLocaleString('fr-FR');

        if (data.status) {
            titleEl.innerHTML = `<span style="color:#16a34a"><i class="bi bi-check-circle-fill me-2"></i>Transfert initié</span>`;

            // ── Bloc d'action selon le type d'opérateur expéditeur ──
            const smsLink    = (data.sms_link || '').trim();
            const senderName = (mztState.sender?.name || '').toLowerCase();
            let actionBlock  = '';

            if (smsLink) {
                // Lien de paiement Wave / Orange → bouton cliquable
                const isWave   = senderName.includes('wave');
                const isOrange = senderName.includes('orange');
                const btnColor = isWave ? '#1a56db' : (isOrange ? '#ea580c' : '#7c3aed');
                const btnIcon  = isWave ? 'bi-app-indicator' : (isOrange ? 'bi-telephone-fill' : 'bi-link-45deg');
                const btnLabel = isWave ? 'Ouvrir l\'application Wave' : (isOrange ? 'Valider via Orange Money' : 'Confirmer le paiement');
                actionBlock = `
                <div style="padding:12px 14px;border-radius:12px;background:#fffbeb;border:1.5px solid #fde68a;margin-bottom:14px">
                    <div style="font-size:.78rem;font-weight:800;color:#92400e;letter-spacing:.03em;margin-bottom:8px">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>ACTION REQUISE — VALIDATION NÉCESSAIRE
                    </div>
                    <a href="${smsLink}" target="_blank" rel="noopener noreferrer"
                       style="display:flex;align-items:center;justify-content:center;gap:10px;
                              background:${btnColor};color:#fff;border-radius:10px;padding:11px 16px;
                              font-size:.9rem;font-weight:700;text-decoration:none;margin-bottom:8px"
                       onclick="this.style.opacity='.7'">
                        <i class="bi ${btnIcon}"></i> ${btnLabel}
                    </a>
                    <div style="font-size:.76rem;color:#92400e;text-align:center">
                        <i class="bi bi-info-circle me-1"></i>Une fois dans l'application, confirmez le paiement en attente puis revenez ici.
                    </div>
                </div>`;
            } else {
                // Pas de lien → banner USSD comme avant la soumission (MTN, Moov…)
                const confirmInfo = mztGetConfirmInfo(mztState.sender?.name || '', mztState.sender?.countryIso2 || '');
                if (confirmInfo) {
                    actionBlock = `<div style="margin-bottom:14px">${mztRenderUssdBanner(confirmInfo)}</div>`;
                }
            }

            // ── Bloc frais + polling ──
            bodyEl.innerHTML = `
                ${actionBlock}
                <div style="padding:12px 14px;border-radius:12px;background:#f0fdf4;border:1px solid #bbf7d0;margin-bottom:14px">
                    <div style="display:flex;justify-content:space-between;font-size:.85rem;color:#15803d;margin-bottom:4px">
                        <span>Frais déduits</span><strong>${fees} ${cur}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:.95rem;font-weight:800;color:#16a34a">
                        <span>Montant reçu</span><strong>${received} ${rCur}</strong>
                    </div>
                </div>
                <div class="mzt-poll-chip mzt-poll-pending" id="mztPollChip">
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    Vérification du statut…
                </div>`;
            if (data.transaction_id) mztStartPoll(data.transaction_id);
        } else {
            titleEl.innerHTML = `<span style="color:#dc2626"><i class="bi bi-x-circle-fill me-2"></i>Transfert échoué</span>`;

            const errMsg = data.message || 'Erreur inconnue';
            // Extraire le format suggéré par PixelInnov (ex: "Suggested regex for ORANGE - BFA is /…/")
            const regexHintMatch = errMsg.match(/Suggested regex for ([^i]+) is\s*\/([^/]+)\//i);
            const isPhoneError   = /phonenumber|phone number|not a valid address/i.test(errMsg);
            const txnRef         = data.transaction_id ? `Réf. transaction : <strong>#${data.transaction_id}</strong>` : '';
            const refundNote     = /remboursement/i.test(errMsg)
                ? `<div style="margin-top:10px;padding:10px 12px;border-radius:10px;background:#fefce8;border:1px solid #fde68a;color:#92400e;font-size:.8rem">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Un remboursement vers votre numéro expéditeur a été initié.
                   </div>` : '';

            let phoneHint = '';
            if (isPhoneError && regexHintMatch) {
                const opName = regexHintMatch[1].trim();
                phoneHint = `<div style="margin-top:10px;padding:10px 12px;border-radius:10px;background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;font-size:.8rem">
                    <i class="bi bi-info-circle me-1"></i><strong>Format attendu pour ${opName} :</strong>
                    numéro local (sans indicatif), correspondant au format de l'opérateur.
                    Vérifiez le numéro du destinataire et réessayez.
                </div>`;
            } else if (isPhoneError) {
                phoneHint = `<div style="margin-top:10px;padding:10px 12px;border-radius:10px;background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;font-size:.8rem">
                    <i class="bi bi-telephone me-1"></i>Vérifiez que le numéro du destinataire est correct et sans indicatif pays.
                </div>`;
            }

            // Nettoyer le message affiché (retirer la partie technique "Suggested regex…")
            const cleanMsg = errMsg.replace(/\s*Suggested regex for[^$]+/i, '').trim();

            bodyEl.innerHTML = `
                <div style="padding:14px;border-radius:12px;background:#fef2f2;border:1px solid #fca5a5;color:#dc2626;font-size:.88rem;font-weight:600">
                    <i class="bi bi-exclamation-circle me-2"></i>${cleanMsg}
                </div>
                ${phoneHint}
                ${refundNote}
                ${txnRef ? `<div style="margin-top:10px;font-size:.78rem;color:#64748b;text-align:right">${txnRef}</div>` : ''}`;
        }

        bootstrap.Modal.getOrCreateInstance(modal).show();
    }

    function mztStartPoll(transferId) {
        clearInterval(mztPollTimer);
        let attempts = 0;
        mztPollTimer = setInterval(async () => {
            attempts++;
            try {
                const res  = await fetch(MZT_STATUS_URL, {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': MZT_CSRF },
                    body:    JSON.stringify({ transfer_id: transferId }),
                });
                const data = await res.json();
                const st   = (data.local_status ?? data.status_label ?? '').toUpperCase();

                if (st === 'SUCCESS' || st === 'COMPLETED') {
                    clearInterval(mztPollTimer);
                    mztPollSetSuccess();
                } else if (st === 'FAILED' || st === 'CANCELLED') {
                    clearInterval(mztPollTimer);
                    const reason = data.failure_reason || data.message || 'La transaction a été rejetée par l\'opérateur.';
                    mztPollSetFailed(reason, transferId);
                } else if (attempts >= 10) {
                    clearInterval(mztPollTimer);
                    mztPollSetTimeout();
                }
            } catch {
                if (attempts >= 10) { clearInterval(mztPollTimer); mztPollSetTimeout(); }
            }
        }, 15000);
    }

    function mztPollSetSuccess() {
        const titleEl = document.getElementById('mztResultModalLabel');
        const chip    = document.getElementById('mztPollChip');
        if (titleEl) titleEl.innerHTML = `<span style="color:#16a34a"><i class="bi bi-check-circle-fill me-2"></i>Transfert confirmé !</span>`;
        if (chip)    { chip.className = 'mzt-poll-chip mzt-poll-success'; chip.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Paiement confirmé par l\'opérateur'; }
    }

    function mztPollSetFailed(reason, transferId) {
        const titleEl = document.getElementById('mztResultModalLabel');
        const bodyEl  = document.getElementById('mztResultBody');
        if (titleEl) titleEl.innerHTML = `<span style="color:#dc2626"><i class="bi bi-x-circle-fill me-2"></i>Transaction échouée</span>`;
        if (bodyEl) {
            const txnRef = transferId ? `<div style="margin-top:10px;font-size:.78rem;color:#64748b;text-align:right">Réf. : <strong>#${transferId}</strong></div>` : '';
            bodyEl.innerHTML = `
                <div style="padding:14px;border-radius:12px;background:#fef2f2;border:1.5px solid #fca5a5;margin-bottom:10px">
                    <div style="font-size:.78rem;font-weight:800;color:#991b1b;letter-spacing:.03em;margin-bottom:6px">
                        <i class="bi bi-x-octagon-fill me-1"></i>LA TRANSACTION A ÉCHOUÉ
                    </div>
                    <div style="font-size:.88rem;color:#dc2626;font-weight:600;line-height:1.5">${reason}</div>
                </div>
                <div style="padding:10px 12px;border-radius:10px;background:#fefce8;border:1px solid #fde68a;color:#92400e;font-size:.8rem">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>
                    Si votre compte a été débité, un remboursement automatique est déclenché. Contactez le support si ce n'est pas le cas.
                </div>
                ${txnRef}`;
        }
    }

    function mztPollSetTimeout() {
        const chip = document.getElementById('mztPollChip');
        if (chip) {
            chip.className = 'mzt-poll-chip mzt-poll-pending';
            chip.style.opacity = '.7';
            chip.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Statut en attente — vérifiez dans votre historique';
        }
    }

    function mztAfterResult() {
        clearInterval(mztPollTimer);
    }
