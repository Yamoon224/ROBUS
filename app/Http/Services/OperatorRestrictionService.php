<?php

namespace App\Http\Services;

use App\Models\OperatorService;

class OperatorRestrictionService
{
    protected $operatorService;
    protected $restrictionMap = [
        'Active Transactions' => 'showRemonterTransButton',
        'Active Crédits' => 'showRemonterCreditButton',
        'Active Paiements' => 'showRemonterPaiementButton',
        'Active International' => 'showRemonterInterButton',
        'Active Rechargements' => 'showRemonterRechargementButton',
        'Active Virements' => 'showRemonterVirementButton',
        'Active Depot' => 'showRemonterDepotButton',
        'Active Retrait' => 'showRemonterRetraitButton',
        'Active Money' => 'showRemonterMoneyButton',
        'Active Commande' => 'showRemonterOrderButton',
        'Active Push' => 'showRemonterPushButton',
        'Active Djamo' => 'showRemonterDjamoButton',
        'Active Xbets' => 'showRemonterXbetButton',
        'Active Bank Send' => 'showRemonterBankSendButton',
        'Active Wave' => 'showRemonterWaveButton',
        'Active Tresort Money' => 'showRemonterTresortButton',
        'Active Retrait Xbet' => 'showRemonterXbetDrawalButton',
        'Active Versements' => 'showRemonterVersementButton',
    ];

    protected $restrictionCMap = [
        'Active Transactions' => 'showRemonterTransCButton',
        'Active Crédits' => 'showRemonterCreditCButton',
        'Active Paiements' => 'showRemonterPaiementCButton',
        'Active International' => 'showRemonterInterCButton',
        'Active Rechargements' => 'showRemonterRechargementCButton',
        'Active Virements' => 'showRemonterVirementCButton',
        'Active Depot' => 'showRemonterDepotCButton',
        'Active Retrait' => 'showRemonterRetraitCButton',
        'Active Money' => 'showRemonterMoneyCButton',
        'Active Commande' => 'showRemonterOrderCButton',
        'Active Push' => 'showRemonterPushCButton',
        'Active Bank Send' => 'showRemonterBankSendCButton',
        'Active Xbets' => 'showRemonterXbetCButton',
        'Active Tresort Money' => 'showRemonterTresortCButton',
        'Active Djamo' => 'showRemonterDjamoCButton',
        'Active Retrait Xbet' => 'showRemonterXbetDrawalCButton',
        'Active Wave' => 'showRemonterWaveCButton',
        'Active Versements' => 'showRemonterVersementCButton',
    ];


    public function __construct(OperatorService $operatorService)
    {
        $this->operatorService = $operatorService;
    }

    /**
     * Récupère les boutons actifs en fonction des restrictions des opérateurs.
     *
     * @param int $firmId
     * @return array
     */
    public function getActiveButtons(int $sidUserId): array
    {
        $operators = $this->operatorService->getOperatorsWithCommissions($sidUserId);
        $activeButtons = [];

        foreach ($operators as $operator) {
            $restrictions = explode(',', $operator->name);

            foreach ($this->restrictionMap as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $activeButtons[$buttonVar] = true ?? false;
                }
            }
        }

        return $activeButtons;
    }

    public function getActiveCButtons(int $sidUserId): array
    {
        $operators = $this->operatorService->getOperatorsWithCommissions($sidUserId);
        $activeButtons = [];

        foreach ($operators as $operator) {
            $restrictions = explode(',', $operator->name);

            foreach ($this->restrictionCMap as $restriction => $buttonVar) {
                if (in_array($restriction, $restrictions)) {
                    $activeButtons[$buttonVar] = true ?? false;
                }
            }
        }

        return $activeButtons;
    }
}
