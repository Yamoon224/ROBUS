<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    // protected $listen = [
    //     Registered::class => [
    //         SendEmailVerificationNotification::class,
    //     ],
    // ];

    protected $listen = [
        // choose Listeners
        'App\Events\ChoosePaymentEvent' => [
            'App\Listeners\PaymentChoose',
        ],
        'App\Events\ChoosePhoneCreditEvent' => [
            'App\Listeners\ChoosePhoneCreditEvent',
        ],
        'App\Events\ChooseReloadCardEvent' => [
            'App\Listeners\ReloadCardChoose',
        ],
        'App\Events\ChooseTransactionEvent' => [
            'App\Listeners\TransactionChoose',
        ],
        'App\Events\ChooseVirementEvent' => [
            'App\Listeners\VirementChoose', 
        ],
        'App\Events\ChooseXbetEvent' => [
            'App\Listeners\XbetChoose',
        ],
        'App\Events\ChooseInterEvent' => [
            'App\Listeners\InterChoose',
        ],

        // Created Listeners
        'App\Events\CreateWalletBankEvent' => [
            'App\Listeners\CreateBank',
        ],
        'App\Events\CreateWalletInterEvent' => [
            'App\Listeners\CreateInter',
        ],
        'App\Events\CreateWalletPaiementEvent' => [
            'App\Listeners\CreatePaiement',
        ],
        'App\Events\CreateWalletPhoneCreditEvent' => [
            'App\Listeners\CreatePhoneCredit',
        ],
        'App\Events\CreateWalletReloadCardEvent' => [
            'App\Listeners\CreateReloadCard',
        ],
        'App\Events\CreateWalletTransfersEvent' => [
            'App\Listeners\CreateTransfert',
        ],

        'App\Events\CreateReloadEvent' => [
            'App\Listeners\ReloadAccompte',
        ],
        // Ajoutez d'autres événements et leurs listeners ici...
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
