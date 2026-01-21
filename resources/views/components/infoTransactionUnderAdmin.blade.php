   <!-- Badges de statut -->

   <td class="text-center w-40">
       @include('components.infoTypeDjamo')


   </td>
   <td class="w-56">

       <div class="amount-section">
           <div class="amount-label">Montant </div>
           <div class="amount-received">
               <div class="flex items-baseline justify-between">
                   <span class="amount-received-value">
                       {{ number_format($item->amount_distributer, 2, ',', ' ') ?? '0,00' }}
                   </span>
                   <span class="amount-received-currency">
                       {{ $item->sidUser->wallet->currencie->code ?? 'CFA' }}

                   </span>
               </div>
           </div>
       </div>
       <div class="text-slate-500  mt-0.5">
           <span
               class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
               {{ $item->description_distributer ?? null }}
           </span>
       </div>

   </td>

   <td class="w-40">
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
           @elseif ($user && $user->is_company == 1)
               <div class="user-badge user-badge--compagnie">
                   Société
               </div>
           @elseif ($user && $user->is_api == 1)
               <div class="user-badge user-badge--compagnie">
                   Integrateur
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

       <!-- Localisation -->
       <div class="user-location">
           {{ $item->sidUser->country->name ?? 'Pays inconnu' }}
           •
           {{ $item->sidUser->city->name ?? 'Ville inconnue' }}
       </div>

   </td>
