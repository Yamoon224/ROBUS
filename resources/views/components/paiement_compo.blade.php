  <div id="paiement" class="tab-pane" role="tabpanel" aria-labelledby="paiement-tab">
      <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
          <div class="hidden md:block mx-auto text-slate-500"></div>
          <div class="hidden md:block mx-auto text-slate-500"></div>
          <div class="hidden md:block mx-auto text-slate-500"></div>


          <div class="hidden md:block mx-auto text-slate-500"></div>

          <div class="input-group input-group-md hidden" style="margin-top: 13px;width: 250px;   ">
              <div class="w-56 relative text-slate-500">

                  <input type="text" name="table_search" 
                      class="form-control  w-56 box pr-10" style="height: 3rem; background-color: #eff3f7;"
                      placeholder="Rechercher">
                  <i class="w-4 h-4 absolute my-auto inset-y-0 mr-6 " style="margin-left: 12rem;"
                      data-feather="searchPaiment"></i>
              </div>
          </div>
      </div>
      <div class="grid grid-cols-12 gap-6 mt-6">
          <div class="intro-y box col-span-12" style="background-color: #eff3f7;">
              <div class="flex items-center px-5 py-3 border-b border-slate-200/60 dark:border-darkmode-400">
                  <h2 class="font-medium text-base mr-auto">
                      Liste des paiements des factures
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
                                          Envoyer le
                                      </th>

                                      <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                                          Numero
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
                                                  {{ $item->provider->name ?? null }}

                                              </td>
                                              <td class="w-50">
                                                  <div class="mt-0.5 mb-2">
                                                      <div class="mt-0.5">
                                                          @if ($item->wallet->sidUser->is_under_distributer == 1)
                                                              <div
                                                                  class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                  <div class="fc-event-title-container">
                                                                      <div class="fc-event-title fc-sticky">
                                                                          Sous-distributeur</div>
                                                                  </div>
                                                              </div>
                                                          @elseif($item->wallet->sidUser->is_custumers == 1)
                                                              <div
                                                                  class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                  <div class="fc-event-title-container">
                                                                      <div class="fc-event-title fc-sticky">Client</div>
                                                                  </div>
                                                              </div>
                                                          @else
                                                              <div
                                                                  class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium">
                                                                  <div class="fc-event-title-container">
                                                                      <div class="fc-event-title fc-sticky">Admin</div>
                                                                  </div>
                                                              </div>
                                                          @endif
                                                          <div class="px-3 py-2 text-primary bg-primary/10 dark:bg-darkmode-400  rounded font-medium"
                                                          style="background-color: #b5048f; color:white;">
                                                          {{-- <div class="fc-event-title-container">
                                                              <div class="fc-event-title fc-sticky"> --}}
                                                                  Numero : {{ $item->wallet->sidUser->phone ?? null }}
                                                              {{-- </div>
                                                          </div> --}}
                                                      </div>
                                                          {{ $item->wallet->sidUser->firstname ?? null }}
                                                          {{ $item->wallet->sidUser->lastname ?? null }}
                                                      </div>
                                                  </div>
                                                  <div class="text-slate-500  mt-0.5">
                                                      <span
                                                          class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                          {{ $item->wallet->sidUser->country->name ?? null }} ville :
                                                          {{ $item->wallet->sidUser->city->name ?? null }}
                                                      </span>
                                                  </div>

                                              </td>
                                              <td class="w-40">

                                                  {{ number_format($item->amount, 2, ',', ' ') ?? null }}
                                                  {{ $item->wallet->currencie->code ?? null }}
                                                  <p class="mt-3">
                                                    Solde : 
                                                </p>
                                                <span
                                                    class="alert alert-primary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                    {{ number_format($item->amount_solde, 2, ',', ' ') ?? null }}
                                                    {{ $item->wallet->currencie->code ?? null }}
                                                </span>

                                              </td>
                                              <td class="w-40">

                                                  {{ $item->created_at ?? null }}
                                              </td>

                                              <td class="w-40">

                                                  @if ($item->updated_by == null)
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
                                                          @elseif ($item->paiementChoix_phone == null && $item->factureChoix_type== null )
                                                              {{ substr($item->number_contract ?? null, 0, 5) }}

                                                          @endif

                                                      </p>
                                                      <div class="flex items-center justify-center text-success mt-3">

                                                          @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                              <a class="flex btn btn-warning items-center  mr-1 mb-2"
                                                                  wire:click="ChooseUpdatePayment({{ $item->id }})">

                                                                  Je prends <svg width="25" viewBox="0 0 120 30"
                                                                      xmlns="http://www.w3.org/2000/svg" fill="1a202c"
                                                                      class="w-4 h-4 ml-2">
                                                                      <circle cx="15" cy="15"
                                                                          r="15">
                                                                          <animate attributeName="r" from="15"
                                                                              to="15" begin="0s"
                                                                              dur="0.8s" values="15;9;15"
                                                                              calcMode="linear"
                                                                              repeatCount="indefinite">
                                                                          </animate>
                                                                          <animate attributeName="fill-opacity"
                                                                              from="1" to="1"
                                                                              begin="0s" dur="0.8s"
                                                                              values="1;.5;1" calcMode="linear"
                                                                              repeatCount="indefinite">
                                                                          </animate>
                                                                      </circle>
                                                                      <circle cx="60" cy="15"
                                                                          r="9" fill-opacity="0.3">
                                                                          <animate attributeName="r" from="9"
                                                                              to="9" begin="0s"
                                                                              dur="0.8s" values="9;15;9"
                                                                              calcMode="linear"
                                                                              repeatCount="indefinite">
                                                                          </animate>
                                                                          <animate attributeName="fill-opacity"
                                                                              from="0.5" to="0.5"
                                                                              begin="0s" dur="0.8s"
                                                                              values=".5;1;.5" calcMode="linear"
                                                                              repeatCount="indefinite">
                                                                          </animate>
                                                                      </circle>
                                                                      <circle cx="105" cy="15"
                                                                          r="15">
                                                                          <animate attributeName="r" from="15"
                                                                              to="15" begin="0s"
                                                                              dur="0.8s" values="15;9;15"
                                                                              calcMode="linear"
                                                                              repeatCount="indefinite">
                                                                          </animate>
                                                                          <animate attributeName="fill-opacity"
                                                                              from="1" to="1"
                                                                              begin="0s" dur="0.8s"
                                                                              values="1;.5;1" calcMode="linear"
                                                                              repeatCount="indefinite">
                                                                          </animate>
                                                                      </circle>
                                                                  </svg>
                                                                  {{-- </button> --}}

                                                              </a>
                                                          @endif

                                                      </div>
                                                  @else
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
                                                              <div class="px-3 py-2 text-dark bg-pending dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                                                                  <div class="fc-event-title-container"
                                                                      style="color: white;">
                                                                      <div class="fc-event-title fc-sticky">
                                                                          Numero du decodeur
                                                                      </div>
                                                                  </div>
                                                              </div>
                                                              {{ ($item->number_decoder) }}

                                                          @else
                                                              {{ $item->number_contract ?? null }}
                                                          @endif
                                                      </p>
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
                                                          @if ($item->status == 'PENDING' && $item->updated_by == Auth::guard('web')->user()->id)
                                                              @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                  <a wire:click="editPaiement({{ $item->id }}, 'update-paiementInvoice-modal')"
                                                                      class="btn btn-primary mr-1 mb-2 btn-sm"
                                                                      href="javascript:;" title="Modifier">
                                                                      Modifier
                                                                  </a>
                                                              @endif
                                                          @elseif ($item->status == 'SUCCESS' && $item->updated_by == Auth::guard('web')->user()->id)
                                                              @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                                                  <a target="_bank"
                                                                      href="{{ route('paiements.invoice.print', $item->id) }}"
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
                      {{ $paiements->links('vendor.livewire.tailwind-paiements') }}
                  </ul>
              </nav>
          </div>
          <!-- END: Pagination -->
      </div>
  </div>
