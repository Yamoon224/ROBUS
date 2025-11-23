   <!-- Badges de statut -->
   @php
       $user = $item ?? null;
   @endphp
   <div class="user-badges-container">
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
       @elseif ($user && $user->is_master == 1)
           <div class="user-badge user-badge--compagnie">
               Masteur
           </div>
     @elseif ($user && $user->is_api == 1)
           <div class="user-badge user-badge--compagnie">
                Intégrateur API
           </div>
       @else
           <div class="user-badge user-badge--admin">
               Administrateur
           </div>
       @endif

       <!-- Numéro de téléphone -->
       <div class="user-phone">
           {{ $item->phone ?? 'Non renseigné' }}
       </div>
   </div>

   <!-- Nom complet -->
   <div class="user-name">
       {{ $item->firstname ?? '' }}
       {{ $item->lastname ?? '' }}
   </div>

   <!-- Localisation -->
   <div class="user-location">
       {{ $item->country->name ?? 'Pays inconnu' }}
       •
       {{ $item->city->name ?? 'Ville inconnue' }}
   </div>
