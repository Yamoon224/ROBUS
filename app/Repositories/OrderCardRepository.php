<?php

namespace App\Repositories;

use App\Models\Banks\BankCard;
use App\Repositories\BaseRepository;

/**
 * Class OrderCardRepository
 * @package App\Repositories
 * @version 
 */

class OrderCardRepository extends BaseRepository
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
     * Return Details Card
     *
     * @return array
     */

    public function getCardPaymentById($cardId)
    {
        return BankCard::findOrFail($cardId);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return BankCard::class;
    }
}