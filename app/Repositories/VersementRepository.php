<?php

namespace App\Repositories;

use App\Models\Wallets\WalletVersement;
use App\Repositories\BaseRepository;

/**
 * Class VersementRepository
 * @package App\Repositories
 * @version 
 */

class VersementRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Return Details Virement
     *
     * @return array
     */

    public function getVirementById($paymentId)
    {
        return WalletVersement::findOrFail($paymentId);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletVersement::class;
    }
}