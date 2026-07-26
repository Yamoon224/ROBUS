@extends('layouts.master')

@section('title', 'Rechargement UV')

@section('content')
    <div>
        @php($reportUser = sabdy())
        @if ($reportUser && ($reportUser->is_admin || $reportUser->hasPermissionTo('rapport-journalier')))
            <div class="intro-y flex justify-end mt-5 mb-4">
                <a href="{{ route('reports.daily') }}" class="btn btn-primary">
                    <i data-feather="bar-chart-2" class="w-4 h-4 mr-2"></i>
                    Rapport journalier
                </a>
            </div>
        @endif

        <livewire:reloaduv>
    </div>
@endsection
