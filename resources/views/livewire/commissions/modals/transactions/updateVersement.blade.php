<div wire:ignore.self id="update-versement-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">

        <div class="modal-container">

            @include('components.modal-header', [
                'title' => 'Validation versement ',
                'icon' => 'fas fa-check-circle',
                'service' => 'Versement',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelVersementField',
            ])
            @php
                $providersImages = [
                    108 => 'uba.png',
                    109 => 'gt_bank.png',
                    110 => 'versus.png',
                    111 => 'nsia.png',
                    112 => 'atlantique.png',
                    113 => 'bni.png',
                    114 => 'sgbci.png',
                    115 => 'sib.png',
                    116 => 'boa.jpeg',
                    117 => 'afg_bank.png',
                    118 => 'bda_bank.png',
                    119 => 'bridge_bank.jpeg',
                    120 => 'Orabank_bank.png',
                ];
                $providerImage = $providersImages[$provider_versement_id] ?? null;
            @endphp

            <div class="modal-body mt-1">
                <div class="col-span-12 text-center">
                    <img class="object-none object-center bg-yellow-300 w-logo"
                        src="{{ asset_url('dist/providers/' . $providerImage) }}" alt="Provider">
                </div>
                <div class="form-section">
                     <div class="field-group">

                        <div class="field full">
                            <label class="form-label required">Canal Versement</label>
                            <div class="field-value">
                                <i class="fas fa-network-wired"></i>
                                <span id="provider_name_xbet_edit"> {{ $provider_versement ?? null }}</span>
                            </div>

                        </div>
                       
                    </div>
                    <div class="field-group">

                       
                        <div class="field">
                            <label class="form-label required">Numero du compte</label>
                            <div class="field-value">
                                <i class="fas fa-user"></i>
                                <span id="numAccompte"> {{ $numAccompte ?? null }}</span>
                            </div>
                        </div>
                         <div class="field">
                            <label class="form-label required">Nom & Prénom</label>
                            <div class="field-value">
                                <i class="fas fa-network-wired"></i>
                                <span id="name"> {{ $name ?? null }}</span>
                            </div>

                        </div>
                    </div>
                    <div class="field-group">
                        <div class="field">
                            <label class="form-label required">Code Guichet</label>
                            <div class="field-value">
                                <i class="fas fa-id-card"></i>
                                <span id="guichet_edit">{{ $guichet_edit ?? null }}</span>
                            </div>
                        </div>
                        <div class="field">
                            <label class="form-label required">CLE RIB</label>
                            <div class="field-value">
                                <i class="fas fa-id-card"></i>
                                <span id="num_auther_edit">{{ $num_auther_edit ?? null }}
                            </div>
                        </div>
                    </div>


                    <div class="field-group">
                        <div class="field">
                            <label class="form-label required">Code banque</label>
                            <div class="field-value">
                                <i class="fas fa-id-card"></i>
                                <span id="bank_code_edit">{{ $bank_code_edit ?? null }}</span>
                            </div>
                        </div>
                        <div class="field">
                            <label class="form-label required">Montant</label>
                            <div class="field-value">
                                <i class="fas fa-money-bill-wave"></i>
                                <span id="versementAmount">{{ $versementAmount ?? null }}
                                    {{ $devise_user ?? null }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 mt-space">
                    <label for="versementDescription" class="form-label">Commentaire</label>
                    <textarea wire:model.defer="versementDescription" id="versementDescription" type="text" class="form-control"
                        cols="13" rows="3">
                    </textarea>
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">

                <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700"
                    wire:click="tryOpenModal('validated-versement-modal')">
                    <input wire:model="versementStatus" id="bordered-radio-70" type="radio" value="SUCCESS"
                        onclick="event.stopPropagation()" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                    <label for="bordered-radio-70"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                        <i class="fas fa-check-circle style_success"></i>
                        Accepté
                    </label>
                </div>

                <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700"
                    wire:click="tryOpenModal('validated-versement-modal')">
                    <input wire:model="versementStatus" id="bordered-radio-71" onclick="event.stopPropagation()"
                        type="radio" value="ERROR" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                    <label for="bordered-radio-71"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                        <i class="fas fa-times-circle style_error"></i>
                        Refusé
                    </label>
                </div>
            </div>

        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_versement')
