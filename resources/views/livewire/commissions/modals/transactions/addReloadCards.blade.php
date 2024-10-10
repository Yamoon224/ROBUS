<!-- Modal -->
<div wire:ignore.self id="create-reloadCards-modal" class="modal  hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">

    @if ($stepReloadCard === 1)

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header alert alert-primary">
                    <h2 class="font-medium text-base mr-auto">Demande de rechargement de carte</h2>
                </div>
                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $stepReloadCard != 1 ? 'btn-default' : 'btn-primary' }}">1</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Saisissez les informations
                            requises
                        </div>
                    </div>
                </div>
                <a wire:click="cancelReloadCardField"
                    style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="x" data-lucide="x"
                        class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </a>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-12 mt-space">
                        <label for="provider_reloadcard" class="form-label">Canal<span class="text-danger">
                                *</span></label>
                        <select wire:model="provider_reloadcard" id="provider_reloadcard" class="form-control">
                            <option value="null" disabled>Selectionnez le canal</option>
                            @foreach ($provider_reloads as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                        @error('provider_reloadcard')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @if (!is_null($provider_reloadcard))
                    @if ($provider_reloadcard == 16)
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset('public/dist/providers/bni.png') }}" alt="Bni">
                            </div>
                        </div>
                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12 mt-space">
                                <label for="account_number_reload_card" class="form-label">Numero du compte<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="account_number_reload_card" id="account_number_reload_card"
                                    type="text" class="form-control" placeholder="Numero du compte" value=""
                                    maxlength="10">
                                @error('account_number_reload_card')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-space">
                                <label for="name_reloadcard" class="form-label">Nom et prenom<span class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="name_reloadcard" id="name_reloadcard" type="text"
                                    class="form-control" placeholder="Nom et prenom">
                                @error('name_reloadcard')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-6 mt-space">
                                <label for="amount_reloadcard" class="form-label">Montant<span class="text-danger">
                                        *</span></label>
                                <input wire:model.debounce.10ms="amount_reloadcard" id="amount_reloadcard"
                                    type="number" class="form-control" placeholder="Montant">
                                @error('amount_reloadcard')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if (!is_null($amount_reloadcard))

                                @if ($user_auth->is_under_distributer == 1)
                                    <div class="col-span-6 mt-3">
                                        <label for="amount_reloadcard" class="form-label">Montant commission<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_deposit_nation_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="col-span-6 mt-3">
                                        <label for="amount_reloadcard" class="form-label">Montant à payer<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_deposit_nation_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="col-span-6 mt-3">
                                    <label for="amount_reloadcard" class="form-label">Montant à
                                        recevoir<span class="text-danger">
                                            *</span></label>
                                    @if ($amount_reloadcard == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_deposit_nation_receive ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @elseif ($provider_reloadcard == 15)
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset('public/dist/providers/bsic.png') }}" alt="BSIC">
                            </div>
                        </div>

                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12 mt-space">
                                <label for="account_number_reload_card" class="form-label">Numero du compte<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="account_number_reload_card" id="account_number_reload_card"
                                    type="text" class="form-control" placeholder="Numero du compte"
                                    value="" maxlength="10">
                                @error('account_number_reload_card')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-space">
                                <label for="name_reloadcard" class="form-label">Nom et prenom<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="name_reloadcard" id="name_reloadcard" type="text"
                                    class="form-control" placeholder="Nom et prenom">
                                @error('name_reloadcard')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-6 mt-space">
                                <label for="amount_reloadcard" class="form-label">Montant<span class="text-danger">
                                        *</span></label>
                                <input wire:model.debounce.10ms="amount_reloadcard" id="amount_reloadcard"
                                    type="number" class="form-control" placeholder="Montant">
                                @error('amount_reloadcard')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if (!is_null($amount_reloadcard))
                                @if ($user_auth->is_under_distributer == 1)

                                    <div class="col-span-6 mt-3">
                                        <label for="amount_reloadcard" class="form-label">Montant commission<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_deposit_nation_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="col-span-6 mt-3">
                                        <label for="amount_reloadcard" class="form-label">Montant à payer <span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_deposit_nation_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="col-span-6 mt-3">
                                    <label for="amount_reloadcard" class="form-label">Montant à
                                        recevoir<span class="text-danger">
                                            *</span></label>
                                    @if ($amount_reloadcard == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_deposit_nation_receive ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @elseif ($provider_reloadcard == 14)
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset('public/dist/providers/ecobank.png') }}" alt="Ecobank">
                            </div>
                        </div>

                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12 mt-space">
                                <label for="account_number_reload_card" class="form-label">Numero du compte<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="account_number_reload_card" id="account_number_reload_card"
                                    type="text" class="form-control" placeholder="Numero du compte"
                                    value="" maxlength="10">
                                @error('account_number_reload_card')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-space">
                                <label for="name_reloadcard" class="form-label">Nom et prenom<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="name_reloadcard" id="name_reloadcard" type="text"
                                    class="form-control" placeholder="Nom et prenom">
                                @error('name_reloadcard')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-6 mt-space">
                                <label for="amount_reloadcard" class="form-label">Montant<span class="text-danger">
                                        *</span></label>
                                <input wire:model.debounce.10ms="amount_reloadcard" id="amount_reloadcard"
                                    type="number" class="form-control" placeholder="Montant">
                                @error('amount_reloadcard')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if (!is_null($amount_reloadcard))

                                @if ($user_auth->is_under_distributer == 1)
                                    <div class="col-span-6 mt-3">
                                        <label for="amount_reloadcard" class="form-label">Montant commission<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_deposit_nation_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="col-span-6 mt-3">
                                        <label for="amount_reloadcard" class="form-label">Montant à payer<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_deposit_nation_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="col-span-6 mt-3">
                                    <label for="amount_reloadcard" class="form-label">Montant à
                                        recevoir<span class="text-danger">
                                            *</span></label>
                                    @if ($amount_reloadcard == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_deposit_nation_receive ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @elseif ($provider_reloadcard == 17)
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset('public/dist/providers/uba.png') }}" alt="UBA BF">
                            </div>
                        </div>

                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12 mt-space">
                                <label for="account_number_reload_card" class="form-label">Numero du compte<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="account_number_reload_card" id="account_number_reload_card"
                                    type="text" class="form-control" placeholder="Numero du compte"
                                    value="" maxlength="10">
                                @error('account_number_reload_card')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-space">
                                <label for="name_reloadcard" class="form-label">Nom et prenom<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="name_reloadcard" id="name_reloadcard" type="text"
                                    class="form-control" placeholder="Nom et prenom">
                                @error('name_reloadcard')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-6 mt-space">
                                <label for="amount_reloadcard" class="form-label">Montant<span class="text-danger">
                                        *</span></label>
                                <input wire:model.debounce.10ms="amount_reloadcard" id="amount_reloadcard"
                                    type="number" class="form-control" placeholder="Montant">
                                @error('amount_reloadcard')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if (!is_null($amount_reloadcard))
                                @if ($user_auth->is_under_distributer == 1)
                                    <div class="col-span-6 mt-3">
                                        <label for="amount_reloadcard" class="form-label">Montant commission<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_deposit_nation_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="col-span-6 mt-3">
                                        <label for="amount_reloadcard" class="form-label">Montant à payer<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_deposit_nation_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>

                                @endif
                                <div class="col-span-6 mt-3">
                                    <label for="amount_reloadcard" class="form-label">Montant à
                                        recevoir<span class="text-danger">
                                            *</span></label>
                                    @if ($amount_reloadcard == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_deposit_nation_receive ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @elseif ($provider_reloadcard == 13)
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                            <div class="col-span-12">
                                <img class="object-none object-center bg-yellow-300 w-logo"
                                    src="{{ asset('public/dist/providers/uba.png') }}" alt="UBA CIV">
                            </div>
                        </div>

                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-12 mt-space">
                                <label for="account_number_reload_card" class="form-label">Numero du compte<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="account_number_reload_card" id="account_number_reload_card"
                                    type="text" class="form-control" placeholder="Numero du compte"
                                    value="" maxlength="10">
                                @error('account_number_reload_card')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-space">
                                <label for="name_reloadcard" class="form-label">Nom et prenom<span
                                        class="text-danger">
                                        *</span></label>
                                <input wire:model.defer="name_reloadcard" id="name_reloadcard" type="text"
                                    class="form-control" placeholder="Nom et prenom">
                                @error('name_reloadcard')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-span-6 mt-space">
                                <label for="amount_reloadcard" class="form-label">Montant<span class="text-danger">
                                        *</span></label>
                                <input wire:model.debounce.10ms="amount_reloadcard" id="amount_reloadcard"
                                    type="number" class="form-control" placeholder="Montant">
                                @error('amount_reloadcard')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            @if (!is_null($amount_reloadcard))

                                @if ($user_auth->is_under_distributer == 1)
                                    <div class="col-span-6 mt-3">
                                        <label for="amount_reloadcard" class="form-label">Montant commission<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_deposit_nation_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="col-span-6 mt-3">
                                        <label for="amount_reloadcard" class="form-label">Montant à payer<span
                                                class="text-danger">
                                                *</span></label>
                                        @if ($amount_reloadcard == '')
                                            <div class="text-gray-500 text-sm">
                                                Entrer le montant
                                            </div>
                                        @else
                                            <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                                style="font-size: 25px; color:white;">
                                                {{ $amount_deposit_nation_pay ?? null }}
                                                {{ $usersCountry }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                                <div class="col-span-6 mt-3">
                                    <label for="amount_reloadcard" class="form-label">Montant à
                                        recevoir<span class="text-danger">
                                            *</span></label>
                                    @if ($amount_reloadcard == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_deposit_nation_receive ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                    @endif
                @endif
                <div class="modal-footer style-foo" style="margin-top: -1rem;">
                    <button wire:click="cancelReloadCardField" type="button"
                        class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                    <button wire:click="addReloadCard" type="button" wire:loading.remove=""
                        class="btn btn-primary w-50">Envoyer la demande</button>
                    <div wire:loading wire:target="addReloadCard">
                        Traitement en cours...
                    </div>
                </div>
            </div>
        </div>
    @elseif ($stepReloadCard === 2)
        <div class="modal-dialog modal-lg mt-3">
            <div class="modal-content">
                <div class="modal-header alert alert-primary">
                    <h2 class="font-medium text-base mr-auto">Validation de mot de passe </h2>
                </div>
                <div
                    class="relative before:hidden before:lg:block before:absolute before:w-[69%] before:h-[3px] before:top-0 before:bottom-0 before:mt-4 before:bg-slate-100 before:dark:bg-darkmode-400 flex flex-col lg:flex-row justify-center px-5 sm:px-20 mt-3">
                    <div class="intro-x lg:text-center flex items-center lg:block flex-1 z-10">
                        <a href="#step-1"
                            class="w-10 h-10 rounded-full btn btn-circle {{ $stepReloadCard != 2 ? 'btn-default' : 'btn-secondary' }}">1</a>
                        <div class="lg:w-32 font-medium text-base lg:mt-3 ml-3 lg:mx-auto"> Validations

                        </div>
                    </div>
                </div>

                <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 px-10 mt-3">

                    <div class="col-span-6 mt-3">
                        <label class="form-label">Nom & prenom<span class="text-danger">
                                *</span></label>

                        <div class="flex">

                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $name_reloadcard ?? null }}
                            </div>
                        </div>


                    </div>
                    <div class="col-span-6 mt-3">Montant<span class="text-danger">
                            *</span></label>
                        <div class="flex">


                            <div
                                class="mr-6 z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-validated">
                                {{ $amount_reloadcard ?? null }} {{ $usersCountry }}
                            </div>
                        </div>

                    </div>
                    <div class="col-span-12 mt-1">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Mot de passe<span class="text-danger">
                                    *</span></label>
                        </div>

                        <div class="input-group input-group-merge form-password-toggle">
                            <input wire:model="password_validated" placeholder="mot de passe"
                                :type="show ? 'password' : 'text'" id="password" tabindex="2"
                                aria-describedby="password" name="password" required autocomplete="current-password"
                                class="form-control
            focus:placeholder-gray-500
            focus:bg-white 
            focus:border-gray-600  
         focus:outline-none">
                            <div class="input-group-text cursor-pointer">
                                <svg class="h-4 text-gray-700" fill="none" @click="show = !show"
                                    :class="{ 'hidden': !show, 'block': show }" xmlns="http://www.w3.org/2000/svg"
                                    viewbox="0 0 576 512">
                                    <path fill="currentColor"
                                        d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z">
                                    </path>
                                </svg>

                                <svg class="h-4 text-gray-700" fill="none" @click="show = !show"
                                    :class="{ 'block': !show, 'hidden': show }" xmlns="http://www.w3.org/2000/svg"
                                    viewbox="0 0 640 512">
                                    <path fill="currentColor"
                                        d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z">
                                    </path>
                                </svg>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        @error('password')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer style-foo">
                    <button wire:click="cancelReloadCardField" type="button"
                        class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                    <button wire:click="addValidatedReloadCard" type="button" wire:loading.remove=""
                        class="btn btn-primary w-50">Envoyer la demande</button>
                    <div wire:loading wire:target="addValidatedReloadCard">
                        Traitement en cours...
                    </div>
                </div>
            </div>
        </div>

    @endif

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            Echo.channel('wallet-card-created').listen(
                '.Created_wallet_card',
                (e) => {
                    window.livewire.emit('notifyNewCard:' + e.id);
                },
            )

        });
    </script>
</div>
