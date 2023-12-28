<?php

namespace App\Repositories;

use App\Models\Wallets\WalletBank;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class VirementRepository
 * @package App\Repositories
 * @version 
 */

class VirementRepository extends BaseRepository
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

    public function getVirementById($virementId)
    {
        return WalletBank::findOrFail($virementId);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletBank::class;
    }
}