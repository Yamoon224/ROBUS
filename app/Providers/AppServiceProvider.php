<?php

namespace App\Providers;

use App\Services\SilentGeneratorFactory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Http\Services\CeldingValeurService;
use L5Swagger\GeneratorFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CeldingValeurService::class, function () {
            return new CeldingValeurService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->publishes([
            __DIR__ . '/../../resources/views/errors' => resource_path('views/errors'),
        ], 'errors');

        // Remplace la factory swagger par une version avec logger silencieux
        // pour éviter que les warnings d'annotations fassent planter la génération.
        $this->app->bind(GeneratorFactory::class, SilentGeneratorFactory::class);
    }
}
