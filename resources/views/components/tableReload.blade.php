  @php
      $currentUser = Auth::guard('web')->check()
          ? Auth::guard('web')->user()
          : (Auth::guard('integration')->check()
              ? Auth::guard('integration')->user()
              : null);
  @endphp


  <table id="table_orders" class="table table-bordered">
      <thead class="table-dark">
          <tr>

              <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                  Opérateur
              </th>
              <th class="border-b-2 dark:border-darkmode-400 whitespace-nowrap">
                  Expéditeur
              </th>
              <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                  Montant demandé
              </th>
              <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                  Info transaction
              </th>

              <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                  Statut </th>
              <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                  Actions
              </th>
          </tr>
      </thead>
      <tbody>
          @if ($reload_historicals->count() > 0)
              @php primeWallets($reload_historicals, 'account_wallet'); @endphp
              @foreach ($reload_historicals as $item)
                  @php
                      $pixelMeta = is_array($item->metadata ?? null)
                          ? $item->metadata
                          : (json_decode($item->metadata ?? '[]', true) ?: []);
                      $isPixelInnov = method_exists($item, 'isPixelInnov')
                          ? $item->isPixelInnov()
                          : str_starts_with((string) ($item->payment_method ?? ''), 'pixel_innov_');
                  @endphp
                  <tr class="intro-x">
                      <td class="text-center w-40">
                          <div
                              class="px-3 py-2 text-dark btn-primary dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
                              <div class="fc-event-title-container" style="color: white;">
                                  <div class="fc-event-title fc-sticky">
                                      {{ $item->provider->name ?? null }}

                                  </div>
                              </div>
                          </div>
                          @if ($isPixelInnov)
                              <div class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-success/20 text-success">
                                  Pixel Innov
                              </div>
                              <div class="text-xs text-slate-500 mt-1">
                                  {{ $pixelMeta['provider'] ?? ($item->provider->name ?? null) }}
                                  @if (!empty($pixelMeta['country']))
                                      - {{ $pixelMeta['country'] }}
                                  @endif
                              </div>
                          @endif

                      </td>
                      <td class="user-info-card">
                          @include('components.infoUser')

                      </td>
                      @php

                          $valeur_amount_receveid = (float) ($item->amount_received ?? 0);

                          $senderCurrencyValue = optional(optional(sabdy()->country)->currencie)->value ?? 1;
                          $receiverCurrencyValue =
                              optional(optional(optional($item->wallet)->sidUser)->country)->currencie->value ?? 1;
                          $countryId = optional(sabdy()->country)->id;

                          if ($countryId == 107) {
                              // Conversion de montant
                              if ($senderCurrencyValue > $receiverCurrencyValue) {
                                  // Conversion en CFA
                                  $valeur_amount_receveid =
                                      (float) $item->amount_received * (float) $senderCurrencyValue;
                              } elseif ($senderCurrencyValue == $receiverCurrencyValue) {
                                  // Montant inchangé
                                  $valeur_amount_receveid = (float) $item->amount_received;
                              } elseif ($senderCurrencyValue < $receiverCurrencyValue) {
                                  // Conversion vers la devise du pays d’envoi
                                  $valeur_amount_receveid =
                                      (float) $item->amount_received * (float) $receiverCurrencyValue;
                              }
                          } else {
                              // Pas de conversion spécifique
                              $valeur_amount_receveid = (float) $item->amount_received;
                          }
                      @endphp
                      <td>
                          @include('components.walletBalanceReloadConvertion')


                      </td>
                      <td class="w-40">
                          <div class="mb-3">
                              @include('components.info_treatment')
                          </div>

                          <div class="user-name">
                              {{ $item->account_wallet ?? ($item->phone ?? null) }}
                          </div>
                          @if ($isPixelInnov)
                              <div class="mt-3 text-xs leading-5 text-slate-600">
                                  <div>
                                      <span class="font-semibold">Service Pixel:</span>
                                      {{ $pixelMeta['pixel_service_name'] ?? '-' }}
                                  </div>
                                  <div>
                                      <span class="font-semibold">Operation:</span>
                                      {{ $pixelMeta['operation_type'] ?? '-' }}
                                  </div>
                                  <div>
                                      <span class="font-semibold">Destination:</span>
                                      {{ $pixelMeta['destination'] ?? ($item->phone ?? '-') }}
                                  </div>
                                  <div>
                                      <span class="font-semibold">Ref Pixel:</span>
                                      {{ $item->reference ?? '-' }}
                                  </div>
                                  <div>
                                      <span class="font-semibold">Gateway service:</span>
                                      {{ $pixelMeta['service_id'] ?? '-' }}
                                  </div>
                              </div>
                          @endif
                      </td>

                      <td class="w-60">
                          @include('components.infoUserReload')

                          @if ($item->status == 'PENDING' || $item->status == 'pending')
                              <div class="flex items-center justify-center text-pending mt-3">
                                  <span
                                      class="alert alert-pending  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                      En-attente
                                  </span>

                              </div>
                          @elseif ($item->status == 'ERROR' || $item->status == 'cancelled'  || $item->status == 'refused' || $item->status == 'failed')
                              <div class="flex items-center justify-center text-danger  mt-3">
                                  <div class="flex items-center justify-center text-pending">
                                      <span
                                          class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                          Refusé
                                      </span>

                                  </div>

                              </div>
                              <div class="flex items-center justify-center text-pending  mt-3">
                                  <span
                                      class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                      style="color: white;">
                                      Traiter
                                  </span>

                              </div>
                          @else
                              <div class="flex items-center justify-center text-success  mt-3">
                                  <div class="flex items-center justify-center text-pending">
                                      <span
                                          class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                                          style="color: white;">
                                          Accepté
                                      </span>


                                  </div>

                              </div>
                              <div class="flex items-center justify-center text-pending  mt-3">
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
                          @include('components.infoUserSoldeUnder')

                          <div class="flex justify-center items-center">

                              @if ($item->status == 'PENDING')
                                  @if ($currentUser && $currentUser->hasPermissionTo('traitement-demande'))
                                      <a wire:click="editReload({{ $item->id }}, 'update-reloadWallet-modal')"
                                          class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;" title="Modifier">
                                          Modifier
                                      </a>
                                  @endif
                              @elseif ($item->status == 'SUCCESS')
                                  <a target="_bank" href="{{ route('reaload.invoice.print', $item->id) }}"
                                      class="btn btn-primary mr-1 mb-2 btn-sm" title="Facture">
                                      Facture
                                  </a>
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
                  <td colspan="7" class="text-center">Aucune donnée pour le
                      moment</td>
              </tr>
          @endif
      </tbody>
  </table>
