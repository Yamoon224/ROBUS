<?php

namespace App\Repositories;

use App\Models\Wallets\WalletXbet;
use App\Repositories\BaseRepository;

/**
 * Class ReloadCardRepository
 * @package App\Repositories
 * @version 
 */

class OnexbetRepository extends BaseRepository
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
        return WalletXbet::findOrFail($xbet_id);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletXbet::class;
    }
}