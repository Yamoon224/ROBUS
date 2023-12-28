<!-- Modal -->
<div wire:ignore.self id="create-virementBank-modal" class="modal  hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header alert alert-primary">
                <h2 class="font-medium text-base mr-auto">Demande de virement bancaire</h2>
            </div>
            <a wire:click="cancelVirementField"
                style="position: absolute; right: 0px; top: 0px; margin-top: 0.75rem; margin-right: 0.75rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="x"
                    data-lucide="x" class="lucide lucide-x w-8 h-8 text-slate-400" style="color: white;">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </a>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                <div class="col-span-12 mt-space">
                    <label for="provider_virement" class="form-label">Canal<span class="text-danger">
                            *</span></label>
                    <select wire:model="provider_virement" id="provider_virement" class="form-control">
                        <option value="null" disabled>Selectionnez le canal</option>
                        @foreach ($provider_virements as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('provider_virement')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @if (!is_null($provider_virement))
                @if ($provider_virement == 12)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                        <div class="col-span-12">
                            <img class="object-none object-center bg-yellow-300 w-logo"
                                src="{{ asset('public/dist/providers/nsia.png') }}" alt="NSIA">
                        </div>
                    </div>

                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">

                        <div class="col-span-12 mt-space">
                            <label for="account_number_user_bank" class="form-label">Numero du compte<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="account_number_user_bank" id="account_number_user_bank"
                                type="number" class="form-control" placeholder="Numero du compte" />
                            @error('account_number_user_bank')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6 mt-space">
                            <label for="firstname_user" class="form-label">Nom du client<span class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="firstname_user" id="firstname_user" type="text"
                                class="form-control" placeholder="Nom du client">
                            @error('firstname_user')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-6 mt-space">
                            <label for="lastname_user" class="form-label">Prenom du client<span class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="lastname_user" id="lastname_user" type="text"
                                class="form-control" placeholder="Prenom du client">
                            @error('lastname_user')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6 mt-space">
                            <label for="phone_user" class="form-label">Telephone<span id="valid_phone"
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="phone_user" id="phone_user" type="text" class="form-control"
                                placeholder="Numero du telephone">
                            @error('phone_user')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-6 mt-space">
                            <label for="amount_virement" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
                            <input wire:model.debounce.10ms="amount_virement" id="amount_virement" type="number"
                                class="form-control" placeholder="Montant">
                            @error('amount_virement')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (!is_null($amount_virement))
                            @if ($user_auth->is_under_distributer == 1)
                                <div class="col-span-6 mt-3">
                                    <label for="amount_virement" class="form-label">Montant commission<span
                                            class="text-danger">
                                            *</span></label>
                                    @if ($amount_virement == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_virement_nation_pay ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="col-span-6 mt-3">
                                    <label for="amount_virement" class="form-label">Montant à payer<span
                                            class="text-danger">
                                            *</span></label>
                                    @if ($amount_virement == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_virement_nation_pay ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="col-span-6 mt-3">
                                <label for="amount_virement" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                @if ($amount_virement == '')
                                    <div class="text-gray-500 text-sm">
                                        Entrer le montant
                                    </div>
                                @else
                                    <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                        style="font-size: 25px; color:white;">
                                        {{ $amount_virement_nation_receive ?? null }}
                                        {{ $usersCountry }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @elseif ($provider_virement == 11)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3 style_image_providers">
                        <div class="col-span-12">
                            <img class="object-none object-center bg-yellow-300 w-logo"
                                src="{{ asset('public/dist/providers/ecobank.png') }}" alt="ECOKBAK">
                        </div>
                    </div>

                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-12 mt-space">
                            <label for="account_number_user_bank" class="form-label">Numero du compte<span
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="account_number_user_bank" id="account_number_user_bank"
                                type="number" class="form-control" placeholder="Numero du compte">
                            @error('account_number_user_bank')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6 mt-space">
                            <label for="firstname_user" class="form-label">Nom du client<span class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="firstname_user" id="firstname_user" type="text"
                                class="form-control" placeholder="Nom du client">
                            @error('firstname_user')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-6 mt-space">
                            <label for="lastname_user" class="form-label">Prenom du client<span class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="lastname_user" id="lastname_user" type="text"
                                class="form-control" placeholder="Prenom du client">
                            @error('lastname_user')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6 mt-space">
                            <label for="phone_user" class="form-label">Telephone<span id="valid_phone"
                                    class="text-danger">
                                    *</span></label>
                            <input wire:model.defer="phone_user" id="phone_user" type="text" class="form-control"
                                placeholder="Numero du telephone">
                            @error('phone_user')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-span-6 mt-space">
                            <label for="amount_virement" class="form-label">Montant<span class="text-danger">
                                    *</span></label>
                            <input wire:model.debounce.10ms="amount_virement" id="amount_virement" type="number"
                                class="form-control" placeholder="Montant">
                            @error('amount_virement')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (!is_null($amount_virement))
                            @if ($user_auth->is_under_distributer == 1)
                                <div class="col-span-6 mt-3">
                                    <label for="amount_virement" class="form-label">Montant commission<span
                                            class="text-danger">
                                            *</span></label>
                                    @if ($amount_virement == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_virement_nation_pay ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="col-span-6 mt-3">
                                    <label for="amount_virement" class="form-label">Montant à payer<span
                                            class="text-danger">
                                            *</span></label>
                                    @if ($amount_virement == '')
                                        <div class="text-gray-500 text-sm">
                                            Entrer le montant
                                        </div>
                                    @else
                                        <div class="px-5 py-3  flex-1 zoom-in  alert alert-primary   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                            style="font-size: 25px; color:white;">
                                            {{ $amount_virement_nation_pay ?? null }}
                                            {{ $usersCountry }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="col-span-6 mt-3">
                                <label for="amount_virement" class="form-label">Montant à
                                    recevoir<span class="text-danger">
                                        *</span></label>
                                @if ($amount_virement == '')
                                    <div class="text-gray-500 text-sm">
                                        Entrer le montant
                                    </div>
                                @else
                                    <div class="px-5 py-3  flex-1 zoom-in  alert alert-success   relative  font-medium leading-6 pl-3 2xl:pl-4"
                                        style="font-size: 25px; color:white;">
                                        {{ $amount_virement_nation_receive ?? null }}
                                        {{ $usersCountry }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

            @endif
            <div class="modal-footer style-foo" style="margin-top: -1rem;">
                <button wire:click="cancelVirementField" type="button"
                    class="btn btn-outline-secondary w-20 mr-1">Annuler</button>
                <button wire:click="addVirement" type="button" wire:loading.remove=""
                    class="btn btn-primary w-50">Envoyer la demande</button>
                    <div  wire:loading wire:target="addVirement">
                        Traitement en cours...
                    </div>
            </div>
        </div>
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
