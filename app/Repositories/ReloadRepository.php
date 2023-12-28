<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Wallets\WalletReload;
use App\Repositories\BaseRepository;

/**
 * Class ReloadRepository
 * @package App\Repositories
 * @version 
 */

class ReloadRepository extends BaseRepository
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

    public function getReloadById($reloadId)
    {
        return WalletReload::findOrFail($reloadId);
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return WalletReload::class;
    }
}