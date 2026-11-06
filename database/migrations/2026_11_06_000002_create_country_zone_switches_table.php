<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_zone_switches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->string('zone', 30);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['country_id', 'zone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_zone_switches');
    }
};
