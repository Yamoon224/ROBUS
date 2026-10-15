-- ============================================================
-- UPDATE logos providers depuis dist/providers/
-- Path stocké : dist/providers/<fichier>
-- Exécuter dans phpMyAdmin ou via : php artisan db:seed (ou directement en SQL)
-- ============================================================

-- ── Providers existants (IDs historiques) ──────────────────

-- MTN (id=1) — logo mtn.png
UPDATE providers SET logo = 'dist/providers/mtn.png' WHERE name LIKE '%Mtn%' OR name LIKE '%MTN%';

-- Orange (id=2,4) — logo orange.png
UPDATE providers SET logo = 'dist/providers/orange.png' WHERE name LIKE '%Orange%';

-- Moov (id=3) — logo moov.png
UPDATE providers SET logo = 'dist/providers/moov.png' WHERE name LIKE '%Moov%';

-- Wave — logo wave.png
UPDATE providers SET logo = 'dist/providers/wave.png' WHERE name LIKE '%Wave%';

-- Djamo — logo djamo.png
UPDATE providers SET logo = 'dist/providers/djamo.png' WHERE name LIKE '%Djamo%' OR name LIKE '%djamo%';

-- Airtel — logo airTel.png
UPDATE providers SET logo = 'dist/providers/airTel.png' WHERE name LIKE '%Airtel%' OR name LIKE '%AirTel%';

-- Tigo — logo tigo.png
UPDATE providers SET logo = 'dist/providers/tigo.png' WHERE name LIKE '%Tigo%';

-- Vodafone — logo vodafone.png
UPDATE providers SET logo = 'dist/providers/vodafone.png' WHERE name LIKE '%Vodafone%';

-- M-Pesa / M-PESA — logo M-PESA.png
UPDATE providers SET logo = 'dist/providers/M-PESA.png' WHERE name LIKE '%M-Pesa%' OR name LIKE '%M-PESA%' OR name LIKE '%mpesa%';

-- Safaricom — logo sarifacom.png
UPDATE providers SET logo = 'dist/providers/sarifacom.png' WHERE name LIKE '%Safaricom%';

-- Africell / Africell — logo africell.png
UPDATE providers SET logo = 'dist/providers/africell.png' WHERE name LIKE '%Africell%' OR name LIKE '%Africel%';

-- T-Kash / TKash — logo tkash.png
UPDATE providers SET logo = 'dist/providers/tkash.png' WHERE name LIKE '%T-Kash%' OR name LIKE '%TKash%' OR name LIKE '%T Kash%';

-- Togocel / TogoCell — logo togoCell.png
UPDATE providers SET logo = 'dist/providers/togoCell.png' WHERE name LIKE '%Togocel%' OR name LIKE '%TogoCell%' OR name LIKE '%Togo Cell%';

-- Telecel — logo telecel_money.png
UPDATE providers SET logo = 'dist/providers/telecel_money.png' WHERE name LIKE '%Telecel%';

-- Q Money / Q-Money — logo q_money.png
UPDATE providers SET logo = 'dist/providers/q_money.png' WHERE name LIKE '%Q Money%' OR name LIKE '%Q-Money%' OR name LIKE '%Qmoney%';

-- Libertis — logo libertis.png
UPDATE providers SET logo = 'dist/providers/libertis.png' WHERE name LIKE '%Libertis%';

-- Sedad — logo sedad.png
UPDATE providers SET logo = 'dist/providers/sedad.png' WHERE name LIKE '%Sedad%';

-- Bankily — logo bankily.png
UPDATE providers SET logo = 'dist/providers/bankily.png' WHERE name LIKE '%Bankily%';

-- Masrvi / Masri — logo masri.png
UPDATE providers SET logo = 'dist/providers/masri.png' WHERE name LIKE '%Masrvi%' OR name LIKE '%Masri%';

-- Muni Dinero / Mini Dinero — logo mini_dinero.jpeg
UPDATE providers SET logo = 'dist/providers/mini_dinero.jpeg' WHERE name LIKE '%Muni Dinero%' OR name LIKE '%Mini Dinero%';

-- Pixel Innov — logo pixel_innov.jpeg
UPDATE providers SET logo = 'dist/providers/pixel_innov.jpeg' WHERE name LIKE '%Pixel%';

-- Chinguittel — logo chinguittel.png
UPDATE providers SET logo = 'dist/providers/chinguittel.png' WHERE name LIKE '%Chinguittel%' OR name LIKE '%Mauritel%';

-- Mattel — logo mattel.png
UPDATE providers SET logo = 'dist/providers/mattel.png' WHERE name LIKE '%Mattel%';

-- Ethio — logo ethio.png
UPDATE providers SET logo = 'dist/providers/ethio.png' WHERE name LIKE '%Ethio%';

-- Zantel — logo zantel.png
UPDATE providers SET logo = 'dist/providers/zantel.png' WHERE name LIKE '%Zantel%';

-- Sierratel — logo sierratel.png
UPDATE providers SET logo = 'dist/providers/sierratel.png' WHERE name LIKE '%Sierratel%';

-- Free Money — logo Cash_Money_log.png (le plus proche disponible)
UPDATE providers SET logo = 'dist/providers/Cash_Money_log.png' WHERE name LIKE '%Free Money%';

-- T-Money — logo q_money.png (aucun logo T-Money dispo, fallback)
-- UPDATE providers SET logo = 'dist/providers/q_money.png' WHERE name LIKE '%T-Money%';

-- ── Providers IDs exacts insérés récemment (150→180) ───────

UPDATE providers SET logo = 'dist/providers/africell.png'    WHERE id = 150; -- Vodacom (pas de logo, remplacer manuellement)
UPDATE providers SET logo = 'dist/providers/mtn.png'         WHERE id = 151; -- Unitel   (pas de logo, remplacer manuellement)
UPDATE providers SET logo = NULL                             WHERE id = 152; -- Movicel
UPDATE providers SET logo = 'dist/providers/africell.png'    WHERE id = 153; -- Africell ✓
UPDATE providers SET logo = NULL                             WHERE id = 154; -- Lumitel
UPDATE providers SET logo = NULL                             WHERE id = 155; -- Econet Leo
UPDATE providers SET logo = NULL                             WHERE id = 156; -- ONATEL
UPDATE providers SET logo = NULL                             WHERE id = 157; -- Evatis
UPDATE providers SET logo = NULL                             WHERE id = 158; -- Movitel
UPDATE providers SET logo = NULL                             WHERE id = 159; -- Alipay
UPDATE providers SET logo = NULL                             WHERE id = 160; -- Weixin
UPDATE providers SET logo = 'dist/providers/q_money.png'     WHERE id = 161; -- Q Money ✓
UPDATE providers SET logo = 'dist/providers/airTel.png'      WHERE id = 162; -- Celtel (ex-Airtel) ✓
UPDATE providers SET logo = NULL                             WHERE id = 163; -- CVMovel
UPDATE providers SET logo = 'dist/providers/bankily.png'     WHERE id = 164; -- Bankily ✓
UPDATE providers SET logo = NULL                             WHERE id = 165; -- Cell C
UPDATE providers SET logo = 'dist/providers/airTel.png'      WHERE id = 166; -- Airtel ✓
UPDATE providers SET logo = 'dist/providers/tigo.png'        WHERE id = 167; -- Tigo ✓
UPDATE providers SET logo = 'dist/providers/africell.png'    WHERE id = 168; -- Afrimoney (filiale Africell) ✓
UPDATE providers SET logo = 'dist/providers/vodafone.png'    WHERE id = 169; -- Vodafone ✓
UPDATE providers SET logo = 'dist/providers/masri.png'       WHERE id = 170; -- Masrvi ✓
UPDATE providers SET logo = 'dist/providers/M-PESA.png'      WHERE id = 171; -- M-Pesa ✓
UPDATE providers SET logo = 'dist/providers/libertis.png'    WHERE id = 172; -- Libertis ✓
UPDATE providers SET logo = 'dist/providers/Cash_Money_log.png' WHERE id = 173; -- Free Money
UPDATE providers SET logo = NULL                             WHERE id = 174; -- T-Money
UPDATE providers SET logo = 'dist/providers/mini_dinero.jpeg' WHERE id = 175; -- Muni Dinero ✓
UPDATE providers SET logo = 'dist/providers/sedad.png'       WHERE id = 176; -- Sedad ✓
UPDATE providers SET logo = 'dist/providers/tkash.png'       WHERE id = 177; -- T-Kash ✓
UPDATE providers SET logo = 'dist/providers/sarifacom.png'   WHERE id = 178; -- Safaricom ✓
UPDATE providers SET logo = 'dist/providers/telecel_money.png' WHERE id = 179; -- Telecel ✓
UPDATE providers SET logo = 'dist/providers/togoCell.png'    WHERE id = 180; -- Togocel ✓
