   <table id="table_orders" class="table table-bordered">
       <thead class="table-dark">
           <tr>
               <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
               </th>
               <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
               </th>
               <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                   Montant à recevoir
               </th>

               <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                   Info Transactions
               </th>
               <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                   Statut
               </th>
               <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                   Actions
               </th>
           </tr>
       </thead>
       <tbody>
           @if ($transfer_historicals->count() > 0)
               @foreach ($transfer_historicals as $key => $item)
                   <tr class="intro-x">
                       <td class="text-center w-40 ">

                           <div
                               class="px-3 py-2 text-dark btn-primary dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                               <div class="fc-event-title-container" style="color: white;">
                                   <div class="fc-event-title fc-sticky">
                                       {{ $item->provider->name ?? null }}

                                   </div>
                               </div>
                           </div>

                       </td>
                       <td class="user-info-card">
                           @include('components.infoUser')

                       </td>
                       @php

                           $senderCurrency = sabdy()->country?->currencie?->value;
                           $receiverCurrency = $item->wallet?->sidUser?->country?->currencie?->value;

                           if (sabdy()->country?->id == 107 && $receiverCurrency !== null) {
                               if ($senderCurrency > $receiverCurrency) {
                                   $valeur_amount_receveid = (float) $item->amount_received * (float) $senderCurrency;
                               } elseif ($senderCurrency == $receiverCurrency) {
                                   $valeur_amount_receveid = $item->amount_received;
                               } elseif ($senderCurrency < $receiverCurrency) {
                                   $valeur_amount_receveid = (float) $item->amount_received * (float) $receiverCurrency;
                               }
                           } else {
                               $valeur_amount_receveid = (float) $item->amount_received;
                           }
                       @endphp
                       <td>
                           @include('components.walletBalanceConvertion')

                       </td>
                       <td class="w-40">
                           <div class="mb-3">
                               @include('components.info_treatment')
                           </div>

                           @if ($item->type_transaction == 'Depot')
                               <span
                                   class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                                text-indigo-100 bg-indigo-700 rounded"
                                   style="color: white;">
                                   Depot
                               </span>
                           @elseif ($item->type_transaction == 'Transfert')
                               <span
                                   class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                        text-indigo-100 bg-indigo-700 rounded"
                                   style="color: white;">
                                   transfert
                               </span>
                           @elseif ($item->type_transaction == 'Réequilibrage')
                               <span
                                   class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                         text-indigo-100 bg-indigo-700 rounded"
                                   style="color: white;">
                                   Réequilibrage
                               </span>
                           @else
                               <span
                                   class="alert alert-success inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none
                                                        text-indigo-100 bg-indigo-700 rounded"
                                   style="color: white;">
                                   Retrait
                               </span>
                           @endif

                           <div class="user-name">
                               <svg xmlns="http://www.w3.org/2000/svg"
                                   class="h-4 w-4 text-slate-400 group-hover:text-primary" fill="none"
                                   viewBox="0 0 24 24" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round"
                                       d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                               </svg>
                               <span class="font-mono text-sm text-slate-700 dark:text-slate-300  truncate">
                                   {{ $item->phone ?? null }} {{ $item->num_wallet ?? null }}
                               </span>
                           </div>
                       </td>

                       <td class="w-40">

                           <div class="mb-3">
                               @include('components.infoValidtedUser')
                           </div>

                           @include('components.info_status')

                       </td>
                       <td class="w-56">
                           <div class="flex justify-center items-center">
                               @if ($item->status == 'PENDING')
                                   <div class="flex items-center justify-center text-pending">
                                       <span
                                           class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                           En-attente
                                       </span>

                                   </div>
                               @elseif ($item->status == 'SUCCESS')
                                   <a target="_bank" href="{{ route('transfers.invoice.print', $item->id) }}"
                                       class="btn btn-primary mr-1 mb-2 btn-sm" title="Facture">
                                       Facture
                                   </a>
                               @else
                                   <div class="flex items-center justify-center text-dark">
                                       <div class="flex items-center justify-center text-pending">
                                           <span
                                               class="alert  alert-danger inline-flex items-center justify-center px-2 py-1 mb-3 
                                                              text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                               style="color: white;">
                                               Aucune
                                           </span>

                                       </div>
                                   </div>
                               @endif

                           </div>
                       </td>
                   </tr>
               @endforeach
           @else
               <tr>
                   <td colspan="7" class="text-center">Aucune donnée pour le moment</td>
               </tr>
           @endif
       </tbody>
   </table>
