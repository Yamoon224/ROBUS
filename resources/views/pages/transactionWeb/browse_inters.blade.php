@extends('layouts.master_web')

@section('title', 'Transfert International Zone')

@section('content')
    {{-- Même interface que le transfert Mobile Zone, mais le pays expéditeur doit différer du pays destinataire --}}
    @include('pages.transactionWeb.partials.mzt-transfer', [
        'mztDistinct'  => true,
        'mztTitle'     => 'Transfert International Zone',
        'mztSubtitle'  => "Envoyez de l'argent vers un autre pays de la zone",
        'mztIntro'     => "Choisissez le pays et l'opérateur expéditeur, puis un pays destinataire différent.",
    ])
@endsection
