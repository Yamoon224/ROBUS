<?php

namespace App\Repositories;

use App\Models\Wallets\WalletPhoneCredit;
use App\Repositories\BaseRepository;

/**
 * Class ReloadCardRepository
 * @package App\Repositories
 * @version 
 */

class PhoneCreditRepository extends BaseRepository
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

    public function getPhoneCreditById($phone_creditId)
    {
        return WalletPhoneCredit::findOrFail($phone_creditId);
    }


    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletPhoneCredit::class;
    }
}