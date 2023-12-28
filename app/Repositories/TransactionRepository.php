<?php

namespace App\Repositories;

use App\Models\Wallets\WalletTransaction;
use App\Repositories\BaseRepository;

/**
 * Class TransactionRepository
 * @package App\Repositories
 * @version 
 */

class TransactionRepository extends BaseRepository
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

    public function getTransactionById($trancationId)
    {
        return WalletTransaction::findOrFail($trancationId);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletTransaction::class;
    }
}