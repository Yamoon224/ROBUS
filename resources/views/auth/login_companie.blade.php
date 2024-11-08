@extends('layouts.app')

@section('title', 'Connectez-vous')

@php

    $status = appDispoRobus(App\Models\AppCompanyStatus::class);
@endphp

@section('content')
    <div>
        <livewire:login-company>

    </div>

@endsection
