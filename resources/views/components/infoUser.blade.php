   <!-- Badges de statut -->
   <div class="user-badges-container">
       @if ($item->wallet->sidUser->is_under_distributer == 1)
           <div class="user-badge user-badge--distributor">
               Sous-distributeur
           </div>
       @elseif($item->wallet->sidUser->is_custumers == 1)
           <div class="user-badge user-badge--customer">
               Client
           </div>
       @else
           <div class="user-badge user-badge--admin">
               Administrateur
           </div>
       @endif

       <!-- Numéro de téléphone -->
       <div class="user-phone">
           {{ $item->wallet->sidUser->phone ?? 'Non renseigné' }}
       </div>
   </div>

   <!-- Nom complet -->
   <div class="user-name">
       {{ $item->wallet->sidUser->firstname ?? '' }}
       {{ $item->wallet->sidUser->lastname ?? '' }}
   </div>

   <!-- Localisation -->
   <div class="user-location">
       {{ $item->wallet->sidUser->country->name ?? 'Pays inconnu' }}
       •
       {{ $item->wallet->sidUser->city->name ?? 'Ville inconnue' }}
   </div>
