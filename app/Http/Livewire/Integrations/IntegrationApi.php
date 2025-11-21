<?php

namespace App\Http\Livewire\Integrations;

use Livewire\Component;
use App\Models\IntegrationClient;

class IntegrationApi extends Component
{
    public function render()
    {
        $integrationClient = IntegrationClient::where('sid_user_id', propect()->id)->first();

        return view('livewire.integrations.integration-api' , [
            'integrationClient' => $integrationClient,
        ]);
    }
}
