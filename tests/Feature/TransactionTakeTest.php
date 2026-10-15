<?php

namespace Tests\Feature;

use App\Events\ChooseTransactionEvent;
use App\Http\Livewire\Transfers;
use App\Models\Sid\SidUser;
use App\Models\Wallets\WalletTransaction;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TransactionTakeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Use a separate in-memory connection; never touch application transactions.
        config(['database.default' => 'take_test', 'database.connections.take_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '',
        ]]);
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->boolean('is_web')->default(false);
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Event::fake([ChooseTransactionEvent::class]);
        $this->actingAs(new SidUser(['id' => 41, 'is_admin' => 1, 'is_distributer' => 0]), 'web');
    }

    private function transaction(string $status = 'PENDING', ?int $owner = null): WalletTransaction
    {
        return WalletTransaction::create(['status' => $status, 'updated_by' => $owner]);
    }

    public function test_admin_can_take_a_pending_transaction(): void
    {
        $transaction = $this->transaction();
        $component = new RecordedTransfers;
        $result = $component->ChooseUpdateTransaction($transaction->id);
        $this->assertTrue($result['success']);
        $this->assertSame('PROCESSING', $transaction->fresh()->status);
        $this->assertSame(41, $transaction->fresh()->updated_by);
        Event::assertDispatched(ChooseTransactionEvent::class);
        $this->assertContains('transaction-taken', array_column($component->recordedEvents, 0));
        $this->assertContains('success', array_column($component->recordedEvents, 0));
    }

    public function test_distributor_can_take_a_pending_transaction(): void
    {
        $this->actingAs(new SidUser(['id' => 42, 'is_admin' => 0, 'is_distributer' => 1]), 'web');
        $transaction = $this->transaction();
        (new RecordedTransfers)->ChooseUpdateTransaction($transaction->id);
        $this->assertSame(42, $transaction->fresh()->updated_by);
        $this->assertSame('PROCESSING', $transaction->fresh()->status);
    }

    public function test_customer_cannot_take_a_transaction(): void
    {
        $this->actingAs(new SidUser(['id' => 43, 'is_admin' => 0, 'is_distributer' => 0]), 'web');
        $transaction = $this->transaction();
        $component = new RecordedTransfers;
        $result = $component->ChooseUpdateTransaction($transaction->id);
        $this->assertFalse($result['success']);
        $this->assertNotEmpty($result['message']);
        $this->assertSame('PENDING', $transaction->fresh()->status);
        $this->assertNull($transaction->fresh()->updated_by);
        Event::assertNotDispatched(ChooseTransactionEvent::class);
        $this->assertContains('info', array_column($component->recordedEvents, 0));
    }

    public function test_second_click_does_not_take_the_transaction_twice(): void
    {
        $transaction = $this->transaction();
        $component = new RecordedTransfers;
        $component->ChooseUpdateTransaction($transaction->id);
        $component->ChooseUpdateTransaction($transaction->id);
        Event::assertDispatchedTimes(ChooseTransactionEvent::class, 1);
        $this->assertSame(41, $transaction->fresh()->updated_by);
        $this->assertContains('error', array_column($component->recordedEvents, 0));
    }

    public function test_claimed_and_completed_transactions_are_left_unchanged(): void
    {
        foreach (['PENDING', 'PROCESSING', 'SUCCESS', 'ERROR'] as $status) {
            $transaction = $this->transaction($status, 99);
            $component = new RecordedTransfers;
            $component->ChooseUpdateTransaction($transaction->id);
            $this->assertSame($status, $transaction->fresh()->status);
            $this->assertSame(99, $transaction->fresh()->updated_by);
            $this->assertContains('error', array_column($component->recordedEvents, 0));
        }
        Event::assertNotDispatched(ChooseTransactionEvent::class);
    }

    public function test_missing_transaction_reports_failure(): void
    {
        $component = new RecordedTransfers;
        $result = $component->ChooseUpdateTransaction(999);
        $this->assertFalse($result['success']);
        $this->assertContains('error', array_column($component->recordedEvents, 0));
        Event::assertNotDispatched(ChooseTransactionEvent::class);
    }

    public function test_event_failure_does_not_block_the_claim(): void
    {
        Event::swap(new \Illuminate\Events\Dispatcher($this->app));
        Event::listen(ChooseTransactionEvent::class, function () {
            throw new \RuntimeException('Simulated broadcast or listener failure');
        });
        $transaction = $this->transaction();
        $component = new RecordedTransfers;
        $result = $component->ChooseUpdateTransaction($transaction->id);
        $this->assertTrue($result['success']);
        $this->assertSame('PROCESSING', $transaction->fresh()->status);
        $this->assertSame(41, $transaction->fresh()->updated_by);
        $this->assertContains('success', array_column($component->recordedEvents, 0));
        $this->assertNotContains('error', array_column($component->recordedEvents, 0));
    }

    public function test_later_transaction_from_another_country_can_be_claimed_first(): void
    {
        $older = $this->transaction();
        $older->update(['country_id' => 107, 'created_at' => now()->subHour()]);
        $later = $this->transaction();
        $later->update(['country_id' => 108]);
        $component = new RecordedTransfers;
        $this->assertTrue($component->ChooseUpdateTransaction($later->id)['success']);
        $this->assertSame('PENDING', $older->fresh()->status);
        $this->assertNull($older->fresh()->updated_by);
        // The first operator need not finish their other transaction.
        $this->assertTrue($component->ChooseUpdateTransaction($older->id)['success']);
    }

    public function test_other_operator_claim_does_not_block_another_transaction(): void
    {
        $busy = $this->transaction('PROCESSING', 99);
        $busy->update(['country_id' => 107]);
        $available = $this->transaction();
        $available->update(['country_id' => 108]);
        $this->assertTrue((new RecordedTransfers)->ChooseUpdateTransaction($available->id)['success']);
        $this->assertSame(99, $busy->fresh()->updated_by);
        $this->assertSame('PROCESSING', $busy->fresh()->status);
        $this->assertSame(41, $available->fresh()->updated_by);
    }

    public function test_back_office_cannot_claim_a_web_transaction(): void
    {
        $transaction = $this->transaction();
        $transaction->update(['is_web' => true]);
        $this->assertFalse((new RecordedTransfers)->ChooseUpdateTransaction($transaction->id)['success']);
        $this->assertNull($transaction->fresh()->updated_by);
    }

    public function test_web_operator_can_claim_out_of_order_without_overwriting_another_owner(): void
    {
        Event::fake([\App\Events\Visitors\ChooseTransactionVisEvent::class]);
        $this->actingAs(new ClaimOperator(['id' => 42]), 'web');
        $older = $this->transaction();
        $older->update(['is_web' => true, 'country_id' => 107, 'created_at' => now()->subHour()]);
        $later = $this->transaction();
        $later->update(['is_web' => true, 'country_id' => 108]);
        $component = new \App\Http\Livewire\TransferVisitors;
        $this->assertTrue($component->ChooseUpdateTransaction($later->id)['success']);
        $this->assertNull($older->fresh()->updated_by);
        $this->assertSame(42, $later->fresh()->updated_by);
        $this->assertSame('PENDING', $later->fresh()->status);
        $this->actingAs(new ClaimOperator(['id' => 43]), 'web');
        $this->assertFalse($component->ChooseUpdateTransaction($later->id)['success']);
        $this->assertSame(42, $later->fresh()->updated_by);
        $this->assertTrue($component->ChooseUpdateTransaction($older->id)['success']);
        $this->assertSame(43, $older->fresh()->updated_by);
        Event::assertDispatchedTimes(\App\Events\Visitors\ChooseTransactionVisEvent::class, 2);
    }

    public function test_web_space_cannot_claim_back_office_transactions(): void
    {
        $this->actingAs(new ClaimOperator(['id' => 42]), 'web');
        $transaction = $this->transaction();
        $component = new \App\Http\Livewire\TransferVisitors;
        $this->assertFalse($component->ChooseUpdateTransaction($transaction->id)['success']);
        $this->assertNull($transaction->fresh()->updated_by);
    }

    public function test_all_back_office_transaction_types_can_be_claimed_independently(): void
    {
        config(['activitylog.enabled' => false]);
        $component = new RecordedTransfers;
        $property = new \ReflectionProperty(Transfers::class, 'transactionConfigs');
        $property->setAccessible(true);
        $configs = $property->getValue($component);
        Event::fake(array_column($configs, 'event'));
        foreach ($configs as $type => $config) {
            $model = $config['model'];
            $table = (new $model)->getTable();
            if (!Schema::hasTable($table)) {
                Schema::create($table, function (Blueprint $table) {
                    $table->id();
                    $table->string('status');
                    $table->boolean('is_web')->default(false);
                    $table->unsignedBigInteger('updated_by')->nullable();
                    $table->timestamps();
                    $table->softDeletes();
                });
            }
            $older = $model::create(['status' => 'PENDING', 'updated_by' => null, 'is_web' => false]);
            $selected = $model::create(['status' => 'PENDING', 'updated_by' => null, 'is_web' => false]);
            $result = $component->takeTransaction($type, $selected->id);
            $this->assertTrue($result['success'], $type);
            $this->assertNull($older->fresh()->updated_by, $type);
            $this->assertSame('PROCESSING', $selected->fresh()->status, $type);
            $this->assertSame(41, $selected->fresh()->updated_by, $type);
        }
    }

    protected function tearDown(): void
    {
        DB::purge('take_test');
        parent::tearDown();
    }
}

class RecordedTransfers extends Transfers
{
    public array $recordedEvents = [];

    public function emit($event, ...$params)
    {
        $this->recordedEvents[] = [$event, $params];
        return parent::emit($event, ...$params);
    }
}

class ClaimOperator extends SidUser
{
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        return $permission === 'traitement-demande';
    }
}
