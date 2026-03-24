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
                  Info Versements
              </th>
              <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                  Statut </th>
              <th class="border-b-2 dark:border-darkmode-400 text-center whitespace-nowrap">
                  Actions
              </th>
          </tr>
      </thead>
      <tbody>
          @if ($bankSend_historicals->count() > 0)
              @foreach ($bankSend_historicals as $key => $item)
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
                              $userCurrencyValue > 0 ? $amountReceived / $userCurrencyValue : null;
                      } /**
                       * Cas 2 : le pays de la transaction n'est PAS CFA
                       * → convertir vers CFA puis vers devise utilisateur
                       */ else {
                          // Étape 1 : montant → CFA
                          $amountInCfa = $amountReceived * $itemCurrencyValue;

                          // Étape 2 : CFA → devise utilisateur
                          $valeur_amount_receveid = $userCurrencyValue > 0 ? $amountInCfa / $userCurrencyValue : null;
                      }
                  @endphp

                  <tr class="intro-x">
                      <td class="text-center w-40">
                          <div
                              class="px-3 py-2 text-dark btn-teal dark:bg-darkmode-400 dark:text-slate-300 rounded font-medium mb-2">
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

                      <td class="w-60">
                          <div class="mb-3">
                              @include('components.info')
                          </div>
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
                                  @if (Auth::guard('web')->user()->hasPermissionTo('traitement-demande'))
                                      <a target="_bank"
                                          href="{{ route('versment_transactions.invoice.print', $item->id) }}"
                                          class="btn btn-primary mr-1 mb-2 btn-sm" title="Facture">
                                          Facture
                                      </a>
                                  @endif
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
