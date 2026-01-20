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
                      Info Djamo
                  </th>
                  <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                      Statut </th>
                  <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                      Actions
                  </th>
              </tr>
          </thead>
          <tbody>
              @if ($bank_historicals->count() > 0)
                  @foreach ($bank_historicals as $key => $item)
                      <tr class="intro-x">
                          <td class="text-center w-40">

                              <div
                                  class="px-3 py-2 text-dark btn-rose dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
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
                              <div class="flex flex-col items-center justify-center gap-2 p-2 bg-gray-50 mt-3">
                                  <i class="fas fa-qrcode text-gray-400 text-xl"></i>


                                  @if ($item->image)
                                      @if ($item->is_livewire === true)
                                          <img src="{{ asset('storage/' . $item->image) }}" alt="QR Code Djamo"
                                              class="w-24 h-24 object-contain rounded-lg border border-gray-300 shadow-sm">
                                      @else
                                          <img src="{{ $item->image }}" alt="QR Code Djamo"
                                              class="w-24 h-24 object-contain rounded-lg border border-gray-300 shadow-sm">
                                      @endif
                                  @else
                                      <span class="text-gray-400 italic">Aucune image
                                          disponible</span>
                                  @endif
                              </div>
                          </td>

                          <td class="w-40">
                              <div class="mb-3">
                                  @include('components.infoValidtedUser')
                              </div>

                              @include('components.info_status')

                          </td>
                          <td class="w-40">
                              <div class="flex justify-center items-center">
                                  @if ($item->status == 'PENDING')
                                      <div class="flex items-center justify-center text-pending">
                                          <span
                                              class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                              En-attente
                                          </span>

                                      </div>
                                  @elseif ($item->status == 'SUCCESS')
                                      {{-- @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande')) --}}
                                      <a target="_bank"
                                          href="{{ route('djamo_transactions.invoice.print', $item->id) }}"
                                          class="btn btn-primary mr-1 mb-2 btn-sm" title="Facture">
                                          Facture
                                      </a>
                                      {{-- @endif --}}
                                  @else
                                      <div class="flex items-center justify-center text-dark">
                                          <div class="flex items-center justify-center text-pending">
                                              <span
                                                  class="alert  alert-warning inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                                      text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                                  style="color: white;">
                                                  Déjà choisir
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
