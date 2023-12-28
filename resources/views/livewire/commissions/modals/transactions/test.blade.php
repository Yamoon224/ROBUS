<!-- Modal -->
<div wire:ignore.self id="create-interTransfers-modal" class="modal  hide  overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">Demande de transfers internationnal</h2>
            </div>
            <a wire:click="cancelInterField"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>


            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3  mt-6">

                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:model="choose_type_paiement" id="choose_type_paiement" type="radio" value="1"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="choose_type_paiement"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300"> Paiement
                        mooney</label>
                    @error('choose_type_paiement')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>

                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:model="choose_type_paiement" id="choose_type_paiement" type="radio" value="5"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="choose_type_paiement"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300"> Paiement
                        facture</label>
                    @error('choose_type_paiement')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 mt-3">
                    <input wire:model="choose_type_paiement" id="choose_type_paiement" type="radio" value="6"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="choose_type_paiement"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300"> Paiement
                        credit telephonique</label>
                    @error('choose_type_paiement')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 mt-3">
                    <input wire:model="choose_type_paiement" id="choose_type_paiement" type="radio" value="7"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="choose_type_paiement"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Versement
                        bancaire</label>
                    @error('choose_type_paiement')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 mt-3">
                    <input wire:model="choose_type_paiement" id="choose_type_paiement" type="radio" value="8"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="choose_type_paiement"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Rechargement de
                        carte</label>
                    @error('choose_type_paiement')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- <div class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700 mt-3">
                    <input wire:model="choose_type_paiement" id="choose_type_paiement" type="radio" value="8"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="choose_type_paiement"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">Rechargement de carte</label>
                    @error('choose_type_paiement')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}

            </div>
            @if (!is_null($choose_type_paiement))

                @if ($choose_type_paiement == 1)

                    <input type="hidden" wire:init="loadChoose1">
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                        <div class="col-span-12 mt-space">
                            <label for="provider_inter" class="form-label">Canal du paiement mooney<span
                                    class="text-danger">
                                    *</span></label>
                            <select wire:model="provider_inter" id="provider_inter" class="form-control">
                                <option value="null" disabled>Selectionnez le canal</option>
                                @foreach ($provider_inters as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('provider_inter')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    @if (!is_null($provider_inter))
                        @if ($provider_inter == 51)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/robus.png') }}"
                                        alt="image"wire:model="image">
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 mt-space">
                                    <label for="account_number_inter" class="form-label">Numero du compte<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model="account_number_inter" id="account_number_inter" type="text"
                                        class="form-control" placeholder="Numero du compte" value=""
                                        maxlength="9">
                                    @error('account_number_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 mt-space">
                                    <div wire:loading>Recherche wallet...</div>
                                    <div wire:loading.remove></div>
                                    @if ($account_number_inter == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer numero wallet
                                        </div>
                                    @else
                                        @if ($wallet_search_inter->isEmpty())
                                            <div class="text-gray-500 text-sm">
                                                Ce numero n'existe pas
                                            </div>
                                        @else
                                            @foreach ($wallet_search_inter as $wallet)
                                                <div>
                                                    <div class="text-lg text-gray-900 text-bold text-secondary ">
                                                        <span
                                                            class="alert alert-secondary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                                                            {{ $wallet->sidUser->firstname ?? null }}
                                                            {{ $wallet->sidUser->lastname ?? null }}
                                                        </span>

                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                                <div class="col-span-6 mt-space">
                                    <label for="country_inter_robus" class="form-label">Pays<span
                                            class="text-danger">
                                            *</span></label>
                                    <select wire:model.defer="country_inter_robus" id="country_inter_robus"
                                        class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter_robus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6 mt-space">
                                    <label for="amount_inter_robus" class="form-label">Montant<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.debounce.10ms="amount_inter_robus" id="amount_inter_robus"
                                        type="number" class="form-control" placeholder="Montant">
                                    @error('amount_inter_robus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>

                            </div>
                        @elseif ($provider_inter == 49)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/orange.png') }}" alt="Orange money">
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 mt-space">
                                    <label for="country_inter" class="form-label">Pays<span class="text-danger">
                                            *</span></label>
                                    <select wire:model="country_inter" id="country_inter" class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if (!is_null($country_inter))

                                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                                    @php
                                        foreach ($countries as $item) {
                                            $countrie_indicative = $item->where('id', $country_inter)->first();
                                        }
                                    @endphp
                                    <div class="col-span-6 mt-space">
                                        <label for="phone_inter" class="form-label">Numero de telephone<span
                                                class="text-danger">
                                                *</span></label>

                                        <div class="flex">

                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>
                                            <input wire:model.defer="phone_inter" id="phone_inter" type="number"
                                                class="form-control" placeholder="Numero" value=""
                                                maxlength="15">
                                            @error('phone_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-span-6 mt-space">
                                        <label for="amount_inter" class="form-label">Montant<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.debounce.10ms="amount_inter" id="amount_inter"
                                            type="number" class="form-control" placeholder="Montant">
                                        @error('amount_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (!is_null($amount_inter))
                                        <div class="col-span-12 mt-3">
                                            <label for="amount_inter" class="form-label">Montant à payer<span
                                                    class="text-danger">
                                                    *</span></label>
                                            @if ($amount_inter == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                    <div
                                                        class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                        {{ $amount_inter + $amount_inter_cost->percent * $amount_inter }}
                                                        {{ $usersCountry }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @elseif ($provider_inter == 50)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/moov.png') }}" alt="Orange money">
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 mt-space">
                                    <label for="country_inter" class="form-label">Pays<span class="text-danger">
                                            *</span></label>
                                    <select wire:model="country_inter" id="country_inter" class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if (!is_null($country_inter))

                                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                                    @php
                                        foreach ($countries as $item) {
                                            $countrie_indicative = $item->where('id', $country_inter)->first();
                                        }
                                    @endphp
                                    <div class="col-span-6 mt-space">
                                        <label for="phone_inter" class="form-label">Numero de telephone<span
                                                class="text-danger">
                                                *</span></label>
                                        <div class="flex">
                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>
                                            <input wire:model.debounce.10ms="phone_inter" id="phone_inter"
                                                type="number" class="form-control" placeholder="Numero"
                                                value="" maxlength="15">
                                            @error('phone_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-span-6 mt-space">
                                        <label for="amount_inter" class="form-label">Montant<span
                                                class="text-danger">
                                                *</span></label>
                                        <div class="flex">
                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>
                                            <input wire:model.debounce.10ms="amount_inter" id="amount_inter"
                                                type="number" class="form-control" placeholder="Montant">
                                            @error('amount_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    @if (!is_null($amount_inter))
                                        <div class="col-span-12 mt-3">
                                            <label for="amount_inter" class="form-label">Montant à payer<span
                                                    class="text-danger">
                                                    *</span></label>
                                            @if ($amount_inter == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                    <div
                                                        class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                        {{ $amount_inter + $amount_inter_cost->percent * $amount_inter }}
                                                        {{ $usersCountry }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                            @endif
                        @elseif ($provider_inter == 48)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/mtn.png') }}" alt="MTN money">
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 mt-space">
                                    <label for="country_inter" class="form-label">Pays<span class="text-danger">
                                            *</span></label>
                                    <select wire:model="country_inter" id="country_inter" class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            @if (!is_null($country_inter))

                                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                                    @php
                                        foreach ($countries as $item) {
                                            $countrie_indicative = $item->where('id', $country_inter)->first();
                                        }
                                    @endphp
                                    <div class="col-span-6 mt-space">
                                        <label for="phone_inter" class="form-label">Numero de telephone<span
                                                class="text-danger">
                                                *</span></label>
                                        <div class="flex">
                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>
                                            <input wire:model.defer="phone_inter" id="phone_inter" type="number"
                                                class="form-control" placeholder="Numero" value=""
                                                maxlength="15">
                                            @error('phone_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-span-6 mt-space">
                                        <label for="amount_inter" class="form-label">Montant<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.debounce.10ms="amount_inter" id="amount_inter"
                                            type="number" class="form-control" placeholder="Montant">
                                        @error('amount_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (!is_null($amount_inter))
                                        <div class="col-span-12 mt-3">
                                            <label for="amount_inter" class="form-label">Montant à payer<span
                                                    class="text-danger">
                                                    *</span></label>
                                            @if ($amount_inter == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                    <div
                                                        class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                        {{ $amount_inter + $amount_inter_cost->percent * $amount_inter }}
                                                        {{ $usersCountry }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @elseif ($provider_inter == 55)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/wave.png') }}" alt="MTN money">
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 mt-space">
                                    <label for="country_inter" class="form-label">Pays<span class="text-danger">
                                            *</span></label>
                                    <select wire:model="country_inter" id="country_inter" class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if (!is_null($country_inter))

                                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">
                                    @php
                                        foreach ($countries as $item) {
                                            $countrie_indicative = $item->where('id', $country_inter)->first();
                                        }
                                    @endphp
                                    <div class="col-span-6 mt-space">
                                        <label for="phone_inter" class="form-label">Numero de telephone<span
                                                class="text-danger">
                                                *</span></label>
                                        <div class="flex">
                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>
                                            <input wire:model.defer="phone_inter" id="phone_inter" type="number"
                                                class="form-control" placeholder="Numero" value=""
                                                maxlength="15">
                                            @error('phone_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-span-6 mt-space">
                                        <label for="amount_inter" class="form-label">Montant<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.debounce.10ms="amount_inter" id="amount_inter"
                                            type="number" class="form-control" placeholder="Montant">
                                        @error('amount_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (!is_null($amount_inter))
                                        <div class="col-span-12 mt-3">
                                            <label for="amount_inter" class="form-label">Montant à payer<span
                                                    class="text-danger">
                                                    *</span></label>
                                            @if ($amount_inter == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                    <div
                                                        class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                        {{ $amount_inter + $amount_inter_cost->percent * $amount_inter }}
                                                        {{ $usersCountry }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif

                        @endif
                    @endif
                @elseif ($choose_type_paiement == 5)
                    <input type="hidden" wire:init="loadChoose2">
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="provider_payment_inter" class="form-label">Canal du paiement de facture<span
                                    class="text-danger">
                                    *</span></label>
                            <select wire:model="provider_payment_inter" id="provider_payment_inter"
                                class="form-control">
                                <option value="null" disabled>Selectionnez le canal</option>
                                @foreach ($provider_paiements_inter as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('provider_payment_inter')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>
                    @if (!is_null($provider_payment_inter))
                        @if ($provider_payment_inter == 52)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/canal.png') }}" alt="MTN money">
                                </div>
                            </div>
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <div class="alert alert-secondary show mb-2" role="alert">
                                        Faire le choix entre les deux numero
                                    </div>
                                </div>
                                <div class="col-span-12">
                                    <label class="form-label" for="paiementChoix_phone">Le choix de numero<span
                                            class="text-danger">
                                            *</span></label>
                                    <select wire:model="paiementChoix_phone" id="paiementChoix_phone"
                                        class="form-control">
                                        <option value="null" disabled>Selectionnez type numero</option>
                                        <option value="1">Numero de telephone</option>
                                        <option value="2">Numero du decodeur</option>
                                    </select>
                                </div>
                            </div>
                            @if (!is_null($paiementChoix_phone))
                                @if ($paiementChoix_phone == 1)
                                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                        <div class="col-span-6">
                                            <label for="paiement_phone_canal_inter" class="form-label">Numero du
                                                telephone(10
                                                chiffres)<span class="text-danger">
                                                    *</span></label>
                                            <input wire:model.defer="paiement_phone_canal_inter"
                                                id="paiement_phone_canal_inter" type="text" class="form-control"
                                                value="" maxlength="10" placeholder="Numero du telephone">
                                            @error('paiement_phone_canal_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-6">
                                            <label for="country_inter" class="form-label">Pays<span
                                                    class="text-danger">
                                                    *</span></label>
                                            <select wire:model.defer="country_inter" id="country_inter"
                                                class="form-control">
                                                <option value="null" disabled>Selectionnez le pays</option>
                                                @foreach ($countries as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('country_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @elseif ($paiementChoix_phone == 2)
                                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                        <div class="col-span-6">
                                            <label for="paiement_decoder_canal_inter" class="form-label">Numero du
                                                decodeur(14 chiffres)<span class="text-danger">
                                                    *</span></label>
                                            <input wire:model.defer="paiement_decoder_canal_inter"
                                                id="paiement_decoder_canal_inter" type="text" class="form-control"
                                                value="" maxlength="14" placeholder="Numero du decodeur">
                                            @error('paiement_decoder_canal_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-6">
                                            <label for="country_inter" class="form-label">Pays<span
                                                    class="text-danger">
                                                    *</span></label>
                                            <select wire:model.defer="country_inter" id="country_inter"
                                                class="form-control">
                                                <option value="null" disabled>Selectionnez le pays</option>
                                                @foreach ($countries as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('country_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                    <div class="col-span-12">
                                        <label for="offer_paiement" class="form-label">Offre<span
                                                class="text-danger">
                                                *</span></label>
                                        <select wire:model="offer_paiement" class="select2 form-select">
                                            <option value="null" disabled>Selectionnez offre</option>
                                            @foreach ($offers_canal as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach

                                        </select>
                                        @error('offer_paiement')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                </div>
                            @endif

                        @endif
                        @if ($provider_payment_inter == 53)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/cie.png') }}" alt="MTN money">
                                </div>
                            </div>
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label class="form-label" for="facture_choix_type">Le choix de type de
                                        facture<span class="text-danger">
                                            *</span></label>
                                    <select wire:model="facture_choix_type" id="facture_choix_type"
                                        class="form-control">
                                        <option value="null" disabled>Choix type facture</option>
                                        <option value="1">Facture prépayée</option>
                                        <option value="2">Facture normale</option>
                                    </select>
                                </div>
                            </div>
                            @if (!is_null($facture_choix_type))
                                @if ($facture_choix_type == 1)
                                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                        <div class="col-span-6">
                                            <label for="paiement_id_invoice_inter" class="form-label">Identifiant
                                                facture(11
                                                chiffres)<span class="text-danger">
                                                    *</span></label>
                                            <input wire:model.defer="paiement_id_invoice_inter"
                                                id="paiement_id_invoice_inter" type="text" class="form-control"
                                                value="" maxlength="11" placeholder="Identifiant facture">
                                            @error('paiement_id_invoice_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-6">
                                            <label for="country_inter" class="form-label">Pays<span
                                                    class="text-danger">
                                                    *</span></label>
                                            <select wire:model.defer="country_inter" id="country_inter"
                                                class="form-control">
                                                <option value="null" disabled>Selectionnez le pays</option>
                                                @foreach ($countries as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('country_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @elseif ($facture_choix_type == 2)
                                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                        <div class="col-span-6">
                                            <label for="paiement_id_invoice_inter" class="form-label">Identifiant
                                                facture(09
                                                chiffres)<span class="text-danger">
                                                    *</span></label>
                                            <input wire:model.defer="paiement_id_invoice_inter"
                                                id="paiement_id_invoice_inter" type="text" class="form-control"
                                                value="" maxlength="9" placeholder="Identifiant">
                                            @error('c')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-span-6">
                                            <label for="country_inter" class="form-label">Pays<span
                                                    class="text-danger">
                                                    *</span></label>
                                            <select wire:model.defer="country_inter" id="country_inter"
                                                class="form-control">
                                                <option value="null" disabled>Selectionnez le pays</option>
                                                @foreach ($countries as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('country_inter')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                @endif
                                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                    <div class="col-span-6">
                                        <label for="paiement_name_cie_inter" class="form-label">Nom de la facture<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.defer="paiement_name_cie_inter" id="paiement_name_cie_inter"
                                            type="text" class="form-control" placeholder="Nom facture">
                                        @error('paiement_name_cie_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-span-6">
                                        <label for="paiement_amount_total_inter" class="form-label">Montant total<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.debounce.10ms="paiement_amount_total_inter"
                                            id="paiement_amount_total_inter" type="number" class="form-control"
                                            placeholder="Montant total">
                                        @error('paiement_amount_total_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (!is_null($paiement_amount_total_inter))
                                        <div class="col-span-12 mt-3">
                                            <label for="paiement_amount_total_inter" class="form-label">Montant à
                                                payer<span class="text-danger">
                                                    *</span></label>
                                            @if ($paiement_amount_total_inter == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                    <div
                                                        class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                        {{ $paiement_amount_total_inter + $amount_invoice->percent * $paiement_amount_total_inter }}
                                                        {{ $usersCountry }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                </div>
                                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                    <div class="col-span-6">
                                        <label for="pre_id_invoice_inter" class="form-label">Id facture<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.defer="pre_id_invoice_inter" id="pre_id_invoice_inter"
                                            type="number" class="form-control" placeholder="Montant total">
                                        @error('pre_id_invoice_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-span-6">
                                        <label for="pre_phone_invoice_inter" class="form-label">Numero </label>
                                        <input wire:model.defer="pre_phone_invoice_inter" id="pre_phone_invoice_inter"
                                            type="number" class="form-control" placeholder="Numero de telephone">
                                        @error('pre_phone_invoice_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                            @endif

                        @endif

                        @if ($provider_payment_inter == 54)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/sodeci.png') }}" alt="MTN money">
                                </div>
                            </div>
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6">
                                    <label for="paiement_name_sodecie_inter" class="form-label">Nom de la facture<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="paiement_name_sodecie_inter"
                                        id="paiement_name_sodecie_inter" type="text" class="form-control"
                                        placeholder="Nom facture">
                                    @error('paiement_name_sodecie_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6">
                                    <label for="country_inter" class="form-label">Pays<span class="text-danger">
                                            *</span></label>
                                    <select wire:model.defer="country_inter" id="country_inter" class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6">
                                    <label for="paiement_num_contrat_inter" class="form-label">Numero du contrat (09
                                        chiffres)<span class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="paiement_num_contrat_inter"
                                        id="paiement_num_contrat_inter" type="text" class="form-control"
                                        placeholder="Numero contrat" value="" maxlength="9">
                                    @error('paiement_num_contrat_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6">
                                    <label for="paiement_amount_t_sodecie_inter" class="form-label">Montant total<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.debounce.10ms="paiement_amount_t_sodecie_inter"
                                        id="paiement_amount_t_sodecie_inter" type="number" class="form-control"
                                        placeholder="Montant total">
                                    @error('paiement_amount_t_sodecie_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if (!is_null($paiement_amount_t_sodecie_inter))
                                    <div class="col-span-12 mt-3">
                                        <label for="paiement_amount_t_sodecie_inter" class="form-label">Montant à
                                            payer<span class="text-danger">
                                                *</span></label>
                                        @if ($paiement_amount_t_sodecie_inter == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                <div
                                                    class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                    {{ $paiement_amount_t_sodecie_inter + $amount_invoice->percent * $paiement_amount_t_sodecie_inter }}
                                                    {{ $usersCountry }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                    @endif
                @elseif ($choose_type_paiement == 6)
                    <input type="hidden" wire:init="loadChoose3">

                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="provider_phone_credit_inter" class="form-label">Canal du paiement de credit
                                telephonique<span class="text-danger">
                                    *</span></label>
                            <select wire:model="provider_phone_credit_inter" id="provider_phone_credit_inter"
                                class="form-control">
                                <option value="null" disabled>Selectionnez le canal</option>
                                @foreach ($provider_credits_inter as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('provider_phone_credit_inter')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if (!is_null($provider_phone_credit_inter))
                        @if ($provider_phone_credit_inter == 56)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/orange.png') }}" alt="Orange money">
                                </div>
                            </div>

                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label for="country_inter_robus" class="form-label">Pays<span
                                            class="text-danger">
                                            *</span></label>
                                    <select wire:model="country_inter_robus" id="country_inter_robus"
                                        class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter_robus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                            @if (!is_null($country_inter_robus))

                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    @php
                                        foreach ($countries as $item) {
                                            $countrie_indicative = $item->where('id', $country_inter_robus)->first();
                                        }
                                    @endphp
                                    <div class="col-span-6">
                                        <label for="number_phone_credit_inter" class="form-label">Numero<span
                                                class="text-danger">
                                                *</span></label>

                                        <div class="flex">
                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>

                                            <input wire:model.defer="number_phone_credit_inter"
                                                id="number_phone_credit_inter" type="number" class="form-control"
                                                placeholder="Montant" value="" maxlength="15">
                                        </div>

                                        @error('number_phone_credit_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-span-6">
                                        <label for="amount_phone_credit_inter" class="form-label">Montant<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.debounce.10ms="amount_phone_credit_inter"
                                            id="amount_phone_credit" type="number" class="form-control"
                                            placeholder="Montant">
                                        @error('amount_phone_credit_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    @if (!is_null($amount_phone_credit_inter))
                                        <div class="col-span-12 mt-3">
                                            <label for="amount_phone_credit_inter" class="form-label">Montant à
                                                recevoir<span class="text-danger">
                                                    *</span></label>
                                            @if ($amount_phone_credit_inter == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                    <div
                                                        class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                        {{ $amount_phone_credit_inter - $amount_phone_cost->percent * $amount_phone_credit_inter }}
                                                        {{ $usersCountry }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @elseif($provider_phone_credit_inter == 57)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/orange.png') }}" alt="Orange money">
                                </div>
                            </div>

                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label for="country_inter_robus" class="form-label">Pays<span
                                            class="text-danger">
                                            *</span></label>
                                    <select wire:model="country_inter_robus" id="country_inter_robus"
                                        class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter_robus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                            @if (!is_null($country_inter_robus))

                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    @php
                                        foreach ($countries as $item) {
                                            $countrie_indicative = $item->where('id', $country_inter_robus)->first();
                                        }
                                    @endphp
                                    <div class="col-span-6">
                                        <label for="number_phone_credit_inter" class="form-label">Numero<span
                                                class="text-danger">
                                                *</span></label>

                                        <div class="flex">
                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>

                                            <input wire:model.defer="number_phone_credit_inter"
                                                id="number_phone_credit_inter" type="number" class="form-control"
                                                placeholder="Montant" value="" maxlength="15">
                                        </div>

                                        @error('number_phone_credit_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-span-6">
                                        <label for="amount_phone_credit_inter" class="form-label">Montant<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.debounce.10ms="amount_phone_credit_inter"
                                            id="amount_phone_credit" type="number" class="form-control"
                                            placeholder="Montant">
                                        @error('amount_phone_credit_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    @if (!is_null($amount_phone_credit_inter))
                                        <div class="col-span-12 mt-3">
                                            <label for="amount_phone_credit_inter" class="form-label">Montant à
                                                recevoir<span class="text-danger">
                                                    *</span></label>
                                            @if ($amount_phone_credit_inter == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                    <div
                                                        class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                        {{ $amount_phone_credit_inter - $amount_phone_cost->percent * $amount_phone_credit_inter }}
                                                        {{ $usersCountry }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @elseif($provider_phone_credit_inter == 58)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/orange.png') }}" alt="Orange money">
                                </div>
                            </div>

                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <label for="country_inter_robus" class="form-label">Pays<span
                                            class="text-danger">
                                            *</span></label>
                                    <select wire:model="country_inter_robus" id="country_inter_robus"
                                        class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter_robus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                            </div>
                            @if (!is_null($country_inter_robus))

                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    @php
                                        foreach ($countries as $item) {
                                            $countrie_indicative = $item->where('id', $country_inter_robus)->first();
                                        }
                                    @endphp
                                    <div class="col-span-6">
                                        <label for="number_phone_credit_inter" class="form-label">Numero<span
                                                class="text-danger">
                                                *</span></label>

                                        <div class="flex">
                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>

                                            <input wire:model.defer="number_phone_credit_inter"
                                                id="number_phone_credit_inter" type="number" class="form-control"
                                                placeholder="Montant" value="" maxlength="15">
                                        </div>

                                        @error('number_phone_credit_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-span-6">
                                        <label for="amount_phone_credit_inter" class="form-label">Montant<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.debounce.10ms="amount_phone_credit_inter"
                                            id="amount_phone_credit" type="number" class="form-control"
                                            placeholder="Montant">
                                        @error('amount_phone_credit_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    @if (!is_null($amount_phone_credit_inter))
                                        <div class="col-span-12 mt-3">
                                            <label for="amount_phone_credit_inter" class="form-label">Montant à
                                                recevoir<span class="text-danger">
                                                    *</span></label>
                                            @if ($amount_phone_credit_inter == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                    <div
                                                        class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                        {{ $amount_phone_credit_inter - $amount_phone_cost->percent * $amount_phone_credit_inter }}
                                                        {{ $usersCountry }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endif
                    @endif
                @elseif ($choose_type_paiement == 7)
                    <input type="hidden" wire:init="loadChoose3">

                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="provider_Inter_virement" class="form-label">Canal du versement bancaire<span
                                    class="text-danger">
                                    *</span></label>
                            <select wire:model="provider_Inter_virement" id="provider_Inter_virement"
                                class="form-control">
                                <option value="null" disabled>Selectionnez le canal</option>
                                @foreach ($provider_inter_virements as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('provider_Inter_virement')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if (!is_null($provider_Inter_virement))
                        @if ($provider_Inter_virement == 59)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/ecobank.png') }}" alt="ecobank money">
                                </div>
                            </div>

                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6">
                                    <label for="country_inter_robus" class="form-label">Pays<span
                                            class="text-danger">
                                            *</span></label>
                                    <select wire:model="country_inter_robus" id="country_inter_robus"
                                        class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter_robus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6">
                                    <label for="account_number_user_bank_inter" class="form-label">Numero du
                                        compte<span class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="account_number_user_bank_inter"
                                        id="account_number_user_bank_inter" type="number" class="form-control"
                                        placeholder="Numero du compte" />
                                    @error('account_number_user_bank_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6">
                                    <label for="firstname_user_inter" class="form-label">Nom du client<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="firstname_user_inter" id="firstname_user_inter"
                                        type="text" class="form-control" placeholder="Nom du client">
                                    @error('firstname_user_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6">
                                    <label for="lastname_user_inter" class="form-label">Prenom du client<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="lastname_user_inter" id="lastname_user_inter"
                                        type="text" class="form-control" placeholder="Prenom du client">
                                    @error('lastname_user_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if (!is_null($country_inter_robus))

                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    @php
                                        foreach ($countries as $item) {
                                            $countrie_indicative = $item->where('id', $country_inter_robus)->first();
                                        }
                                    @endphp
                                    <div class="col-span-6">
                                        <label for="phone_user_inter" class="form-label">Numero<span
                                                class="text-danger">
                                                *</span></label>

                                        <div class="flex">
                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>

                                            <input wire:model.defer="phone_user_inter" id="phone_user_inter"
                                                type="number" class="form-control" placeholder="Montant"
                                                value="" maxlength="15">
                                        </div>

                                        @error('phone_user_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-span-6">
                                        <label for="amount_virement_inter" class="form-label">Montant<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.debounce.10ms="amount_virement_inter"
                                            id="amount_phone_credit" type="number" class="form-control"
                                            placeholder="Montant">
                                        @error('amount_virement_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    @if (!is_null($amount_virement_inter))
                                        <div class="col-span-12 mt-3">
                                            <label for="amount_virement_inter" class="form-label">Montant à
                                                recevoir<span class="text-danger">
                                                    *</span></label>
                                            @if ($amount_virement_inter == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                    <div
                                                        class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                        {{ $amount_bank_inter ?? null }} {{ $usersCountry }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @elseif($provider_Inter_virement == 60)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/nsia.png') }}" alt="nsia money">
                                </div>
                            </div>

                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6">
                                    <label for="country_inter_robus" class="form-label">Pays<span
                                            class="text-danger">
                                            *</span></label>
                                    <select wire:model="country_inter_robus" id="country_inter_robus"
                                        class="form-control">
                                        <option value="null" disabled>Selectionnez le pays</option>
                                        @foreach ($countries as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_inter_robus')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6">
                                    <label for="account_number_user_bank_inter" class="form-label">Numero du
                                        compte<span class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="account_number_user_bank_inter"
                                        id="account_number_user_bank_inter" type="number" class="form-control"
                                        placeholder="Numero du compte" />
                                    @error('account_number_user_bank_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6">
                                    <label for="firstname_user_inter" class="form-label">Nom du client<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="firstname_user_inter" id="firstname_user_inter"
                                        type="text" class="form-control" placeholder="Nom du client">
                                    @error('firstname_user_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6">
                                    <label for="lastname_user_inter" class="form-label">Prenom du client<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="lastname_user_inter" id="lastname_user_inter"
                                        type="text" class="form-control" placeholder="Prenom du client">
                                    @error('lastname_user_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if (!is_null($country_inter_robus))
                                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                    @php
                                        foreach ($countries as $item) {
                                            $countrie_indicative = $item->where('id', $country_inter_robus)->first();
                                        }
                                    @endphp
                                    <div class="col-span-6">
                                        <label for="phone_user_inter" class="form-label">Numero<span
                                                class="text-danger">
                                                *</span></label>

                                        <div class="flex">
                                            <div
                                                class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
                                                {{ $countrie_indicative->indecative ?? null }}
                                            </div>

                                            <input wire:model.defer="phone_user_inter" id="phone_user_inter"
                                                type="number" class="form-control" placeholder="Montant"
                                                value="" maxlength="15">
                                        </div>

                                        @error('phone_user_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-span-6">
                                        <label for="amount_virement_inter" class="form-label">Montant<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.debounce.10ms="amount_virement_inter"
                                            id="amount_virement_inter" type="number" class="form-control"
                                            placeholder="Montant">
                                        @error('amount_virement_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                                @if (!is_null($amount_virement_inter))
                                    <div class="col-span-12 mt-3">
                                        <label for="amount_virement_inter" class="form-label">Montant à
                                            recevoir<span class="text-danger">
                                                *</span></label>
                                        @if ($amount_virement_inter == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                <div
                                                    class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                    {{ $amount_bank_inter ?? null }} {{ $usersCountry }}

                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                @elseif ($choose_type_paiement == 8)
                    <input type="hidden" wire:init="loadChoose8">

                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12">
                            <label for="provider_reloadcard_inter" class="form-label">Canal du versement bancaire<span
                                    class="text-danger">
                                    *</span></label>
                            <select wire:model="provider_reloadcard_inter" id="provider_reloadcard_inter"
                                class="form-control">
                                <option value="null" disabled>Selectionnez le canal</option>
                                @foreach ($provider_reload_inters as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('provider_reloadcard_inter')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if (!is_null($provider_reloadcard_inter))
                        @if ($provider_reloadcard_inter == 65)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/bsic.png') }}" alt="BSIC">
                                </div>
                            </div>

                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 mt-space">
                                    <label for="account_number_reload_card_inter" class="form-label">Numero du
                                        compte<span class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="account_number_reload_card_inter"
                                        id="account_number_reload_card_inter" type="text" class="form-control"
                                        placeholder="Numero du compte" value="" maxlength="10">
                                    @error('account_number_reload_card_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6 mt-space">
                                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter"
                                        type="text" class="form-control" placeholder="Nom et prenom">
                                    @error('name_reloadcard_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6 mt-space">
                                    <label for="amount_reloadcard_inter" class="form-label">Montant<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.debounce.10ms="amount_reloadcard_inter"
                                        id="amount_reloadcard_inter" type="number" class="form-control"
                                        placeholder="Montant">
                                    @error('amount_reloadcard_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if (!is_null($amount_reloadcard_inter))
                                    <div class="col-span-12 mt-3">
                                        <label for="amount_reloadcard_inter" class="form-label">Montant à payer<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard_inter == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                <div
                                                    class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                    {{ $amount_deposit_inter }} {{ $usersCountry }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @elseif($provider_reloadcard_inter == 64)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/ecobank.png') }}" alt="Ecobank">
                                </div>
                            </div>

                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 mt-space">
                                    <label for="account_number_reload_card_inter" class="form-label">Numero du compte<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="account_number_reload_card_inter"
                                        id="account_number_reload_card_inter" type="text" class="form-control"
                                        placeholder="Numero du compte" value="" maxlength="10">
                                    @error('account_number_reload_card_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6 mt-space">
                                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter" type="text"
                                        class="form-control" placeholder="Nom et prenom">
                                    @error('name_reloadcard_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6 mt-space">
                                    <label for="amount_reloadcard_inter" class="form-label">Montant<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.debounce.10ms="amount_reloadcard_inter" id="amount_reloadcard_inter"
                                        type="number" class="form-control" placeholder="Montant">
                                    @error('amount_reloadcard_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if (!is_null($amount_reloadcard_inter))
                                    <div class="col-span-12 mt-3">
                                        <label for="amount_reloadcard_inter" class="form-label">Montant à payer<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard_inter == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                <div
                                                    class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                    {{ $amount_deposit_inter }} {{ $usersCountry }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @elseif ($provider_reloadcard_inter == 63)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/uba.png') }}" alt="UBA BF">
                                </div>
                            </div>

                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 mt-space">
                                    <label for="account_number_reload_card_inter" class="form-label">Numero du
                                        compte<span class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="account_number_reload_card_inter"
                                        id="account_number_reload_card_inter" type="text" class="form-control"
                                        placeholder="Numero du compte" value="" maxlength="10">
                                    @error('account_number_reload_card_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6 mt-space">
                                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter"
                                        type="text" class="form-control" placeholder="Nom et prenom">
                                    @error('name_reloadcard_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6 mt-space">
                                    <label for="amount_reloadcard_inter" class="form-label">Montant<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.debounce.10ms="amount_reloadcard_inter"
                                        id="amount_reloadcard_inter" type="number" class="form-control"
                                        placeholder="Montant">
                                    @error('amount_reloadcard_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if (!is_null($amount_reloadcard_inter))
                                    <div class="col-span-12 mt-3">
                                        <label for="amount_reloadcard_inter" class="form-label">Montant à payer<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard_inter == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                <div
                                                    class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">

                                                    {{ $amount_deposit_nation }} {{ $usersCountry }}

                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @elseif ($provider_reloadcard_inter == 62)
                            <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12">
                                    <img class="object-none object-center bg-yellow-300 w-logo"
                                        src="{{ asset('public/dist/providers/bni.png') }}" alt="Bni">
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-12 mt-space">
                                    <label for="account_number_reload_card_inter" class="form-label">Numero du
                                        compte<span class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="account_number_reload_card_inter"
                                        id="account_number_reload_card_inter" type="text" class="form-control"
                                        placeholder="Numero du compte" value="" maxlength="10">
                                    @error('account_number_reload_card_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                <div class="col-span-6 mt-space">
                                    <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter"
                                        type="text" class="form-control" placeholder="Nom et prenom">
                                    @error('name_reloadcard_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-span-6 mt-space">
                                    <label for="amount_reloadcard_inter" class="form-label">Montant<span
                                            class="text-danger">
                                            *</span></label>
                                    <input wire:model.debounce.10ms="amount_reloadcard_inter"
                                        id="amount_reloadcard_inter" type="number" class="form-control"
                                        placeholder="Montant">
                                    @error('amount_reloadcard_inter')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                @if (!is_null($amount_reloadcard_inter))
                                    <div class="col-span-12 mt-3">
                                        <label for="amount_reloadcard_inter" class="form-label">Montant à payer<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard_inter == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                <div
                                                    class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                    {{ $amount_deposit_nation }}
                                                    {{ $usersCountry }}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @elseif ($provider_reloadcard_inter == 61)
                                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                                    <div class="col-span-12">
                                        <img class="object-none object-center bg-yellow-300 w-logo"
                                            src="{{ asset('public/dist/providers/uba.png') }}" alt="UBA CIV">
                                    </div>
                                </div>

                                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                    <div class="col-span-12 mt-space">
                                        <label for="account_number_reload_card_inter" class="form-label">Numero du
                                            compte<span class="text-danger">
                                                *</span></label>
                                        <input wire:model.defer="account_number_reload_card_inter"
                                            id="account_number_reload_card_inter" type="text" class="form-control"
                                            placeholder="Numero du compte" value="" maxlength="10">
                                        @error('account_number_reload_card_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                                    <div class="col-span-6 mt-space">
                                        <label for="name_reloadcard_inter" class="form-label">Nom et prenom<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.defer="name_reloadcard_inter" id="name_reloadcard_inter"
                                            type="text" class="form-control" placeholder="Nom et prenom">
                                        @error('name_reloadcard_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-span-6 mt-space">
                                        <label for="amount_reloadcard_inter" class="form-label">Montant<span
                                                class="text-danger">
                                                *</span></label>
                                        <input wire:model.debounce.10ms="amount_reloadcard_inter" id="amount_reloadcard_inter"
                                            type="number" class="form-control" placeholder="Montant">
                                        @error('amount_reloadcard_inter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (!is_null($amount_reloadcard_inter))
                                        <div class="col-span-12 mt-3">
                                            <label for="amount_reloadcard_inter" class="form-label">Montant à payer<span
                                                    class="text-danger">
                                                    *</span></label>
                                            @if ($amount_reloadcard_inter == '')
                                                <div class="text-gray-500 text-sm">
                                                    Entrer le montant
                                                </div>
                                            @else
                                                <div class="box px-5 py-3 ml-4 flex-1 zoom-in mr-6 ml-6">
                                                    <div
                                                        class="relative text-2xl 2xl:text-3xl font-medium leading-6 pl-3 2xl:pl-4">
                                                        {{ $amount_deposit_nation }} {{ $usersCountry }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                        @endif
                    @endif
                @endif

                <div class="modal-footer style-foo">
                    <button wire:click="cancelInterField" type="button"
                        class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                    <button wire:click="addInter" type="button" wire:loading.remove=""
                        class="btn btn-primary w-50">Envoyer la demande</button>
                    <button wire:loading.delay="" wire:target="addInter" wire:click="addInter"
                        type="button" class="btn btn-warning w-40"> Traitement ... </button>
                </div>

            @endif

        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-bank-created').listen(
                '.Created_wallet_bank',
                (e) => {
                    window.livewire.emit('notifyNewBank:' + e.id);
                },
            )

        });
    </script>
</div>
