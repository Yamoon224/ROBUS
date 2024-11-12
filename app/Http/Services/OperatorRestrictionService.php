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
                    $activeButtons[$buttonVar] = true;
                }
            }
        }

        return $activeButtons;
    }
}
