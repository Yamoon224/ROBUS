@extends('layouts.master_web')

@section('title', 'Transfert Mobile Zone')

@section('content')
    @include('pages.transactionWeb.partials.mzt-transfer', ['mztDistinct' => false])
@endsection
