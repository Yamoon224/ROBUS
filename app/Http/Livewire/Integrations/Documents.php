<?php

namespace App\Http\Livewire\Integrations;

use Livewire\Component;
use App\Models\IntegrationClient;

class Documents extends Component
{
    public function render()
    {
        $integrationClient = IntegrationClient::where('sid_user_id', propect()->id)->first();

        $documentsCollection = collect([
            ['name' => 'Front CNI', 'url' => $integrationClient->front_document ?? null, 'status' => 'Validé'],
            ['name' => 'Verso CNI', 'url' => $integrationClient->piece_document ?? null, 'status' => 'Validé'],
            ['name' => 'Juridict Form', 'url' => $integrationClient->juridict_form ?? null, 'status' => 'Validé'],
        ])->filter(fn($doc) => $doc['url']); 

        // Calcul des statistiques
        $validatedCount = $documentsCollection->where('status', 'Validé')->count();
        $pendingCount   = $documentsCollection->where('status', 'En attente')->count();
        $rejectedCount  = $documentsCollection->where('status', 'Rejeté')->count();
        $totalCount     = $documentsCollection->count();

        return view('livewire.integrations.documents', [
            'documents'      => $integrationClient,
            'validatedCount' => $validatedCount,
            'pendingCount'   => $pendingCount,
            'rejectedCount'  => $rejectedCount,
            'totalCount'     => $totalCount,
        ]);
    }
}
