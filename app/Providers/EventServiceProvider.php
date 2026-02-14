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
        // Auth
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // --- Choose Events ---
        \App\Events\ChoosePaymentEvent::class => [
            \App\Listeners\PaymentChoose::class,
        ],
        \App\Events\ChoosePhoneCreditEvent::class => [
            \App\Listeners\PhoneCreditChoose::class,
        ],
        \App\Events\ChooseReloadCardEvent::class => [
            \App\Listeners\ReloadCardChoose::class,
        ],
        \App\Events\ChooseTransactionEvent::class => [
            \App\Listeners\TransactionChoose::class,
        ],
        \App\Events\ChooseVirementEvent::class => [
            \App\Listeners\VirementChoose::class,
        ],
        \App\Events\ChooseXbetEvent::class => [
            \App\Listeners\XbetChoose::class,
        ],

        \App\Events\ChooseInterEvent::class => [
            \App\Listeners\InterChoose::class,
        ],

        // New event
        \App\Events\ChooseTresortEvent::class => [
            \App\Listeners\TresortMoneyChoose::class,
        ],

        

        \App\Events\ChooseDrawalEvent::class => [
            \App\Listeners\DrawalChoose::class,
        ],

        \App\Events\ChooseBankSendEvent::class => [
            \App\Listeners\BankSendChoose::class,
        ],

        \App\Events\ChooseDjamoEvent::class => [
            \App\Listeners\DjamoChoose::class,
        ],

        \App\Events\ChooseVersementEvent::class => [
            \App\Listeners\VersementChoose::class,
        ],

        \App\Events\ChoosePushEvent::class => [
            \App\Listeners\PushChoose::class,
        ],


        // --- Create Events ---
        \App\Events\CreateWalletBankEvent::class => [
            \App\Listeners\CreateBank::class,
        ],

         \App\Events\CreateCardOrderEvent::class => [
            \App\Listeners\CreateOderBank::class,
        ],

        \App\Events\CreateWalletInterEvent::class => [
            \App\Listeners\CreateInter::class,
        ],
        \App\Events\CreateWalletPaiementEvent::class => [
            \App\Listeners\CreatePaiement::class,
        ],
        \App\Events\CreateWalletPhoneCreditEvent::class => [
            \App\Listeners\CreatePhoneCredit::class,
        ],
        \App\Events\CreateWalletReloadCardEvent::class => [
            \App\Listeners\CreateReloadCard::class,
        ],
        \App\Events\CreateWalletTransfersEvent::class => [
            \App\Listeners\CreateTransfert::class,
        ],

        \App\Events\CreateWalletDrawalEvent::class => [
            \App\Listeners\CreateDrawal::class,
        ],

        \App\Events\CreateReloadEvent::class => [
            \App\Listeners\ReloadAccompte::class,
        ],

        // --- Visitor Events ---
        \App\Events\Visitors\CreateWalletInterVisEvent::class => [
            \App\Listeners\Visitors\CreateInterVis::class,
        ],
        \App\Events\Visitors\CreateWalletPhoneCreditVisEvent::class => [
            \App\Listeners\Visitors\CreatePhoneCreditVis::class,
        ],
        \App\Events\Visitors\CreateWalletReloadCardVisEvent::class => [
            \App\Listeners\Visitors\CreateReloadCardVis::class,
        ],
        \App\Events\Visitors\CreateWalletTransfersVisEvent::class => [
            \App\Listeners\Visitors\CreateTransfertVis::class,
        ],
        \App\Events\Visitors\CreateWalletOneXbetVisEvent::class => [
            \App\Listeners\Visitors\CreateXbetVis::class,
        ],

        // --- Choose Visitor Events ---
        \App\Events\Visitors\ChoosePhoneVisEvent::class => [
            \App\Listeners\Visitors\PhoneCreditChooseVis::class,
        ],
        \App\Events\Visitors\ChooseCardVisEvent::class => [
            \App\Listeners\Visitors\ReloadCardChooseVis::class,
        ],
        \App\Events\Visitors\ChooseTransactionVisEvent::class => [
            \App\Listeners\Visitors\TransactionChooseVis::class,
        ],
        \App\Events\Visitors\ChooseXbetVisEvent::class => [
            \App\Listeners\Visitors\XbetChooseVis::class,
        ],
        \App\Events\Visitors\ChooseInterVisEvent::class => [
            \App\Listeners\Visitors\InterChooseVis::class,
        ],

        // Updated events
        // \App\Events\UpdateBankEvent::class => [
        //     \App\Listeners\BankUpdate::class,
        // ],


        // \App\Events\UpdateBankSendEvent::class => [
        //     \App\Listeners\BankSend::class,
        // ],
        // \App\Events\UpdateDjamoEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],
        // \App\Events\UpdateInterEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],
        // \App\Events\UpdateOrderCardEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],
        // \App\Events\UpdatePaiementEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],
        // \App\Events\UpdatePhoneCreditEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],

        //   \App\Events\UpdatePushEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],

        //    \App\Events\UpdateReloadCardEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],


        //    \App\Events\UpdateReloadEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],


        //    \App\Events\UpdateTransferEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],


        //   \App\Events\UpdateTresortEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],

        //   \App\Events\UpdateVersmentEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],
        //   \App\Events\UpdateWaveEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],
        //   \App\Events\UpdateXbetEvent::class => [
        //     \App\Listeners\Visitors\InterChooseVis::class,
        // ],
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
