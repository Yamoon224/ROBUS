<?php

namespace App\Traits;

use App\Events\CreateWalletBankEvent;
use App\Models\Wallets\WalletBank;

trait ActivityWalletBank
{
    /**
     * Register the necessary event listeners.
     *
     * @return void
     */
    protected static function bootRecordsActivity()
    {
        foreach (static::getModelEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }
    }

    /**
     * Record activity for the model.
     *
     * @param  string $event
     * @return void
     */
    public function recordActivity($event)
    {
        $activity_bank = WalletBank::create([
            'id' => $this->id,
            'amount' => $this->amount($this. $event),
        ]);

        broadcast(new CreateWalletBankEvent($activity_bank));
    }


    
    /**
     * Get the model events to record activity for.
     *
     * @return array
     */
    protected static function getModelEvents()
    {
        if (isset(static::$recordEvents)) {
            return static::$recordEvents;
        }

        return [
            'created', 'deleted', 'updated'
        ];
    }
}