<?php

namespace App\Http\Livewire\Integrations;

use Livewire\Component;
use App\Models\IntegrationClient;
use App\Models\Sid\SidUser;

class IntegrationApi extends Component
{
    public function render()
    {
        $integrationClient = IntegrationClient::where('sid_user_id', propect()->id)->first();
        $prospect = SidUser::where('id', propect()->id)->first();

        return view('livewire.integrations.integration-api' , [
            'integrationClient' => $integrationClient,
            'prospect' => $prospect,
        ]);
    }
}
