@extends('layouts.app_master')

@section('title', 'Connectez-vous')
@php
    $currentDayOfWeek = Illuminate\Support\Carbon::now()->dayOfWeek;

    $open_apps = getCloseMasterRobusData();
@endphp

@section('content')
    @foreach ($open_apps as $index => $open)
        <div class="col-span-12 xl:col-span-6 ml-3 mr-3 mb-3">
            @if ($currentDayOfWeek == $open->id)
                <h2 class="text-lg font-medium truncate items-center text-xs mt-3 mr-6 ml-6 text-center whitespace-nowrap blinking style_app text-white"
                    id="box_{{ $index }}">
                    Robus est ouvert ce {{ $open->day_of_week ?? null }} de :
                    {{ $open->time_start ?? null }} a {{ $open->time_end ?? null }}
                </h2>
            @endif
        </div>
    @endforeach
    <div>
        <livewire:login-master>

    </div>

@endsection
