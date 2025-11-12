   <!-- Badges de statut -->
@php
    use App\Models\Wallets\Wallet;
    $wallet = Wallet::where('wallet_id', $item->account_wallet)->first();
    $user = $wallet ? $wallet->sidUser : null;
@endphp

  <span class="bg-success w-60 text-white fs-5 px-1 py-1 rounded">
      Bénéficiaire du rechargement
   </span>
   <div class="user-badges-container mt-3">
       @if ($user && $user->is_under_distributer == 1)
           <div class="user-badge user-badge--distributor">
               Sous-distributeur
           </div>
       @elseif ($user && $user->is_custumers == 1)
           <div class="user-badge user-badge--customer">
               Client
           </div>
       @elseif ($user && $user->is_company == 1)
           <div class="user-badge user-badge--compagnie">
               Société
           </div>
       @else
           <div class="user-badge user-badge--admin">
               Administrateur
           </div>
       @endif

       <!-- Numéro de téléphone -->
       <div class="user-phone">
           {{ $user->phone ?? 'Non renseigné' }}
       </div>
   </div>

   <!-- Nom complet -->
   <div class="user-name">
       {{ $user->firstname ?? '' }}
       {{ $user->lastname ?? '' }}
   </div>

   <!-- Localisation -->
   <div class="user-location">
       {{ $user->country->name ?? 'Pays inconnu' }}
       •
       {{ $user->city->name ?? 'Ville inconnue' }}
   </div>
