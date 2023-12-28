@extends('layouts.master')

@section('title', 'Details pays')

@section('content')
    <div>
        {{-- <livewire:detail-countrie> --}}
        @livewire('detail-countrie', ['key' => $key])
    </div>
@endsection
