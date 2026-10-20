const acvState = { robusId: '', robusName: '', provider: null, calcData: null };
let acvVerifyTimer = null;
let acvVerified    = false;
let acvPollTimer   = null;
let acvCalcTimer   = null;

/* ── Utilitaires ─────────────────────────────────── */
function acvSanitizePhone(raw) { return (raw || '').replace(/\D/g, ''); }
function acvMarkInvalid(id)    { document.getElementById(id)?.classList.add('is-invalid'); }
function acvClearInvalid(id)   { document.getElementById(id)?.classList.remove('is-invalid'); }

function acvShowError(msg) {
    const box = document.getElementById('acvErrorBox');
    if (!box) return;
    box.innerHTML = `<i class="bi bi-exclamation-circle-fill"></i> ${msg}`;
    box.style.display = 'flex';
    box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
function acvHideError() {
    const box = document.getElementById('acvErrorBox');
    if (box) box.style.display = 'none';
}

function acvSetSubmitLoading(loading) {
    const btn  = document.getElementById('acvSubmitBtn');
    const text = btn?.querySelector('.acv-submit-text');
    const spin = btn?.querySelector('.acv-loading-spinner');
    if (!btn) return;
    btn.disabled = loading;
    text?.classList.toggle('d-none', loading);
    spin?.classList.toggle('d-none', !loading);
}

/* ── Table USSD / App par opérateur ─────────────── */
const acvConfirmTable = {
    'orange_ci': { type:'ussdOtp',   ussdCode:'#144*82#',              shortLabel:'Code OTP Orange CI',    instruction:'Composez #144*82# sur votre téléphone Orange pour générer votre code OTP, puis validez.' },
    'mtn_ci':    { type:'ussdDebit', ussdCode:'*133#',                 shortLabel:'USSD MTN CI',           instruction:'Après réception du SMS de débit MTN, composez *133# pour confirmer.' },
    'moov_ci':   { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Moov CI',      instruction:'Vous recevrez un SMS push Moov Money. Saisissez votre code secret pour valider.' },
    'wave_ci':   { type:'smsLink',   ussdCode:null,                    shortLabel:'App Wave CI',           instruction:"Ouvrez l'application Wave et confirmez le paiement en attente." },
    'orange_sn': { type:'ussdOtp',   ussdCode:'#144#391#',             shortLabel:'Code OTP Orange SN',    instruction:'Composez #144#391# sur votre téléphone Orange Sénégal pour générer votre code OTP.' },
    'free_sn':   { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Free SN',      instruction:'Vous recevrez un SMS push. Saisissez votre code secret Free Money pour valider.' },
    'wave_sn':   { type:'smsLink',   ussdCode:null,                    shortLabel:'App Wave SN',           instruction:"Ouvrez l'application Wave et confirmez le paiement en attente." },
    'orange_ml': { type:'ussdOtp',   ussdCode:'#144#77#',              shortLabel:'Code OTP Orange ML',    instruction:'Composez #144#77# sur votre téléphone Orange Mali pour générer votre code OTP.' },
    'moov_ml':   { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Moov ML',      instruction:'Vous recevrez un SMS push Moov Money. Saisissez votre code secret pour valider.' },
    'tmoney_tg': { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push T-Money TG',   instruction:'Vous recevrez un SMS push T-Money. Saisissez votre code secret pour valider.' },
    'flooz_tg':  { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Flooz TG',     instruction:'Vous recevrez un SMS push Flooz. Saisissez votre code secret pour valider.' },
    'moov_bj':   { type:'ussdDebit', ussdCode:'*855*7*8#',             shortLabel:'USSD Moov BJ',          instruction:'Composez *855*7*8# après réception du SMS pour confirmer le paiement Moov BJ.' },
    'mtn_bj':    { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push MTN BJ',       instruction:'Vous recevrez un SMS push MTN MoMo. Saisissez votre code secret pour valider.' },
    'orange_bf': { type:'ussdOtp',   ussdCode:'*144*4*6*Montant*PIN#', shortLabel:'Code OTP Orange BF',    instruction:'Composez *144*4*6*[Montant]*[PIN]# sur votre téléphone Orange Burkina pour valider.' },
    'moov_bf':   { type:'ussdDebit', ussdCode:'*555*6#',               shortLabel:'USSD Moov BF',          instruction:'Composez *555*6# après réception du SMS pour confirmer le paiement Moov BF.' },
    'orange_cm': { type:'ussdDebit', ussdCode:'#150*50#',              shortLabel:'USSD Orange CM',        instruction:'Composez #150*50# après réception du SMS pour confirmer.' },
    'mtn_cm':    { type:'ussdDebit', ussdCode:'*126#',                 shortLabel:'USSD MTN CM',           instruction:'Composez *126# après réception du SMS pour confirmer le paiement MTN CM.' },
    'orange_cd': { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Orange CD',    instruction:'Vous recevrez un SMS push Orange Money Congo.' },
    'orange_cg': { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Orange Congo', instruction:'Vous recevrez un SMS push Orange Money Congo.' },
    'mpesa_cd':  { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push M-Pesa CD',    instruction:'Vous recevrez un SMS push M-Pesa. Saisissez votre code secret.' },
    'airtel_cd': { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push Airtel CD',    instruction:'Vous recevrez un SMS push Airtel Money. Saisissez votre code secret.' },
    'orange_gn': { type:'ussdOtp',   ussdCode:'#144#',                 shortLabel:'Code OTP Orange GN',    instruction:"Composez #144# → option 4 puis option 2 pour générer votre code OTP Orange Guinée." },
    'mtn_gn':    { type:'smsPush',   ussdCode:null,                    shortLabel:'SMS push MTN GN',       instruction:'Vous recevrez un SMS push MTN MoMo Guinée.' },
};

function acvGetConfirmInfo(name, iso2) {
    if (!name || !iso2) return null;
    const iso       = iso2.toLowerCase().trim();
    const norm      = name.toLowerCase().trim();
    const noHyphen  = norm.replace(/-/g, '');
    const firstWord = norm.split(/\s+/)[0];
    for (const key of [`${norm}_${iso}`, `${firstWord}_${iso}`, `${noHyphen}_${iso}`, `${norm.replace(/[^a-z0-9]/g,'')}_${iso}`]) {
        if (acvConfirmTable[key]) return acvConfirmTable[key];
    }
    return null;
}

function acvRenderUssdBanner(info) {
    if (!info) return '';
    const styles = {
        ussdOtp:   { bg:'#fff7ed', border:'#f97316', text:'#c2410c', icon:'bi-dialpad-fill' },
        ussdDebit: { bg:'#eff6ff', border:'#3b82f6', text:'#1d4ed8', icon:'bi-telephone-outbound-fill' },
        smsPush:   { bg:'#f0fdfa', border:'#14b8a6', text:'#0f766e', icon:'bi-chat-dots-fill' },
        smsLink:   { bg:'#eef2ff', border:'#818cf8', text:'#4338ca', icon:'bi-app-indicator' },
        app:       { bg:'#eef2ff', border:'#818cf8', text:'#4338ca', icon:'bi-phone-fill' },
    };
    const s = styles[info.type] || styles.smsPush;
    const ussdHtml = info.ussdCode
        ? `<div style="margin:8px 0 4px"><code style="display:inline-block;padding:5px 18px;border-radius:8px;
               background:${s.bg};border:2px solid ${s.border};color:${s.text};
               font-size:1.1rem;font-weight:900;letter-spacing:2.5px">${info.ussdCode}</code></div>` : '';
    return `<div style="padding:14px 16px;border-radius:14px;background:${s.bg};border:1.5px solid ${s.border}">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
            <i class="bi ${s.icon}" style="color:${s.text};flex:0 0 auto"></i>
            <strong style="font-size:.83rem;font-weight:800;color:${s.text}">${info.shortLabel}</strong>
        </div>
        ${ussdHtml}
        <p style="margin:6px 0 0;font-size:.82rem;color:${s.text};opacity:.88;line-height:1.5">${info.instruction}</p>
    </div>`;
}

/* ── ÉTAPE 1 : Vérification ID ROBUS ─────────────── */
function acvOnIdInput(val) {
    acvVerified = false;
    acvState.robusId = acvState.robusName = '';
    document.getElementById('acvNameCard').style.display = 'none';
    document.getElementById('acvStep1Btn').disabled = true;
    acvSetVerifyIcon('');
    clearTimeout(acvVerifyTimer);
    if (!val || val.trim().length < 3) return;
    acvSetVerifyIcon('loading');
    acvVerifyTimer = setTimeout(() => acvVerifyRobus(val.trim()), 600);
    // Synchro champ readonly de confirmation
    const display = document.getElementById('acvRobusDisplay');
    if (display) display.value = val;
}

async function acvVerifyRobus(id) {
    try {
        const res  = await fetch(window._ACV.searchUrl + '?num_robus=' + encodeURIComponent(id));
        const data = await res.json();
        const card = document.getElementById('acvNameCard');
        const val  = document.getElementById('acvRobusNameVal');

        if (data.user_firstname) {
            const fullName = `${data.user_firstname} ${data.user_lastname || ''}`.trim();
            acvState.robusId   = id;
            acvState.robusName = fullName;
            acvVerified = true;
            if (val)  { val.textContent = fullName; val.classList.remove('acv-name-err'); }
            if (card) { card.style.display = 'flex'; card.querySelector('i').className = 'bi bi-person-check-fill'; }
            document.getElementById('acvStep1Btn').disabled = false;
            acvSetVerifyIcon('ok');
        } else {
            acvVerified = false;
            if (val)  { val.textContent = 'Aucun compte trouvé pour cet ID'; val.classList.add('acv-name-err'); }
            if (card) { card.style.display = 'flex'; card.querySelector('i').className = 'bi bi-person-x-fill'; }
            document.getElementById('acvStep1Btn').disabled = true;
            acvSetVerifyIcon('err');
        }
    } catch { acvSetVerifyIcon('err'); }
}

function acvSetVerifyIcon(state) {
    const el = document.getElementById('acvVerifyIcon');
    if (!el) return;
    el.className = 'acv-verify-icon';
    if (state === 'loading') { el.className += ' loading'; el.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }
    else if (state === 'ok')  { el.className += ' ok';  el.innerHTML = '<i class="bi bi-check-circle-fill"></i>'; }
    else if (state === 'err') { el.className += ' err'; el.innerHTML = '<i class="bi bi-x-circle-fill"></i>'; }
    else el.innerHTML = '';
}

/* ── Étape 1 → Étape 2 ───────────────────────────── */
function acvContinueToPayment() {
    if (!acvVerified) return;
    document.getElementById('acvSection1').classList.add('d-none');
    document.getElementById('acvSection2').classList.remove('d-none');
    document.getElementById('acvStep2Ind')?.classList.add('active');
    document.getElementById('acvProgressLine1')?.classList.add('active');
    document.getElementById('acvSection2')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/* ── Sélection pays ───────────────────────────────── */
function acvPickCountry(card) {
    card.closest('.acv-country-grid').querySelectorAll('.acv-ctry-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    document.getElementById('acvBackFlag').textContent  = card.dataset.countryFlag  || '';
    document.getElementById('acvBackLabel').textContent = card.dataset.countryName  || '';
    document.getElementById('acvCountryStep').classList.add('d-none');
    document.getElementById('acvOpStep').classList.remove('d-none');
    document.querySelectorAll('.acv-op-group').forEach(g => g.classList.add('d-none'));
    document.querySelector(`.acv-op-group[data-country-id="${card.dataset.countryId}"]`)?.classList.remove('d-none');
    acvState.provider = null;
    document.getElementById('acvOpBar').classList.add('d-none');
    document.getElementById('acvSection2Btn').disabled = true;
}

function acvBackToCountry() {
    document.getElementById('acvOpStep').classList.add('d-none');
    document.getElementById('acvCountryStep').classList.remove('d-none');
    acvState.provider = null;
    document.getElementById('acvOpBar').classList.add('d-none');
    document.getElementById('acvSection2Btn').disabled = true;
}

/* ── Sélection opérateur ─────────────────────────── */
function acvSelectOp(card) {
    document.querySelectorAll('.acv-op-card').forEach(c => c.classList.remove('selected'));
    card.classList.add('selected');
    acvState.provider = {
        name:        card.dataset.opName           || '—',
        image:       card.dataset.opImage          || '',
        serviceId:   card.dataset.serviceId        || '',
        country:     card.dataset.country          || '',
        countryIso2: card.dataset.countryIso2      || '',
        indicatif:   card.dataset.countryIndicatif || '',
    };
    acvState.calcData = null;
    // Relance le calcul si un montant est déjà saisi
    const amountVal = document.getElementById('acvAmount')?.value;
    if (amountVal) acvOnAmountChange(amountVal);
    const bar = document.getElementById('acvOpBar');
    document.getElementById('acvOpBarName').textContent    = acvState.provider.name;
    document.getElementById('acvOpBarCountry').textContent = acvState.provider.country;
    const avatar = document.getElementById('acvOpBarAvatar');
    if (avatar && acvState.provider.image) {
        avatar.innerHTML = `<img src="${acvState.provider.image}" style="width:100%;height:100%;object-fit:contain"
            onerror="this.parentElement.innerHTML='<i class=\\'bi bi-phone-fill\\'></i>'">`;
    }
    bar.classList.remove('d-none');
    document.getElementById('acvSection2Btn').disabled = false;
}

/* ── Étape 2 → Étape 3 ───────────────────────────── */
function acvProceed() {
    if (!acvState.provider) return;
    const p = acvState.provider;

    // Carte opérateur en lecture seule
    document.getElementById('acvFormOpName').textContent    = p.name;
    document.getElementById('acvFormOpCountry').textContent = p.country;
    const avatar = document.getElementById('acvFormOpAvatar');
    if (avatar && p.image) {
        avatar.innerHTML = `<img src="${p.image}" style="width:100%;height:100%;object-fit:contain"
            onerror="this.parentElement.innerHTML='<i class=\\'bi bi-phone-fill\\'></i>'">`;
    }

    // Bandeau USSD + OTP
    const confirmInfo  = acvGetConfirmInfo(p.name, p.countryIso2);
    const bannerWrap   = document.getElementById('acvUssdBanner');
    const otpWrap      = document.getElementById('acvOtpFieldWrap');
    const otpInput     = document.getElementById('acvOtp');
    if (bannerWrap) {
        bannerWrap.innerHTML = acvRenderUssdBanner(confirmInfo);
        bannerWrap.style.display = confirmInfo ? 'block' : 'none';
    }
    const requiresOtp = confirmInfo?.type === 'ussdOtp';
    if (otpWrap)  otpWrap.classList.toggle('d-none', !requiresOtp);
    if (otpInput) otpInput.required = requiresOtp;

    // Préfixe pays sur le champ téléphone
    acvSetPhonePrefix(p.indicatif);

    // Progression
    document.getElementById('acvStep3Ind')?.classList.add('active');
    document.getElementById('acvProgressLine2')?.classList.add('active');

    // Révéler section 3
    const form = document.getElementById('acvFormReveal');
    if (form) {
        form.classList.add('visible');
        setTimeout(() => form.scrollIntoView({ behavior: 'smooth', block: 'start' }), 80);
    }
}

function acvSetPhonePrefix(indicatif) {
    const field = document.getElementById('acvPhoneField');
    const input = document.getElementById('acvPhone');
    if (!field || !input) return;
    field.querySelector('.acv-phone-prefix')?.remove();
    if (!indicatif) return;
    const prefix = indicatif.startsWith('+') ? indicatif : '+' + indicatif;
    const span = document.createElement('span');
    span.className   = 'acv-phone-prefix';
    span.textContent = prefix;
    field.insertBefore(span, input);
    input.placeholder = 'N° local (sans indicatif)';
}

/* ── Retour ──────────────────────────────────────── */
function acvBackToStep1() {
    document.getElementById('acvSection1').classList.remove('d-none');
    document.getElementById('acvSection2').classList.add('d-none');
    document.getElementById('acvFormReveal')?.classList.remove('visible');
    ['acvStep2Ind','acvStep3Ind'].forEach(id => document.getElementById(id)?.classList.remove('active'));
    ['acvProgressLine1','acvProgressLine2'].forEach(id => document.getElementById(id)?.classList.remove('active'));
    document.getElementById('acvSection1')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
function acvBackToStep2() {
    document.getElementById('acvFormReveal')?.classList.remove('visible');
    document.getElementById('acvStep3Ind')?.classList.remove('active');
    document.getElementById('acvProgressLine2')?.classList.remove('active');
    document.getElementById('acvSection2')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/* ── Calcul dynamique des frais (serveur) ────────── */
function acvOnAmountChange(val) {
    acvState.calcData = null;
    const amount = parseFloat(val);
    const wrap   = document.getElementById('acvFeesWrap');
    const note   = document.getElementById('acvFeesNote');

    if (!amount || amount <= 0) {
        if (wrap) wrap.style.display = 'none';
        if (note) note.style.display = 'flex';
        clearTimeout(acvCalcTimer);
        return;
    }

    if (!acvState.provider?.serviceId) {
        if (wrap) wrap.style.display = 'none';
        if (note) note.style.display = 'flex';
        return;
    }

    // Affiche un état de chargement immédiatement
    const prev = document.getElementById('acvFeesPreview');
    if (prev) {
        prev.innerHTML = `<div class="acv-fees-card" style="justify-content:center;gap:8px;color:#7c3aed;font-size:.83rem;font-weight:600">
            <span class="spinner-border spinner-border-sm" style="width:.85rem;height:.85rem;border-width:2px"></span>
            Calcul des frais…
        </div>`;
    }
    if (wrap) wrap.style.display = 'block';
    if (note) note.style.display = 'none';

    clearTimeout(acvCalcTimer);
    acvCalcTimer = setTimeout(() => acvFetchCalc(amount), 500);
}

async function acvFetchCalc(amount) {
    const prev = document.getElementById('acvFeesPreview');
    const wrap = document.getElementById('acvFeesWrap');
    const note = document.getElementById('acvFeesNote');

    try {
        const res  = await fetch(window._ACV.calcUrl, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window._ACV.csrf },
            body:    JSON.stringify({
                pixel_service_id: parseInt(acvState.provider.serviceId),
                amount,
            }),
        });
        const data = await res.json();

        if (!data.status) {
            const msg = data.message || 'Montant hors limites.';
            const min = data.amount_min ? ` Min : <strong>${Number(data.amount_min).toLocaleString('fr-FR')}</strong>` : '';
            const max = data.amount_max ? ` / Max : <strong>${Number(data.amount_max).toLocaleString('fr-FR')}</strong>` : '';
            const cur = data.currency || 'FCFA';
            if (prev) {
                prev.innerHTML = `<div style="display:flex;align-items:center;gap:8px;padding:10px 12px;
                    border-radius:10px;background:#fef2f2;border:1px solid #fca5a5;color:#dc2626;font-size:.83rem;font-weight:600">
                    <i class="bi bi-exclamation-circle-fill" style="flex:0 0 auto"></i>
                    <span>${msg}${min}${max} ${cur}</span>
                </div>`;
            }
            if (wrap) wrap.style.display = 'block';
            if (note) note.style.display = 'none';
            return;
        }

        acvState.calcData = data;
        const fees    = Number(data.fees).toLocaleString('fr-FR');
        const recvd   = Number(data.amount_received).toLocaleString('fr-FR');
        const pctFmt  = Number(data.total_fee_pct).toFixed(2).replace(/\.?0+$/, '');
        const cur     = data.currency || 'FCFA';

        if (prev) {
            prev.innerHTML = `
                <div class="acv-fees-card">
                    <div class="acv-fees-row">
                        <span><i class="bi bi-dash-circle me-1 text-danger" style="font-size:.75rem"></i>Frais (${pctFmt}%)</span>
                        <span>${fees} ${cur}</span>
                    </div>
                    <hr class="acv-fees-divider">
                    <div class="acv-fees-row acv-fees-highlight">
                        <span><i class="bi bi-check-circle me-1" style="font-size:.75rem"></i>Crédité sur votre compte ROBUS</span>
                        <span>${recvd} ${cur}</span>
                    </div>
                </div>`;
        }
        if (wrap) wrap.style.display = 'block';
        if (note) note.style.display = 'none';

    } catch {
        if (prev) {
            prev.innerHTML = `<div style="display:flex;align-items:center;gap:8px;padding:10px 12px;
                border-radius:10px;background:#fef2f2;border:1px solid #fca5a5;color:#dc2626;font-size:.83rem">
                <i class="bi bi-wifi-off"></i> Impossible de calculer les frais. Vérifiez votre connexion.
            </div>`;
        }
    }
}

/* ── Soumission → modal de confirmation ──────────── */
function acvSubmit() {
    acvHideError();
    if (!acvVerified || !acvState.robusId) { acvShowError('Veuillez d\'abord vérifier votre ID ROBUS.'); return; }
    if (!acvState.provider) { acvShowError('Veuillez sélectionner un opérateur de paiement.'); return; }

    const phone = acvSanitizePhone(document.getElementById('acvPhone')?.value);
    if (!phone || phone.length < 6) {
        acvMarkInvalid('acvPhoneField');
        document.getElementById('acvPhone')?.focus();
        acvShowError('Numéro de téléphone invalide. Saisissez uniquement les chiffres, sans indicatif.');
        return;
    }
    const otpWrap = document.getElementById('acvOtpFieldWrap');
    const otpInput = document.getElementById('acvOtp');
    if (otpWrap && !otpWrap.classList.contains('d-none') && otpInput?.required && !otpInput.value.trim()) {
        otpInput.focus();
        acvShowError('Le code OTP est obligatoire pour cet opérateur.');
        return;
    }
    const amount = parseFloat(document.getElementById('acvAmount')?.value);
    if (!amount || amount <= 0) {
        document.getElementById('acvAmount')?.focus();
        acvShowError('Veuillez saisir un montant valide.');
        return;
    }

    acvOpenConfirmModal(amount, phone);
}

function acvOpenConfirmModal(amount, phone) {
    const p    = acvState.provider;
    const calc = acvState.calcData;
    const fees  = calc ? Number(calc.fees)            : 0;
    const recvd = calc ? Number(calc.amount_received) : amount;
    const pctFmt = calc ? Number(calc.total_fee_pct).toFixed(2).replace(/\.?0+$/, '') : '0';
    const cur    = calc?.currency || 'FCFA';
    const amtFmt = amount.toLocaleString('fr-FR');

    const row = (icon, lbl, val, hi = false) =>
        `<div class="acv-confirm-row${hi ? ' acv-confirm-highlight' : ''}">
            <i class="bi ${icon} acv-confirm-icon"></i>
            <span class="acv-confirm-lbl">${lbl}</span>
            <span class="acv-confirm-val">${val}</span>
        </div>`;

    document.getElementById('acvConfirmBody').innerHTML =
        row('bi-phone',             'Opérateur',       `${p.name} — ${p.country}`) +
        row('bi-telephone-outbound','Numéro',           phone) +
        row('bi-person-badge',      'Compte ROBUS',     acvState.robusId + (acvState.robusName ? ` (${acvState.robusName})` : '')) +
        '<hr style="margin:8px 0;border-color:rgba(15,23,42,.07)">' +
        row('bi-cash',              'Montant envoyé',    `${amtFmt} ${cur}`) +
        row('bi-percent',           `Frais (${pctFmt}%)`, `${fees.toLocaleString('fr-FR')} ${cur}`) +
        row('bi-wallet2',           'Crédité ROBUS',     `${recvd.toLocaleString('fr-FR')} ${cur}`, true);

    bootstrap.Modal.getOrCreateInstance(document.getElementById('acvConfirmModal')).show();
}

/* ── Initialisation PixelInnov ───────────────────── */
async function acvDoInitialize() {
    bootstrap.Modal.getOrCreateInstance(document.getElementById('acvConfirmModal')).hide();
    acvSetSubmitLoading(true);

    const phone  = acvSanitizePhone(document.getElementById('acvPhone')?.value);
    const amount = parseFloat(document.getElementById('acvAmount')?.value);
    const otp    = document.getElementById('acvOtp')?.value?.trim();

    const body = {
        robus_id:         acvState.robusId,
        pixel_service_id: parseInt(acvState.provider.serviceId),
        amount,
        destination:      phone,
    };
    if (otp) body.om_otp = otp;

    try {
        const res  = await fetch(window._ACV.initUrl, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window._ACV.csrf },
            body:    JSON.stringify(body),
        });
        const data = await res.json();
        acvSetSubmitLoading(false);
        acvOpenResultModal(data, amount);
    } catch {
        acvSetSubmitLoading(false);
        acvOpenResultModal({ status: false, message: 'Erreur de connexion. Veuillez réessayer.' }, 0);
    }
}

/* ── Modal résultat ──────────────────────────────── */
/* ── Helpers statut modal ────────────────────────── */
function acvStatusHtml(type, msg, sub) {
    const cfg = {
        pending: { bg:'#eff6ff', border:'#bfdbfe', color:'#1d4ed8', icon:'bi-hourglass-split',      label:'En cours de traitement' },
        success: { bg:'#f0fdf4', border:'#86efac', color:'#16a34a', icon:'bi-check-circle-fill',     label:'Transaction confirmée'  },
        failed:  { bg:'#fef2f2', border:'#fca5a5', color:'#dc2626', icon:'bi-x-octagon-fill',        label:'Transaction échouée'    },
        timeout: { bg:'#fafaf9', border:'#d4d4aa', color:'#78716c', icon:'bi-hourglass-bottom',      label:'Statut en attente'      },
    };
    const c = cfg[type] || cfg.pending;
    const spinner = type === 'pending'
        ? `<span class="spinner-border spinner-border-sm me-2" style="width:.9rem;height:.9rem;border-width:2px"></span>` : '';
    return `
        <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-radius:14px;
                    background:${c.bg};border:1.5px solid ${c.border};margin-bottom:14px">
            <div style="flex:0 0 auto;width:36px;height:36px;border-radius:50%;background:${c.color};
                        display:flex;align-items:center;justify-content:center">
                ${spinner}<i class="bi ${c.icon}" style="color:#fff;font-size:1rem${type==='pending'?';display:none':''}"></i>
            </div>
            <div style="flex:1;min-width:0">
                <div style="font-size:.9rem;font-weight:800;color:${c.color};line-height:1.3">${msg || c.label}</div>
                ${sub ? `<div style="font-size:.8rem;color:${c.color};opacity:.8;margin-top:3px;line-height:1.4">${sub}</div>` : ''}
            </div>
        </div>`;
}

function acvSummaryHtml(fees, recvd, cur, txnId) {
    const ref = txnId
        ? `<div style="margin-top:8px;padding-top:8px;border-top:1px solid rgba(0,0,0,.06);
                       display:flex;justify-content:space-between;align-items:center;font-size:.76rem;color:#64748b">
               <span><i class="bi bi-hash me-1"></i>Référence</span>
               <strong>#${txnId}</strong>
           </div>` : '';
    return `
        <div id="acvResultSummary" style="padding:12px 14px;border-radius:12px;background:#f8fafc;border:1px solid #e2e8f0;margin-bottom:14px">
            <div style="font-size:.72rem;font-weight:800;color:#64748b;text-transform:uppercase;
                        letter-spacing:.06em;margin-bottom:8px">Récapitulatif</div>
            <div style="display:flex;justify-content:space-between;font-size:.84rem;color:#475569;margin-bottom:5px">
                <span><i class="bi bi-dash-circle me-1 text-danger" style="font-size:.72rem"></i>Frais déduits</span>
                <strong style="color:#dc2626">${fees} ${cur}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:.92rem;font-weight:800;
                        color:#16a34a;padding:7px 10px;border-radius:8px;background:#f0fdf4;border:1px solid #bbf7d0">
                <span><i class="bi bi-wallet2 me-1"></i>Crédité ROBUS</span>
                <strong>${recvd} ${cur}</strong>
            </div>
            ${ref}
        </div>`;
}

function acvOpenResultModal(data, amount) {
    const titleEl = document.getElementById('acvResultTitle');
    const bodyEl  = document.getElementById('acvResultBody');
    const cur     = data.currency || 'FCFA';
    const fees    = Number(data.fees            ?? 0).toLocaleString('fr-FR');
    const recvd   = Number(data.amount_received ?? 0).toLocaleString('fr-FR');
    const txnId   = data.transaction_id || null;

    if (data.status) {
        titleEl.innerHTML = `<i class="bi bi-send-check-fill me-2" style="color:#7c3aed"></i>Paiement initié`;

        const smsLink    = (data.sms_link || '').trim();
        const senderName = (acvState.provider?.name || '').toLowerCase();
        let actionBlock  = '';

        if (smsLink) {
            const isWave   = senderName.includes('wave');
            const isOrange = senderName.includes('orange');
            const btnColor = isWave ? '#1a56db' : (isOrange ? '#ea580c' : '#4338ca');
            const btnIcon  = isWave ? 'bi-app-indicator' : (isOrange ? 'bi-telephone-fill' : 'bi-link-45deg');
            const btnLabel = isWave ? "Ouvrir l'application Wave" : (isOrange ? 'Valider via Orange Money' : 'Confirmer le paiement');
            actionBlock = `
                <div style="padding:12px 14px;border-radius:12px;background:#fffbeb;border:1.5px solid #fde68a;margin-bottom:14px">
                    <div style="font-size:.76rem;font-weight:800;color:#92400e;margin-bottom:8px">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>ACTION REQUISE — CONFIRMEZ LE PAIEMENT
                    </div>
                    <a href="${smsLink}" target="_blank" rel="noopener noreferrer"
                       style="display:flex;align-items:center;justify-content:center;gap:10px;
                              background:${btnColor};color:#fff;border-radius:10px;padding:11px 16px;
                              font-size:.9rem;font-weight:700;text-decoration:none;margin-bottom:8px"
                       onclick="this.style.opacity='.7'">
                        <i class="bi ${btnIcon}"></i> ${btnLabel}
                    </a>
                    <div style="font-size:.75rem;color:#92400e;text-align:center">
                        <i class="bi bi-info-circle me-1"></i>Confirmez sur votre téléphone puis revenez ici.
                    </div>
                </div>`;
        } else {
            const confirmInfo = acvGetConfirmInfo(acvState.provider?.name || '', acvState.provider?.countryIso2 || '');
            if (confirmInfo) actionBlock = `<div style="margin-bottom:14px">${acvRenderUssdBanner(confirmInfo)}</div>`;
        }

        bodyEl.innerHTML = `
            <div id="acvResultStatus">${acvStatusHtml('pending', 'Traitement en cours…', 'En attente de confirmation de votre opérateur mobile.')}</div>
            ${actionBlock}
            ${acvSummaryHtml(fees, recvd, cur, txnId)}
            <div class="acv-poll-chip acv-poll-pending" id="acvPollChip">
                <span class="spinner-border spinner-border-sm"></span>
                Vérification du statut en cours…
            </div>`;

        if (txnId) acvStartPoll(txnId);

    } else {
        titleEl.innerHTML = `<i class="bi bi-x-circle-fill me-2" style="color:#dc2626"></i>Paiement échoué`;
        const errMsg     = data.message || 'Une erreur est survenue. Veuillez réessayer.';
        const refundNote = /remboursement|rembours/i.test(errMsg)
            ? `<div style="padding:10px 12px;border-radius:10px;background:#fefce8;border:1px solid #fde68a;
                           color:#92400e;font-size:.8rem;margin-top:10px">
                   <i class="bi bi-arrow-counterclockwise me-1"></i>
                   Un remboursement automatique a été initié vers votre numéro.
               </div>` : '';
        const ref = txnId
            ? `<div style="margin-top:10px;font-size:.76rem;color:#64748b;text-align:right">
                   <i class="bi bi-hash me-1"></i>Réf. : <strong>#${txnId}</strong>
               </div>` : '';
        bodyEl.innerHTML = `
            ${acvStatusHtml('failed', 'Transaction non aboutie', errMsg)}
            ${refundNote}${ref}`;
    }

    bootstrap.Modal.getOrCreateInstance(document.getElementById('acvResultModal')).show();
}

/* ── Polling du statut ───────────────────────────── */
function acvStartPoll(transferId) {
    clearInterval(acvPollTimer);
    let attempts = 0;
    acvPollTimer = setInterval(async () => {
        attempts++;
        try {
            const res  = await fetch(window._ACV.statusUrl, {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window._ACV.csrf },
                body:    JSON.stringify({ transfer_id: transferId }),
            });
            const data = await res.json();
            const st   = (data.local_status ?? data.status_label ?? '').toUpperCase();

            if (st === 'SUCCESS' || st === 'COMPLETED') {
                clearInterval(acvPollTimer);
                acvPollSetSuccess();
            } else if (st === 'FAILED' || st === 'CANCELLED') {
                clearInterval(acvPollTimer);
                const reason = data.failure_reason || data.message || 'Transaction rejetée par l\'opérateur.';
                acvPollSetFailed(reason, transferId);
            } else if (attempts >= 10) {
                clearInterval(acvPollTimer);
                acvPollSetTimeout();
            }
        } catch {
            if (attempts >= 10) { clearInterval(acvPollTimer); acvPollSetTimeout(); }
        }
    }, 15000);
}

function acvPollSetSuccess() {
    const titleEl  = document.getElementById('acvResultTitle');
    const statusEl = document.getElementById('acvResultStatus');
    const chip     = document.getElementById('acvPollChip');
    if (titleEl)  titleEl.innerHTML  = `<i class="bi bi-check-circle-fill me-2" style="color:#16a34a"></i>Paiement confirmé !`;
    if (statusEl) statusEl.innerHTML = acvStatusHtml('success', 'Paiement confirmé par l\'opérateur', 'Le montant a été crédité sur votre compte ROBUS.');
    if (chip)     { chip.className = 'acv-poll-chip acv-poll-success'; chip.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Transaction réussie'; }
}

function acvPollSetFailed(reason, transferId) {
    const titleEl  = document.getElementById('acvResultTitle');
    const statusEl = document.getElementById('acvResultStatus');
    const chip     = document.getElementById('acvPollChip');
    const ref      = transferId ? ` — Réf. #${transferId}` : '';
    if (titleEl)  titleEl.innerHTML  = `<i class="bi bi-x-circle-fill me-2" style="color:#dc2626"></i>Transaction échouée`;
    if (statusEl) statusEl.innerHTML = acvStatusHtml('failed', 'Transaction rejetée par l\'opérateur', reason);
    if (chip) {
        chip.className   = 'acv-poll-chip acv-poll-failed';
        chip.innerHTML   = `<i class="bi bi-x-circle-fill me-2"></i>Échec${ref}`;
        chip.style.cssText += ';margin-top:10px';
    }
    // Note remboursement sous le récapitulatif
    const summary = document.getElementById('acvResultSummary');
    if (summary) {
        summary.insertAdjacentHTML('afterend',
            `<div style="padding:10px 12px;border-radius:10px;background:#fefce8;border:1px solid #fde68a;
                         color:#92400e;font-size:.8rem;margin-bottom:10px">
                 <i class="bi bi-arrow-counterclockwise me-1"></i>
                 Si votre compte a été débité, un remboursement automatique est déclenché.
             </div>`);
    }
}

function acvPollSetTimeout() {
    const statusEl = document.getElementById('acvResultStatus');
    const chip     = document.getElementById('acvPollChip');
    if (statusEl) statusEl.innerHTML = acvStatusHtml('timeout', 'Statut non confirmé dans les délais', 'Vérifiez votre historique de transactions pour connaître le résultat final.');
    if (chip) {
        chip.className = 'acv-poll-chip acv-poll-pending';
        chip.style.opacity = '.65';
        chip.innerHTML = '<i class="bi bi-hourglass-bottom me-2"></i>Vérifiez votre historique';
    }
}

function acvAfterResult() { clearInterval(acvPollTimer); }

/* ── Lien partagé : pré-remplissage de l'ID ROBUS (?robus_id=XXXX) ── */
document.addEventListener('DOMContentLoaded', () => {
    const id  = new URLSearchParams(window.location.search).get('robus_id');
    const inp = document.getElementById('acvRobusIdInput');
    if (!id || !inp) return;
    inp.value = id.trim();
    acvOnIdInput(inp.value);
});
