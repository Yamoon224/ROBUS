@extends('errors.maxForced') 

@section('content')
    <div class="flex items-center justify-center h-screen bg-gray-100">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-red-600">Erreur 599</h1>
            <p class="text-lg text-gray-700 mt-4">Désolé, vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>
            <p class="text-lg text-gray-700 mt-4">Veuillez contacter l'administrateur pour obtenir de l'aide.</p>
            <h1>Application Hors Ligne</h1>
        <p>{{ $message ?? 'L’application est actuellement hors ligne pour maintenance.' }}</p>
        </div>
    </div>
@endsection
