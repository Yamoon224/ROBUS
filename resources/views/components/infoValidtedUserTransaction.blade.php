<!-- Utilisateur ayant traité la transaction -->
@php
$userId = $item->updated_by ?? $item->created_by;
$user = $item->updated_by !== null
? $item->updatedByUser
: $item->createdByUser;

$fullName = $user
? trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''))
: null;
@endphp

@if ($user)
<span class="bg-success w-25 text-white fs-5 px-2 py-1 rounded">
    Traité par
</span>

<div class="user-badges-container mt-3">
    @if ($user->is_under_distributer == 1)
    <div class="user-badge user-badge--distributor">
        Sous-distributeur
    </div>
    @elseif ($user->is_custumers == 1)
    <div class="user-badge user-badge--customer">
        Client
    </div>
    @elseif ($user->is_supervisor == 1)
    <div class="user-badge user-badge--customer">
        Superviseurs
    </div>
    @else
    <div class="user-badge user-badge--admin">
        Administrateur
    </div>
    @endif

    <div class="user-phone">
        {{ $user->phone ?? 'Non renseigné' }}
    </div>
</div>

<div class="user-name">
    {{ $fullName ?: 'Nom non renseigné' }}
</div>

<div class="user-location">
    {{ $user->country?->name ?? 'Pays inconnu' }}
    •
    {{ $user->city?->name ?? 'Ville inconnue' }}
</div>
@elseif ($userId)
<span class="bg-warning text-dark fs-5 px-2 py-1 rounded">
    Utilisateur introuvable
</span>
@else
<span class="bg-warning text-dark fs-5 px-2 py-1 rounded">
    Non traité
</span>
@endif