<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pixel_mobile_zone_transfers', function (Blueprint $table) {
            // Solde Pixel Innov avant et après l'opération
            $table->decimal('pixel_wallet_before', 14, 2)->nullable()->after('currency')
                ->comment('Solde Pixel Innov avant le Cash Out');
            $table->decimal('pixel_wallet_after', 14, 2)->nullable()->after('pixel_wallet_before')
                ->comment('Solde Pixel Innov après le Cash In');

            // Remboursement expéditeur si Cash In échoue
            $table->string('refund_transaction_id')->nullable()->after('recipient_status')
                ->comment('Transaction ID Pixel Innov du remboursement vers l\'expéditeur');
            $table->string('refund_status', 20)->nullable()->after('refund_transaction_id')
                ->comment('PENDING | SUCCESS | FAILED');
            $table->timestamp('refunded_at')->nullable()->after('refund_status');
        });
    }

    public function down(): void
    {
        Schema::table('pixel_mobile_zone_transfers', function (Blueprint $table) {
            $table->dropColumn([
                'pixel_wallet_before',
                'pixel_wallet_after',
                'refund_transaction_id',
                'refund_status',
                'refunded_at',
            ]);
        });
    }
};
