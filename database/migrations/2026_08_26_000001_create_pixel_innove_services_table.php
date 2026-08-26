<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pixel_innove_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
            $table->foreignId('provider_type_id')->constrained('provider_types')->cascadeOnDelete();
            $table->foreignId('provider_id')->constrained('providers')->cascadeOnDelete();
            $table->string('name');
            $table->string('operation_type');
            $table->unsignedInteger('service_id');
            $table->decimal('fixed_fee', 12, 2)->nullable();
            $table->decimal('commission_percent', 5, 2)->default(1.90);
            $table->boolean('status')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['provider_id', 'country_id', 'operation_type'], 'pixel_provider_country_operation_unique');
            $table->unique('service_id', 'pixel_service_id_unique');
            $table->index(['country_id', 'provider_type_id', 'status'], 'pixel_country_type_status_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pixel_innove_services');
    }
};
