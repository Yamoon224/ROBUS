@extends('errors.maxForced') 

@section('content')
    <div class="flex items-center justify-center h-screen bg-gray-100">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-red-600">Erreur 599</h1>
            <p class="text-lg text-gray-700 mt-4">Désolé, vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>
            <p class="text-lg text-gray-700 mt-4">Veuillez contacter l'administrateur pour obtenir de l'aide.</p>
            <a href="{{ url()->previous() }}" class="text-blue-500 hover:text-blue-700 mt-8 block">Revenir en arrière</a>
        </div>
    </div>
@endsection
