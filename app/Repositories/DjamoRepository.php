<?php

namespace App\Repositories;

use App\Models\Wallets\WalletDjamo;
use App\Repositories\BaseRepository;

/**
 * Class ReloadCardRepository
 * @package App\Repositories
 * @version 
 */

class DjamoRepository extends BaseRepository
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

    public function getXbetById($xbet_id)
    {
        return WalletDjamo::findOrFail($xbet_id);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletDjamo::class;
    }
}