<?php

namespace App\Support;

use App\Models\Currency;

/**
 * Conversion de devise des transferts International Zone (Pixel Innov).
 *
 * Le taux vient de la table `currencies` (page d'administration « Devises ») : le champ `value`
 * est la valeur de 1 unité de la devise en FCFA (ex. 1 Dt = 200, 1 CFA = 1). Le taux entre
 * deux devises est donc  valeur(départ) ÷ valeur(arrivée).
 *
 * Logique d'un transfert (ex. 100 Dt → Côte d'Ivoire, frais de 7,5 %) :
 *   montant envoyé   100 Dt
 *   montant converti 100 × 200 = 20 000 FCFA
 *   frais            20 000 × 7,5 % = 1 500 FCFA   (devise du destinataire)
 *   à recevoir       20 000 − 1 500 = 18 500 FCFA
 */
final class TransferConversion
{
    /** Devises Pixel Innov dont le code diffère de celui de la table `currencies`. */
    private const PIXEL_TO_SYSTEM = [
        'XOF' => 'CFA',
        'XAF' => 'CFA',
    ];

    /** Ligne de la table `currencies` correspondant à une devise Pixel Innov (XOF, XAF, CDF, GMD, GNF…). */
    public static function currencyForPixelCode(?string $pixelCode): ?Currency
    {
        $pixelCode = strtoupper(trim((string) $pixelCode));
        if ($pixelCode === '') {
            return null;
        }

        $code = self::PIXEL_TO_SYSTEM[$pixelCode] ?? $pixelCode;

        return Currency::whereRaw('UPPER(code) = ?', [strtoupper($code)])
            ->where('status', true)
            ->orderBy('id')
            ->first();
    }

    /**
     * Taux de conversion de la devise de départ vers la devise Pixel d'arrivée.
     *
     * @return array{rate: float, from: string, to: string, converted: bool}|null
     *         null si le taux ne peut pas être déterminé (devise absente ou valeur nulle)
     */
    public static function rate(?Currency $from, ?string $toPixelCode): ?array
    {
        $to = self::currencyForPixelCode($toPixelCode);

        if (!$from || !$to) {
            return null;
        }

        if ((int) $from->id === (int) $to->id) {
            return ['rate' => 1.0, 'from' => (string) $from->code, 'to' => (string) $to->code, 'converted' => false];
        }

        $fromValue = (float) $from->value;
        $toValue   = (float) $to->value;

        if ($fromValue <= 0 || $toValue <= 0) {
            return null;
        }

        return [
            'rate'      => $fromValue / $toValue,
            'from'      => (string) $from->code,
            'to'        => (string) $to->code,
            'converted' => true,
        ];
    }

    /** Taux entre deux devises Pixel Innov (ex. CDF → XOF), avec la table `currencies`. */
    public static function rateBetweenPixelCodes(?string $fromPixelCode, ?string $toPixelCode): ?array
    {
        $from = self::currencyForPixelCode($fromPixelCode);

        return $from ? self::rate($from, $toPixelCode) : null;
    }


    /**
     * Montants d'un transfert : conversion, puis frais prélevés dans la devise d'arrivée.
     *
     * Quand le taux vaut 1 (même devise), on garde exactement l'ancien calcul (2 décimales).
     * Sinon les montants sont arrondis à l'unité : les opérateurs mobile money créditent des entiers.
     *
     * @return array{converted: float, fees: float, received: float}
     */
    public static function amounts(float $amount, float $rate, float $totalFeePct): array
    {
        if (abs($rate - 1.0) < 1e-9) {
            $fees = round($amount * $totalFeePct / 100, 2);

            return [
                'converted' => $amount,
                'fees'      => $fees,
                'received'  => round($amount - $fees, 2),
            ];
        }

        $converted = round($amount * $rate);
        $fees      = round($converted * $totalFeePct / 100);

        return [
            'converted' => (float) $converted,
            'fees'      => (float) $fees,
            'received'  => (float) ($converted - $fees),
        ];
    }

    /** Message affiché quand aucun taux ne permet de convertir. */
    public static function missingRateMessage(?Currency $from, ?string $toPixelCode): string
    {
        $fromCode = $from?->code ?: 'devise du portefeuille';

        return "Taux de change non configuré entre {$fromCode} et " . strtoupper((string) $toPixelCode)
            . '. Contactez l\'administrateur.';
    }
}
