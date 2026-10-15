<?php

namespace Tests\Feature;

use App\Models\Wallets\WalletTransaction;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class ReceiptOperationRenderTest extends TestCase
{
    public function test_receipt_component_prints_the_explicit_nature(): void
    {
        foreach (['Depot' => 'Dépôt', 'Retrait' => 'Retrait'] as $type => $label) {
            $html = Blade::render('<x-receipt-operation :transaction="$transaction" />', [
                'transaction' => new WalletTransaction(['type_transaction' => $type]),
            ]);
            $this->assertStringContainsString('Nature de la transaction : ' . $label, $html);
        }
    }
}
