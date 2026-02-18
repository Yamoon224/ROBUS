 @if ($item->provider_type == 1)
     <div class="amount-section">
         <div class="amount-label">Montant envoyé</div>
         <div class="flex items-baseline">
             <span class="amount-value">
                 {{ number_format($item->amount, 2, ',', ' ') ?? '0,00' }}
             </span>
             <span class="amount-currency">
                 {{ $item->wallet->country->currencie->code ?? null }}
             </span>
         </div>
     </div>
 @else
     <div class="amount-section">
         <div class="amount-label">Montant envoyé</div>
         <div class="flex items-baseline">
             <span class="amount-value">
                 {{ number_format($item->amount, 2, ',', ' ') ?? '0,00' }}
             </span>
             <span class="amount-currency">
                  {{ $item->wallet->currencie->code ?? null }}
             </span>
         </div>
     </div>
 @endif
 <!-- Montant reçu -->
 <div class="amount-section">
     <div class="amount-label">Montant reçu</div>
     <div class="amount-received">
         <div class="flex items-baseline justify-between">
             <span class="amount-received-value">
                 {{ number_format($item->amount_received, 2, ',', ' ') ?? '0,00' }}
             </span>
             <span class="amount-received-currency">
                 {{ $item->country->currencie->code ?? null }}
             </span>
         </div>
     </div>
 </div>

 <!-- Montant reçu -->
 <div class="amount-section">
     <div class="amount-label">Montant en {{ $usersCountry ?? null }} </div>
     <div class="amount-value-received">
         <div class="flex items-baseline justify-between">
             <span class="amount-value-received-value">
                 @isset($valeur_amount_receveid)
                     @if ($valeur_amount_receveid !== null)
                         {{ number_format($valeur_amount_receveid, 2, ',', ' ') }}
                         {{ $usersCountry ?? '' }}
                     @else
                         Non défini
                     @endif
                 @else
                     Non défini
                 @endisset
             </span>
         </div>
     </div>
 </div>
 <!-- Solde -->
 <div class="amount-section">
     <div class="amount-label">Solde actuel</div>
     <div class="amount-balance">
         <div class="flex items-baseline justify-between">
             <span class="amount-balance-value">
                 {{ number_format($item->amount_solde, 2, ',', ' ') ?? '0,00' }}
             </span>
             <span class="amount-balance-currency">
                 {{ $item->wallet->currencie->code ?? 'EUR' }}
             </span>
         </div>
     </div>
 </div>
