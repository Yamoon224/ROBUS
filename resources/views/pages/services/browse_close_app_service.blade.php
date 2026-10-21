@extends('layouts.master')

@section('title', 'Horaires du service')

@section('content')
    <div>
        <livewire:close-service-schedule :service="$service">
    </div>
@endsection
