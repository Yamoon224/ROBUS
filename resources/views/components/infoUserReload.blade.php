@php
    use App\Models\Wallets\Wallet;
   $wallet_info = walletByNumber($item->account_wallet);
    $user = $wallet_info?->sidUser;
@endphp

  <span class="bg-success w-60 text-white fs-5 px-1 py-1 rounded">
      Bénéficiaire du rechargement
   </span>
   <div class="user-badges-container mt-6">
       @if ($wallet_info?->sidUser && $wallet_info?->sidUser?->is_under_distributer == 1)
           <div class="user-badge user-badge--distributor">
               Sous-distributeur
           </div>
       @elseif ($wallet_info?->sidUser && $wallet_info?->sidUser?->is_custumers == 1)
           <div class="user-badge user-badge--customer">
               Client
           </div>
       @elseif ($wallet_info?->sidUser && $wallet_info?->sidUser?->is_company == 1)
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
