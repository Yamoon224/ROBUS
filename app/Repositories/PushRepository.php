<?php

namespace App\Repositories;

use App\Models\Wallets\WalletPush;
use App\Repositories\BaseRepository;

/**
 * Class ReloadCardRepository
 * @package App\Repositories
 * @version 
 */

class PushRepository extends BaseRepository
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

    public function getPushById($xbet_id)
    {
        return WalletPush::findOrFail($xbet_id);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletPush::class;
    }
}