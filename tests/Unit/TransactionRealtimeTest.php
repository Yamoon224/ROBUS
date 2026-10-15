<?php

namespace Tests\Unit;

use App\Http\Livewire\Transfers;
use App\Http\Livewire\TransferVisitors;
use App\Models\Wallets\WalletTransaction;
use Tests\TestCase;

class TransactionRealtimeTest extends TestCase
{
    private function listeners($component): array
    {
        $property = new \ReflectionProperty($component, 'listeners');
        $property->setAccessible(true);
        return $property->getValue($component);
    }

    private function subscription(string $class): string
    {
        $event = new $class(new WalletTransaction(['id' => 1, 'status' => 'SUCCESS']));
        $channels = $event->broadcastOn();
        $channel = is_array($channels) ? $channels[0] : $channels;
        return 'echo:' . $channel->name . ',.' . $event->broadcastAs();
    }

    public function test_back_office_listens_to_each_status_change_event(): void
    {
        $listeners = $this->listeners(new Transfers);
        foreach (['UpdateBank', 'UpdateBankSend', 'UpdateDjamo', 'UpdateInter',
            'UpdatePaiement', 'UpdatePhoneCredit', 'UpdatePush', 'UpdateReloadCard',
            'UpdateTransfer', 'UpdateTresort', 'UpdateVersment', 'UpdateXbet'] as $name) {
            $this->assertArrayHasKey($this->subscription('App\\Events\\' . $name . 'Event'), $listeners);
        }
    }

    public function test_web_space_listens_to_creation_claim_and_status_changes(): void
    {
        $listeners = $this->listeners(new TransferVisitors);
        foreach (['CreateWalletOneXbetVisEvent', 'CreateWalletTransfersVisEvent',
            'CreateWalletInterVisEvent', 'CreateWalletReloadCardVisEvent', 'CreateWalletPhoneCreditVisEvent',
            'ChooseTransactionVisEvent', 'ChooseCardVisEvent', 'ChooseInterVisEvent',
            'ChoosePhoneVisEvent', 'ChooseXbetVisEvent', 'WebTransactionUpdated'] as $name) {
            $this->assertArrayHasKey($this->subscription('App\\Events\\Visitors\\' . $name), $listeners);
        }
    }

    public function test_incoming_web_notifications_do_not_switch_tabs(): void
    {
        $component = new TransferVisitors;
        foreach (['notifyNewTransfers', 'notifyNewInter', 'notifyNewCard', 'notifyNewPhone', 'notifyNewXbet'] as $method) {
            $component->{$method}();
        }
        $this->assertSame([], $component->getEventQueue());
        $this->assertTrue($component->showNTransfersNotification);
    }

    public function test_web_status_event_does_not_broadcast_customer_data(): void
    {
        $transaction = new WalletTransaction(['id' => 3, 'status' => 'SUCCESS', 'phone' => 'private']);
        $event = new \App\Events\Visitors\WebTransactionUpdated($transaction);
        $this->assertSame(['id' => 3, 'status' => 'SUCCESS'], $event->broadcastWith());
    }
}
