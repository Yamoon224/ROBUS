  <div wire:ignore.self id="paiement" class="tab-pane {{ $activeTab === 'paiement' ? 'active show' : '' }}" class="tab-pane" role="tabpanel" aria-labelledby="paiement-tab">
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
                      data-feather="search"></i>
              </div>
          </div>
      </div>
      <div class="grid grid-cols-12 gap-6 mt-6">
          <div class="intro-y box col-span-12" style="background-color: #eff3f7;">

              <div
                  class="flex items-center  border-b border-slate-200/60 dark:border-darkmode-400 bg-white dark:bg-darkmode-700">
                  <div class="flex items-center gap-3">
                      <div class="w-3 h-8 bg-orange-500 rounded-r-full"></div>
                      <div>
                          <h2 class="font-medium text-base mr-auto text-slate-900 dark:text-white text-xl">
                              Liste des paiements des factures
                          </h2>
                          <div class="flex items-center gap-4 mt-2 mb-2 ">
                              <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">
                                  Dernière mise à jour: {{ now()->format('d/m/Y H:i') }}
                              </span>
                              <span class="w-1 h-1 bg-slate-400 rounded-full"></span>
                              <span class="text-sm text-slate-600 dark:text-slate-400 font-medium">
                                  {{ $paiements->count() }} éléments
                              </span>
                          </div>
                      </div>
                  </div>
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
                                          Info Paiement
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
                                  @if ($paiements->count() > 0)
                                      @foreach ($paiements as $key => $item)
                                          <tr class="intro-x">
                                              <td class="text-center w-40">

                                                  <div
                                                      class="px-3 py-2 text-dark btn-success dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
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
                                                  @include('components.walletBalance')

                                              </td>
                                              <td class="w-60">
                                                  <div class="mb-3">
                                                      @include('components.info')
                                                  </div>
                                                  @if ($item->updated_by == null)
                                                      <div class="user-name">
                                                          <p class="font-medium whitespace-nowrap">
                                                              @if ($item->factureChoix_type == 1)
                                                                  <div
                                                                      class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                      <div class="fc-event-title-container"
                                                                          style="color: white;">
                                                                          <div class="fc-event-title fc-sticky">
                                                                              Facture
                                                                              prépayée</div>
                                                                      </div>
                                                                  </div>
                                                                  {{ substr($item->id_invoice ?? null, 0, 5) }}
                                                              @elseif($item->factureChoix_type == 2)
                                                                  <div
                                                                      class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                      <div class="fc-event-title-container"
                                                                          style="color: white;">
                                                                          <div class="fc-event-title fc-sticky">
                                                                              Facture
                                                                              normale</div>
                                                                      </div>
                                                                  </div>
                                                                  {{ substr($item->id_invoice ?? null, 0, 5) }}
                                                              @elseif($item->paiementChoix_phone == 1)
                                                                  <div
                                                                      class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                      <div class="fc-event-title-container"
                                                                          style="color: white;">
                                                                          <div class="fc-event-title fc-sticky">

                                                                              Numero de telephone</div>
                                                                      </div>
                                                                  </div>

                                                                  {{ substr($item->number_phone ?? null, 0, 6) }}
                                                              @elseif($item->paiementChoix_phone == 2)
                                                                  <div
                                                                      class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                      <div class="fc-event-title-container"
                                                                          style="color: white;">
                                                                          <div class="fc-event-title fc-sticky">
                                                                              Numero du decodeur
                                                                          </div>
                                                                      </div>
                                                                  </div>
                                                                  {{ substr($item->number_decoder, 0, 5) }}
                                                              @elseif ($item->paiementChoix_phone == null && $item->factureChoix_type == null)
                                                                  {{ substr($item->number_contract ?? null, 0, 5) }}
                                                              @endif

                                                          </p>
                                                      </div>


                                                      <div class="flex items-center justify-center text-success mt-3">

                                                          @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                              <x-take-action-button action="ChooseUpdatePayment"
                                                                  :itemId="$item->id" label="Je prends en charge"
                                                                  speed="ultra-fast" color="warning" />
                                                          @endif

                                                      </div>
                                                  @else
                                                      <div class="user-name">
                                                          <p class="font-medium whitespace-nowrap">
                                                              @if ($item->factureChoix_type == 1)
                                                                  <div
                                                                      class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                      <div class="fc-event-title-container"
                                                                          style="color: white;">
                                                                          <div class="fc-event-title fc-sticky">
                                                                              Facture
                                                                              prépayée</div>
                                                                      </div>
                                                                  </div>
                                                                  {{ $item->id_invoice ?? null }}
                                                              @elseif($item->factureChoix_type == 2)
                                                                  <div
                                                                      class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                      <div class="fc-event-title-container"
                                                                          style="color: white;">
                                                                          <div class="fc-event-title fc-sticky">
                                                                              Facture
                                                                              normale</div>
                                                                      </div>
                                                                  </div>
                                                                  {{ $item->id_invoice ?? null }}
                                                              @elseif($item->paiementChoix_phone == 1)
                                                                  <div
                                                                      class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                      <div class="fc-event-title-container"
                                                                          style="color: white;">
                                                                          <div class="fc-event-title fc-sticky">

                                                                              Numero de telephone</div>
                                                                      </div>
                                                                  </div>

                                                                  {{ $item->number_phone ?? null }}
                                                              @elseif($item->paiementChoix_phone == 2)
                                                                  <div
                                                                      class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                      <div class="fc-event-title-container"
                                                                          style="color: white;">
                                                                          <div class="fc-event-title fc-sticky">
                                                                              Numero du decodeur
                                                                          </div>
                                                                      </div>
                                                                  </div>
                                                                  {{ $item->number_decoder }}

                                                              @else
                                                                  {{ $item->number_contract ?? null }}
                                                              @endif
                                                          </p>
                                                      </div>
                                                  @endif
                                              </td>

                                              <td class="w-40">
                                                  @include('components.info_status')

                                              </td>
                                              <td class="w-40">
                                                  <div class="flex justify-center items-center">
                                                      @if ($item->updated_by != null)
                                                          @if (
                                                              ($item->status == 'PENDING' && $item->updated_by == Auth::guard('web')->user()->id) ||
                                                                  ($item->status === 'PROCESSING' && $item->updated_by == Auth::guard('web')->user()->id))
                                                              @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                  <a wire:click="editPaiement({{ $item->id }}, 'update-paiementInvoice-modal')"
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
                                                              @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                  <a target="_bank"
                                                                      href="{{ route('paiements.invoice.print', $item->id) }}"
                                                                      class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                      title="Facture">
                                                                      <svg class="w-4 h-4 mr-1" fill="none"
                                                                          stroke="currentColor" viewBox="0 0 24 24">
                                                                          <path stroke-linecap="round"
                                                                              stroke-linejoin="round" stroke-width="2"
                                                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                      </svg>
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
                                                                          <svg class="w-4 h-4 mr-1" fill="none"
                                                                              stroke="currentColor"
                                                                              viewBox="0 0 24 24">
                                                                              <path stroke-linecap="round"
                                                                                  stroke-linejoin="round"
                                                                                  stroke-width="2"
                                                                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                          </svg>
                                                                          Déjà traité

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
                      {{ $paiements->links('vendor.livewire.tailwind-paiements') }}
                  </ul>
              </nav>
          </div>
          <!-- END: Pagination -->
      </div>
  </div>
