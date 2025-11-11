   <!-- Badges de statut -->
   @php
       $user = $item->sidUser ?? null;
   @endphp
  
   <!-- Localisation -->
   <div class="user-location">
       {{ $item->sidUser->country->name ?? 'Pays inconnu' }}
       •
       {{ $item->sidUser->city->name ?? 'Ville inconnue' }}
   </div>
