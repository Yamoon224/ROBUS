@extends('layouts.master')

@section('title', 'Ajouter des contacts')

@section('content')
    <div>
        {{-- <livewire:detailcontacts :id="$id"> --}}
        @livewire('detailcontacts', ['key' => $key])
    </div>
@endsection
