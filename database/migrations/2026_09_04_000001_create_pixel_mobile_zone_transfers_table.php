<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pixel_mobile_zone_transfers', function (Blueprint $table) {
            $table->id();

            // Expéditeur
            $table->unsignedBigInteger('sender_pixel_service_id')->comment('ID interne PixelInnoveService (Cash Out)');
            $table->string('sender_phone', 20);
            $table->string('sender_country_iso2', 5)->nullable();

            // Destinataire
            $table->unsignedBigInteger('recipient_pixel_service_id')->comment('ID interne PixelInnoveService (Cash In)');
            $table->string('recipient_phone', 20);
            $table->string('recipient_country_iso2', 5)->nullable();

            // Montants
            $table->decimal('amount', 14, 2)->comment('Montant demandé par l\'expéditeur');
            $table->decimal('sender_fee', 14, 2)->default(0)->comment('Frais leg Cash Out');
            $table->decimal('recipient_fee', 14, 2)->default(0)->comment('Frais leg Cash In');
            $table->decimal('total_fee', 14, 2)->default(0);
            $table->decimal('amount_to_send', 14, 2)->comment('Montant envoyé au destinataire (=amount - sender_fee)');
            $table->decimal('amount_received', 14, 2)->comment('Montant net reçu par le destinataire');
            $table->string('currency', 10)->default('XOF');

            // Pixel Innov — leg Cash Out (expéditeur)
            $table->string('sender_transaction_id')->nullable()->comment('Transaction ID Pixel Innov Cash Out');
            $table->string('sender_status', 20)->default('PENDING')->comment('PENDING | SUCCESS | FAILED');

            // Pixel Innov — leg Cash In (destinataire)
            $table->string('recipient_transaction_id')->nullable()->comment('Transaction ID Pixel Innov Cash In');
            $table->string('recipient_status', 20)->default('PENDING')->comment('PENDING | SUCCESS | FAILED');

            // Statut global
            $table->string('status', 20)->default('PENDING')
                ->comment('PENDING | PROCESSING (cashout ok) | SUCCESS | FAILED | REFUNDED');

            // Wallet du client Robus (pour référence et commissions)
            $table->unsignedBigInteger('wallet_id')->nullable();

            // Réponses brutes
            $table->json('raw_response_sender')->nullable();
            $table->json('raw_response_recipient')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index('sender_transaction_id');
            $table->index('recipient_transaction_id');
            $table->index('status');
            $table->index('wallet_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pixel_mobile_zone_transfers');
    }
};
