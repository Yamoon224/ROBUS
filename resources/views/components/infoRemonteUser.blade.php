   <!-- Badges de statut -->
   @php
       $user = $item->sidUser ?? null;
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
       @elseif ($user && $user->is_master == 1)
           <div class="user-badge user-badge--customer">
               Master
           </div>
       @elseif ($user && $user->is_supervisor == 1)
           <div class="user-badge user-badge--customer">
               Superviseur
           </div>
       @else
           <div class="user-badge user-badge--admin">
               Administrateur
           </div>
       @endif

       <!-- Numéro de téléphone -->
       <div class="user-phone">
           {{ $item->sidUser->phone ?? 'Non renseigné' }}
       </div>
   </div>

   <!-- Nom complet -->
   <div class="user-name">
       {{ $item->sidUser->firstname ?? '' }}
       {{ $item->sidUser->lastname ?? '' }}
   </div>

