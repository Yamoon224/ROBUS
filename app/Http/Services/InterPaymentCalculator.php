<?php

namespace App\Services\Robus;

use App\Models\Custumers\Country;

class InterPaymentCalculator
{


    public function __construct(
        protected $percentRobus,
        protected $validatedamountDays
    ) {}

    public function calculate(
        string $countryName,
        float $amount,
        int $providerOutCiv
    ): array {

        $country = Country::whereName($countryName)->first();

        if (!$country || $amount <= 0) {
            return $this->error('Pays ou montant invalide.');
        }

        $maxSend = $country->amount_max_send ?? 1_000_000;

        $commission = $this->percentRobus
            ->getRobusPercentageInter($country, $providerOutCiv);

        if (!$commission) {
            return $this->error('Aucune commission définie.');
        }

        $isDistributor = robus()->is_under_distributer || robus()->is_distributer;
        
        $commissionPay = $this->calculateCommissionPay(
            $commission,
            $amount,
            $isDistributor
        );

        $amountAfterCommission = $amount - $commissionPay;

        if ($amountAfterCommission < 0) {
            return $this->error('Montant négatif');
        }

        $receivedAmount = $this->calculateReceiveAmount(
            $amountAfterCommission,
            $country,
            $providerOutCiv,
            $maxSend
        );

        return [
            'success' => true,
            'data' => [
                'amount_commission' => $this->calculateInterPay(
                    $commission,
                    $amount,
                    $isDistributor
                ),
                'amount_pay' => $commissionPay,
                'amount_receive' => $receivedAmount . ' ' . $country->currencie->code,
            ],
        ];
    }

    /* ================= HELPERS ================= */
    private function calculateCommissionPay($commission, float $amount, bool $isDistributor): float
    {
        $percent = $isDistributor
            ? $commission->percent_under_distributer
            : $commission->percent;

        return (float) $percent * $amount;
    }

    private function calculateInterPay($commission, float $amount, bool $isDistributor): float
    {
        return $isDistributor
            ? (float) $commission->percent_deposit_under_distributer * $amount
            : (float) $commission->percent * $amount;
    }

    private function calculateReceiveAmount(
        float $amount,
        Country $country,
        float $maxSend
    ): float {

        $senderRate = robus()->country->currencie->value;
        $receiverRate = $country->currencie->value;

        $converted = $senderRate === $receiverRate
            ? $amount
            : ($amount * $senderRate) / $receiverRate;

        if (!$this->validatedamountDays->validateAmountSendLimit($converted, $maxSend)) {
            throw new \Exception(
                "Le montant maximum à envoyer est {$maxSend} {$country->currencie->code}"
            );
        }

        return round($converted, 2);
    }

    private function error(string $message): array
    {
        return [
            'success' => false,
            'message' => $message,
        ];
    }
}
