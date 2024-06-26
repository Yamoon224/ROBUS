<?php

namespace App\Repositories;

use App\Models\Wallets\WalletWave;
use App\Repositories\BaseRepository;

/**
 * Class LoginRepository
 * @package App\Repositories
 * @version April 22, 2022, 7:07 pm UTC
 */

class WaveRepository extends BaseRepository
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
     * Configure the Model
     **/
    public function model()
    {
        return WalletWave::class;
    }
}