  <div wire:ignore.self id="bank-send" class="tab-pane {{ $activeTab === 'bank-send' ? 'active show' : '' }}" role="tabpanel" aria-labelledby="bank-send-tab">
      <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
          <div class="hidden md:block mx-auto text-slate-500"></div>
          <div class="hidden md:block mx-auto text-slate-500"></div>
          <div class="hidden md:block mx-auto text-slate-500"></div>


          <div class="hidden md:block mx-auto text-slate-500"></div>

          <div class="input-group input-group-md hidden" style="margin-top: 13px;width: 250px;   ">
              <div class="w-56 relative text-slate-500">

                  <input type="text" name="table_search" class="form-control  w-56 box pr-10"
                      style="height: 3rem; background-color: #eff3f7;" placeholder="Rechercher">
                  <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                      data-feather="searchPaiment"></i>
              </div>
          </div>
      </div>
      <div class="grid grid-cols-12 gap-6 mt-6">
          <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
              <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                  <h2 class="font-medium text-base mr-auto">
                      Liste des transactions des bank sends
                  </h2>
              </div>
              <div class=" py-5">
                  <div class="px-5">
                      <div class="overflow-x-auto">
                          <table class="table table-bordered">
                              <thead class="table-dark">
                                  <tr>
                                      <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Opérateur
                                      </th>
                                      <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">Expéditeur
                                      </th>

                                      <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                          Montant demandé
                                      </th>
                                      <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                          Info Transaction
                                      </th>
                                      <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                          Statut </th>
                                      @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                          <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                              Actions
                                          </th>
                                      @endif
                                  </tr>
                              </thead>
                              <tbody>
                                  @if ($bankSends->count() > 0)

                                      @foreach ($bankSends as $key => $item)
                                          @php
                                              $userCurrencyValue = (float) sabdy()->country->currencie->value;
                                              $itemCurrencyValue = (float) $item->country->currencie->value;
                                              $amountReceived = (float) $item->amount_received;

                                              /**
                                               * Cas 1 : le pays de la transaction est CFA (107)
                                               * → montant stocké en CFA → convertir vers devise utilisateur
                                               */
                                              if ($item->country->id == 107) {
                                                  $valeur_amount_receveid =
                                                      $userCurrencyValue > 0
                                                          ? $amountReceived / $userCurrencyValue
                                                          : null;
                                              } /**
                                               * Cas 2 : le pays de la transaction n'est PAS CFA
                                               * → convertir vers CFA puis vers devise utilisateur
                                               */ else {
                                                  // Étape 1 : montant → CFA
                                                  $amountInCfa = $amountReceived * $itemCurrencyValue;

                                                  // Étape 2 : CFA → devise utilisateur
                                                  $valeur_amount_receveid =
                                                      $userCurrencyValue > 0 ? $amountInCfa / $userCurrencyValue : null;
                                              }
                                          @endphp

                                          <tr class="intro-x">
                                              <td class="text-center w-40">

                                                  <div
                                                      class="px-3 py-2 text-dark btn-pink dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
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
                                              <td>
                                                  <div class="mb-3">
                                                      <div
                                                          class="px-3 py-2 text-dark bg-blue-900 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                          <div class="fc-event-title-container" style="color: white;">
                                                              <div class="fc-event-title fc-sticky">
                                                                  {{ $item->country->name ?? null }}
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  @include('components.walletBalanceInter')

                                              </td>
                                              <td class="w-40">

                                                  <div class="mb-3">
                                                      @include('components.info')

                                                  </div>

                                                  @if ($item->updated_by == null)
                                                      <div class="user-name">
                                                          {{ substr($item->phone ?? null, 0, 6) }}
                                                          <p class="font-medium whitespace-nowrap">
                                                              Nom& Prenom(S) :
                                                              {{ $item->name_complet ?? $item->wallet->sidUser->firstname }}

                                                          </p>
                                                          <p class="font-medium whitespace-nowrap mt-1">
                                                              N0 : {{ $item->num_piece ?? null }}
                                                          </p>
                                                          <p class="font-medium whitespace-nowrap">
                                                              Date validité :
                                                              {{ $item->validated_date ?? null }}
                                                          </p>
                                                          @if ($item->provider_id == 99)
                                                              <p class="font-medium whitespace-nowrap">
                                                                  Nom du beneficiaire : {{ $item->name_benef ?? null }}
                                                                  Numero Compte banque :
                                                                  {{ $item->number_compte ?? null }}
                                                                  Nom banque : {{ $item->name ?? null }}

                                                              </p>
                                                          @endif
                                                      </div>


                                                      <div  wire:key="action-bank-send-{{ $item->id }}" class="flex items-center justify-center text-success mt-3">

                                                          @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                              <x-take-action-button action="ChooseUpdateBankSend"
                                                                  :itemId="$item->id" label="Je prends en charge"
                                                                  speed="ultra-fast" color="warning" />
                                                          @endif

                                                      </div>
                                                  @else
                                                      <div class="user-name">
                                                          {{ $item->phone ?? null }}
                                                          <p class="font-medium whitespace-nowrap">
                                                              Nom& Prenom(S) :
                                                              {{ $item->name_complet ?? $item->wallet->sidUser->firstname }}

                                                          </p>
                                                          <p class="font-medium whitespace-nowrap mt-1">
                                                              N0 : {{ $item->num_piece ?? null }}
                                                          </p>
                                                          <p class="font-medium whitespace-nowrap">
                                                              Date validité :
                                                              {{ $item->validated_date ? date('d-M-y', strtotime($item->validated_date)) : null }}
                                                          </p>
                                                          @if ($item->provider_id == 99)
                                                              <p class="font-medium whitespace-nowrap">
                                                                  Nom du bénéficiaire : {{ $item->name_benef ?? null }}

                                                              </p>
                                                              <p class="font-medium whitespace-nowrap">
                                                                  Numero Compte banque :
                                                                  {{ $item->number_compte ?? null }}

                                                              </p>
                                                              <p class="font-medium whitespace-nowrap">
                                                                  Nom banque : {{ $item->name ?? null }}

                                                              </p>
                                                          @endif
                                                      </div>
                                                  @endif

                                              </td>

                                              <td class="w-40">
                                                  @if ($item->status == 'PENDING')
                                                      <div class="flex items-center justify-center text-pending">
                                                          <span
                                                              class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                              En-attente
                                                          </span>

                                                      </div>
                                                  @elseif ($item->status == 'ERROR')
                                                      <div class="flex items-center justify-center text-danger">
                                                          <div class="flex items-center justify-center text-pending">
                                                              <span
                                                                  class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                  Refusé
                                                              </span>

                                                          </div>
                                                      </div>
                                                      <div class="flex items-center justify-center text-pending">
                                                          <span
                                                              class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                              style="color: white;">
                                                              Traiter
                                                          </span>

                                                      </div>
                                                  @elseif($item->status == 'REFUND')
                                                      <div class="flex items-center justify-center text-primary">
                                                          <div class="flex items-center justify-center text-pending">
                                                              <span
                                                                  class="alert alert-primary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                                  Remboursé
                                                              </span>

                                                          </div>
                                                      </div>
                                                      <div class="flex items-center justify-center text-pending">
                                                          <span
                                                              class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                              style="color: white;">
                                                              Traiter
                                                          </span>

                                                      </div>
                                                  @else
                                                      <div class="flex items-center justify-center text-success">
                                                          <div class="flex items-center justify-center text-pending">
                                                              <span
                                                                  class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                  style="color: white;">
                                                                  Accepté
                                                              </span>

                                                          </div>

                                                      </div>
                                                      <div class="flex items-center justify-center text-pending">
                                                          <span
                                                              class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                              style="color: white;">
                                                              Traiter
                                                          </span>

                                                      </div>
                                                  @endif
                                              </td>

                                              <td class="w-56">
                                                  <div class="flex justify-center items-center">
                                                      @if ($item->updated_by != null)
                                                          @if (
                                                              ($item->status == 'PENDING' && $item->updated_by == Auth::guard('web')->user()->id) ||
                                                                  ($item->status === 'PROCESSING' && $item->updated_by == Auth::guard('web')->user()->id))
                                                              @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                  <a wire:click="editBankSend({{ $item->id }}, 'update-bankSend-modal')"
                                                                      class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                      href="javascript:;" title="Modifier">

                                                                      <svg class="w-4 h-4 mr-1" fill="none"
                                                                          stroke="currentColor" viewBox="0 0 24 24">
                                                                          <path stroke-linecap="round"
                                                                              stroke-linejoin="round" stroke-width="2"
                                                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                      </svg>
                                                                      Traité maintenant
                                                                  </a>
                                                              @endif
                                                          @elseif ($item->status == 'SUCCESS' && $item->updated_by == Auth::guard('web')->user()->id)
                                                              @if (Route::has('bankSends.invoice.print') && Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                  <a target="_bank"
                                                                      href="{{ route('bankSends.invoice.print', $item->id) }}"
                                                                      class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                      title="Facture">
                                                                      Facture
                                                                  </a>
                                                              @endif
                                                          @else
                                                              <div class="flex items-center justify-center text-dark">
                                                                  <div
                                                                      class="flex items-center justify-center text-pending">
                                                                      <span
                                                                          class="alert  alert-warning inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                          style="color: white;">
                                                                          Déjà choisir
                                                                      </span>

                                                                  </div>
                                                              </div>
                                                          @endif
                                                      @else
                                                          <div class="flex items-center justify-center text-dark">
                                                              <div
                                                                  class="flex items-center justify-center text-warring">
                                                                  <span
                                                                      class="alert alert-dark show mb-2 inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                    text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                                      style="color: white;">
                                                                      <svg xmlns="http://www.w3.org/2000/svg"
                                                                          width="24" height="24"
                                                                          viewBox="0 0 24 24" fill="none"
                                                                          stroke="currentColor" stroke-width="2"
                                                                          stroke-linecap="round"
                                                                          stroke-linejoin="round"
                                                                          icon-name="alert-octagon"
                                                                          data-lucide="alert-octagon"
                                                                          class="lucide lucide-alert-octagon w-6 h-6 mr-2">
                                                                          <polygon
                                                                              points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2">
                                                                          </polygon>
                                                                          <line x1="12" y1="8"
                                                                              x2="12" y2="12"></line>
                                                                          <line x1="12" y1="16"
                                                                              x2="12.01" y2="16"></line>
                                                                      </svg>
                                                                      Pas encore choisir
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
                      </div>
                  </div>
              </div>
          </div>
          {{-- <script>
              window.addEventListener('DOMContentLoaded', function() {
                  Echo.channel('update_all_wallet').listen(
                      '.wallet_update_all',
                      (e) => {
                          window.livewire.emit('notifyNewTransfers:' + e.id);
                      },
                  )
              });
          </script> --}}
          <!-- BEGIN: Pagination -->
          <div class="intro-y col-span-12 flex flex-wrap sm:flex-row sm:flex-nowrap items-center mt-6">
              <nav class="w-full sm:w-auto sm:mr-auto">
                  <ul class="pagination">
                      {{ $bankSends->links('vendor.livewire.tailwind-paiements') }}
                  </ul>
              </nav>
          </div>
          <!-- END: Pagination -->
      </div>
  </div>
