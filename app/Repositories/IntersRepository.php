<?php

namespace App\Repositories;

use App\Models\Wallets\WalletInter;
use App\Repositories\BaseRepository;

/**
 * Class IntersRepository
 * @package App\Repositories
 * @version 
 */

class IntersRepository extends BaseRepository
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

    public function getInterById($interId)
    {
        return WalletInter::findOrFail($interId);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletInter::class;
    }
}