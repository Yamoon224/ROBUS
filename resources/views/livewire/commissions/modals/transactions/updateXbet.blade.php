<div wire:ignore.self id="update-xbet-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">

        <div class="modal-container">

            @include('components.modal-header', [
                'title' => 'Valider la demande Xbet',
                'icon' => 'fas fa-check-circle',
                'service' => '1Xbet',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelXbetField',
            ])


            <div class="modal-body mt-1">

                @if ($choose_type_xbet == 1)

               
                    <div class="form-section">
                        <!-- Montant reçu -->
                         <div class="amount-section">
                            <div class="amount-label">Type de transaction</div>
                            <div class="amount-received">
                                <div class="flex items-baseline justify-between">
                                    <span class="amount-received-value">
                                        Depot
                                    </span>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="field-group">

                            <div class="field">
                                <label class="form-label required">Canal 1XBET</label>
                                <div class="field-value">
                                    <i class="fas fa-network-wired"></i>
                                    <span id="provider_name_xbet_edit"> {{ $xbetProvider ?? null }}</span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="form-label required">Numéro du compte</label>
                                <div class="field-value">
                                    <i class="fas fa-user"></i>
                                    <span id="id_compte"> {{ $id_compte ?? null }}</span>
                                </div>
                            </div>
                        </div>


                        <div class="field-group">
                            <div class="field">
                                <label class="form-label required">Nom & prénom du client</label>
                                <div class="field-value">
                                    <i class="fas fa-id-card"></i>
                                    <span id="full_nameXbet">{{ $full_nameXbet ?? null }}</span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="form-label required">Montant</label>
                                <div class="field-value">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span id="amount_xbet_edit">{{ formatDisplayAmount($amount_xbet ?? null) }}
                                        {{ $devise_user ?? null }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else

                     
                    <div class="form-section" id="type1">
                        
                        <div class="field-group">
                            <div class="field">
                                <label class="form-label required">Canal 1XBET</label>
                                <div class="field-value">
                                    <i class="fas fa-network-wired"></i>
                                    <span id="provider_name_xbet_edit"> {{ $xbetProvider ?? null }}</span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="form-label required">Numéro du compte</label>
                                <div class="field-value">
                                    <i class="fas fa-user"></i>
                                    <span id="id_compte"> {{ $id_accompte ?? null }}</span>
                                </div>
                            </div>

                        </div>


                        <div class="field-group">
                            <div class="field">
                                <label class="form-label required">Pays</label>
                                <div class="field-value">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span id="countrie">{{ $countrie ?? null }}</span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="form-label required">Ville</label>
                                <div class="field-value">
                                    <i class="fas fa-city"></i>
                                    <span id="citie_xbet"> {{ $citie_xbet ?? null }}</span>
                                </div>
                            </div>

                        </div>

                        <div class="field-group">
                            <div class="field">
                                <label class="form-label required">Code retrait du compte</label>
                                <div class="field-value">
                                    <i class="fas fa-user"></i>
                                    <span id="id_compte"> {{ $code_drawal ?? null }}</span>
                                </div>
                            </div>
                            <div class="field">
                                <label class="form-label required">Montant</label>
                                <div class="field-value">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span id="amount_xbet"> {{ formatDisplayAmount($amount_xbet ?? null) }}
                                        {{ $currency_custumers ?? null }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                 <div class="form-section mb-3">
                    <h4 class="section-title mb-4">Information Pays</h4>
                    <div class="field-group">
                        <div class="field">
                            <label class="form-label required">
                                <i class="fas fa-coins mr-2 text-primary"></i>
                                Pays envoyeur
                            </label>
                            <div class="field-value">
                                <i class="fas fa-globe"></i>
                                <span
                                    class="font-medium">{{ $country_receved ?? 'Non spécifié' }}</span>
                            </div>
                        </div>
                        <div class="field">
                            <label class="form-label required">
                                <i class="fas fa-globe-africa mr-2 text-primary"></i>
                                Pays receveur
                            </label>
                            <div class="field-value">
                                <i class="fas fa-globe"></i>
                                <span
                                    class="font-medium">{{ $country_sendeur ?? 'Non spécifié' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                <div class="col-span-12 mt-space">
                    <label for="xbetDescription" class="form-label">Commentaire</label>
                    <textarea wire:model.defer="xbetDescription" id="xbetDescription" type="text" class="form-control" cols="13"
                        rows="3">
                    </textarea>
                </div>
            </div>

            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3 mb-3">

                <div class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700"
                    wire:click="$emit('modal-xbet-validted-show', 'validated-xbet-modal')">
                    <input wire:model="xbetStatus" id="bordered-radio-56" type="radio" value="SUCCESS"
                        onclick="event.stopPropagation()" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300">
                    <label for="bordered-radio-56"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                        <i class="fas fa-check-circle style_success"></i>
                        Accepté
                    </label>

                    @error('xbetStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700"
                    wire:click="$emit('modal-xbet-validted-show' , 'validated-xbet-modal')">
                    <input wire:model="xbetStatus" id="bordered-radio-57" onclick="event.stopPropagation()"
                        type="radio" value="ERROR"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-57"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                        <i class="fas fa-times-circle style_error"></i>
                        Refusé</label>

                    @error('xbetStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_xbet')
