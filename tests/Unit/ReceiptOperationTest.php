<?php

namespace Tests\Unit;

use App\Models\Wallets\WalletDjamo;
use App\Models\Wallets\WalletPush;
use App\Models\Wallets\WalletTransaction;
use App\Models\Wallets\WalletTresortMoney;
use App\Models\Wallets\WalletXbet;
use App\Support\ReceiptOperation;
use PHPUnit\Framework\TestCase;

class ReceiptOperationTest extends TestCase
{
    public function test_explicit_type_is_used_even_when_the_generic_choice_differs(): void
    {
        $this->assertSame('Retrait', ReceiptOperation::label(new WalletTransaction([
            'type_transaction' => 'Retrait', 'choose_transaction' => 1,
        ])));
        foreach (['Depot', 'Dépôt', ' DEPOSIT '] as $type) {
            $this->assertSame('Dépôt', ReceiptOperation::label(new WalletTransaction(['type_transaction' => $type])));
        }
    }

    public function test_cash_services_support_numeric_and_text_choices(): void
    {
        foreach ([WalletTransaction::class, WalletPush::class, WalletDjamo::class, WalletTresortMoney::class] as $model) {
            foreach ([1 => 'Dépôt', 2 => 'Retrait', 'Transfert' => 'Dépôt', 'Retrait' => 'Retrait'] as $choice => $expected) {
                $this->assertSame($expected, ReceiptOperation::label(new $model(['choose_transaction' => $choice])));
            }
        }
    }

    public function test_xbet_uses_its_specific_deposit_and_withdrawal_choice(): void
    {
        $this->assertSame('Dépôt', ReceiptOperation::label(new WalletXbet(['choose_type_transaction' => '1'])));
        $this->assertSame('Retrait', ReceiptOperation::label(new WalletXbet(['choose_type_transaction' => '2'])));
    }

    public function test_other_operations_are_not_mislabeled(): void
    {
        foreach (['Paiement', 'Réequilibrage', ''] as $type) {
            $this->assertNull(ReceiptOperation::label(new WalletTransaction(['type_transaction' => $type])));
        }
        $this->assertNull(ReceiptOperation::label(null));
    }
}
