<?php

namespace Tests\Feature;

use App\Models\CountryService;
use App\Models\Providers\Provider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CountryServiceActivationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_country_activation_controls_provider_availability(): void
    {
        $link = CountryService::query()
            ->whereHas('provider', fn ($query) => $query->where('status', true))
            ->first();

        if (!$link) {
            $this->markTestSkipped('Aucune association méthode-pays disponible.');
        }

        $link->update(['status' => false]);

        $this->assertFalse($link->fresh()->status);
        $this->assertFalse($this->providerIsAvailable($link));

        $link->update(['status' => true]);

        $this->assertTrue($link->fresh()->status);
        $this->assertTrue($this->providerIsAvailable($link));
    }

    private function providerIsAvailable(CountryService $link): bool
    {
        return Provider::query()
            ->whereKey($link->provider_id)
            ->availableInCountry($link->country_id)
            ->exists();
    }
}
