   <!-- Badges de statut -->

   <div class="transaction-badges-container">
      

       <div class="transaction-badge transaction-badge--distributor text-white text-center">

         @if ($item->choose_transaction == 1)
            Transfert
         @elseif ($item->choose_transaction == 2)
            Retrait
         @else

          {{ $item->choose_transaction}}

         @endif
       </div>

   </div>
