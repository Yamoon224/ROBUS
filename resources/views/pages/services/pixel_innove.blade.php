@extends('layouts.master')

@section('title', ($mode ?? 'mobile_money') === 'uv_robus' ? 'Pixel Innov UV Robus' : 'Pixel Innov Mobile Money')

@section('content')
    <div>
        <livewire:pixel-innove-services :mode="$mode ?? 'mobile_money'" />
    </div>
@endsection
