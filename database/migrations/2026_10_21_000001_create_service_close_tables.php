<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SERVICES = ['mobile_zone', 'international_zone'];

    private const DAYS = [
        1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi',
        5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche',
    ];

    public function up(): void
    {
        // Plages horaires d'ouverture par service et par jour (status = règle active)
        Schema::create('service_close_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('service', 40);
            $table->unsignedTinyInteger('day_number');
            $table->string('day_of_week');
            $table->time('time_start')->default('00:00:00');
            $table->time('time_end')->default('23:59:59');
            $table->text('description')->nullable();
            $table->boolean('status')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->unique(['service', 'day_number']);
        });

        // Activation / désactivation manuelle : prime sur les plages horaires
        // manual_mode : null = automatique (horaires), 'open' = forcé ouvert, 'closed' = forcé fermé
        Schema::create('service_close_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('service', 40)->unique();
            $table->string('manual_mode', 10)->nullable();
            $table->text('manual_message')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        $now = now();
        foreach (self::SERVICES as $service) {
            DB::table('service_close_settings')->insert([
                'service' => $service, 'created_at' => $now, 'updated_at' => $now,
            ]);

            foreach (self::DAYS as $number => $name) {
                DB::table('service_close_schedules')->insert([
                    'service' => $service,
                    'day_number' => $number,
                    'day_of_week' => $name,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_close_settings');
        Schema::dropIfExists('service_close_schedules');
    }
};
