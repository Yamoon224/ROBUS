   <!-- Badges de statut -->

   <div class="transaction-badges-container">
       {{-- @if ($item->type_transaction)
           
       @else
           <div class="transaction-badge transaction-badge--customer text-white text-center">
               Dépot
           </div>
       @endif --}}

       <div class="transaction-badge transaction-badge--distributor text-white text-center">
          {{ $item->type_transaction}}
       </div>

   </div>
