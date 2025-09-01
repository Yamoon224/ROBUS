<?php

namespace App\Repositories;

use App\Models\Wallets\WalletTresortMoney;
use App\Repositories\BaseRepository;

/**
 * Class ReloadCardRepository
 * @package App\Repositories
 * @version 
 */

class TresortRepository extends BaseRepository
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

    public function getTresorById($xbet_id)
    {
        return WalletTresortMoney::findOrFail($xbet_id);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletTresortMoney::class;
    }
}