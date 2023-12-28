<?php

namespace App\Repositories;

use App\Models\Wallets\WalletDeposit;
use App\Repositories\BaseRepository;

/**
 * Class ReloadCardRepository
 * @package App\Repositories
 * @version 
 */

class ReloadCardRepository extends BaseRepository
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

    public function getReloadCardById($payment_cardId)
    {
        return WalletDeposit::findOrFail($payment_cardId);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletDeposit::class;
    }
}