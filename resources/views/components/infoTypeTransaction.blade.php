   <!-- Badges de statut -->

   <div class="transaction-badges-container">
       @if ($item->choose_transaction == 1)
           <div class="transaction-badge transaction-badge--distributor text-white text-center">
               Transferts
           </div>
       @else
           <div class="transaction-badge transaction-badge--customer text-white text-center">
               Dépot
           </div>
       @endif

   </div>

