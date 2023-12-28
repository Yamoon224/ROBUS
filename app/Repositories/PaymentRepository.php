<?php

namespace App\Repositories;

use App\Models\Wallets\WalletPayment;
use App\Repositories\BaseRepository;

/**
 * Class PaymentRepository
 * @package App\Repositories
 * @version 
 */

class PaymentRepository extends BaseRepository
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

    public function getPaymentById($paymentId)
    {
        return WalletPayment::findOrFail($paymentId);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletPayment::class;
    }
}