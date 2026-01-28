<div wire:ignore.self id="update-bankSend-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            @include('components.modal-header', [
                'title' => 'Valider demande bank Send',
                'icon' => 'fas fa-check-circle',
                'service' => 'Bank Send',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelBankSendField',
            ])
            <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Transfers bank send
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                @if ($type_image == 'Passeport')
                    <div class="col-span-12">
                        <div class="field field-full">
                            <label class="form-label required">Image de la carte</label>

                            <div class="field-value flex items-center gap-2">
                                <i class="fas fa-qrcode"></i>

                                @if ($image_bank_edit)
                                    @php
                                        $img = asset_url($image_bank_edit) ?? asset_url('image_inters/' . $image_djamo);
                                    @endphp

                                    <a href="{{ $img }}" download target="_blank" class="relative group">
                                        <img src="{{ $img }}"
                                            class="w-24 h-24 object-contain rounded border cursor-pointer" />

                                        <!-- Icône téléchargement -->
                                        <span
                                            class="absolute inset-0 flex items-center justify-center
                                   bg-black/40 text-white opacity-0 group-hover:opacity-100
                                   rounded transition">
                                            <i class="fas fa-download text-lg"></i>
                                        </span>
                                    </a>
                                @else
                                    <span class="text-gray-500">Aucune image</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-span-6">
                        <div class="field field-full">
                            <label class="form-label required">Image Front de la carte</label>

                            <div class="field-value flex items-center gap-2">
                                <i class="fas fa-qrcode"></i>

                                @if ($image_bank_edit)
                                    @php
                                        $imgFront =
                                            asset_url($image_bank_edit) ?? asset_url('image_inters/' . $image_djamo);
                                    @endphp

                                    <a href="{{ $imgFront }}" download target="_blank" class="relative group">
                                        <img src="{{ $imgFront }}"
                                            class="w-24 h-24 object-contain rounded border cursor-pointer">

                                        <span
                                            class="absolute inset-0 flex items-center justify-center
                                 bg-black/40 text-white opacity-0 group-hover:opacity-100
                                 rounded transition">
                                            <i class="fas fa-download"></i>
                                        </span>
                                    </a>
                                @else
                                    <span class="text-gray-500">Aucune image</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-span-6">
                        <div class="field field-full">
                            <label class="form-label required">Image arrière de la carte</label>

                            <div class="field-value flex items-center gap-2">
                                <i class="fas fa-qrcode"></i>

                                @if ($image_back_bank_edit)
                                    @php
                                        $imgBack =
                                            asset_url($image_back_bank_edit) ??
                                            asset_url('image_inters/' . $image_djamo);
                                    @endphp

                                    <a href="{{ $imgBack }}" download target="_blank" class="relative group">
                                        <img src="{{ $imgBack }}"
                                            class="w-24 h-24 object-contain rounded border cursor-pointer">

                                        <span
                                            class="absolute inset-0 flex items-center justify-center
                                 bg-black/40 text-white opacity-0 group-hover:opacity-100
                                 rounded transition">
                                            <i class="fas fa-download"></i>
                                        </span>
                                    </a>
                                @else
                                    <span class="text-gray-500">Aucune image</span>
                                @endif
                            </div>
                        </div>
                    </div>

                @endif
                <div class="col-span-4 mt-3">
                    <label class="form-label">Canal transfers d'argent <span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-money-bill-transfer text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-medium">{{ $provider_bank_edit ?? 'Non spécifié' }}</span>
                    </div>
                </div>
                <div class="col-span-4 mt-3">
                    <label class="form-label">Nom & prenom du bénéficiaire <span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-phone text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-mono">{{ $name_bank_edit ?? 'Non renseigné' }}</span>
                    </div>
                </div>
                <div class="col-span-4 mt-3">
                    <label class="form-label">Numero de telephone du
                        bénéficiaire <span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-money-bill-transfer text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-medium">{{ $phone_bank_edit ?? 'Non spécifié' }}</span>
                    </div>
                </div>
                <div class="col-span-4">
                    <label class="form-label">Numero du compte du bénéficiaire <span
                            class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-phone text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-mono">{{ $account_number_bank_edit ?? 'Non renseigné' }}</span>
                    </div>
                </div>
                <div class="col-span-4">
                    <label class="form-label">Nom & prenom de l'expediteur <span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-money-bill-transfer text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-medium">{{ $name_sender_bank_edit ?? 'Non spécifié' }}</span>
                    </div>
                </div>
                <div class="col-span-4">
                    <label class="form-label">Numero de telephone de
                        l'expediteur <span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-phone text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-mono">{{ $phone_exp_bank_edit ?? 'Non renseigné' }}</span>
                    </div>
                </div>
                <div class="col-span-4">
                    <label class="form-label">Numero piece<span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-money-bill-transfer text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-medium">{{ $num_piece_bank_edit ?? 'Non spécifié' }}</span>
                    </div>
                </div>
                <div class="col-span-4">
                    <label class="form-label">Date piece <span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-phone text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-mono">{{ $date_bank_edit ?? 'Non renseigné' }}</span>
                    </div>
                </div>
                <div class="col-span-4">
                    <label class="form-label">Nom de la banque<span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-money-bill-transfer text-gray-500 mr-3"></i>
                        <span
                            class="text-gray-800 font-medium">{{ $account_number_bank_edit ?? 'Non spécifié' }}</span>
                    </div>
                </div>
                <div class="col-span-4">
                    <label class="form-label">Code swift/IFSC/Bic Code <span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-phone text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-mono">{{ $swit_bank_edit ?? 'Non renseigné' }}</span>
                    </div>
                </div>

                <div class="col-span-4 ">
                    <label class="form-label">Montant <span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-money-bill-wave text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-medium">{{ $amount_bank_edit ?? '0' }}</span>
                        <span class="ml-2 text-gray-600 font-mono">{{ $currency_custumers ?? '' }}</span>
                    </div>
                </div>
                <div class="col-span-4 ">
                    <label class="form-label">Pays <span class="text-danger">*</span></label>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="fas fa-globe text-gray-500 mr-3"></i>
                        <span class="text-gray-800 font-medium">{{ $countrydetails ?? 'Non spécifié' }}</span>
                    </div>
                </div>
            </div>


            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3">
                <div class="col-span-12 mt-space">
                    <label for="bank_description_edit" class="form-label">Commentaire</label>
                    <textarea wire:model="bank_description_edit" id="bank_description_edit" type="text" class="form-control"
                        cols="13" rows="3">
                    </textarea>

                </div>
            </div>
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 ">

                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="tryOpenModalBankSend('validated-bankSend-modal')" wire:model="bankStatus"
                        id="bordered-radio-100" type="radio" value="SUCCESS"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-100"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                        <i class="fas fa-check-circle style_success"></i>

                        Accepté</label>
                    @error('bankStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="tryOpenModalBankSend('validated-bankSend-modal')" wire:model="bankStatus"
                        id="bordered-radio-99" type="radio" value="ERROR"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-99"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                        <i class="fas fa-times-circle style_error"></i>
                        Refusé</label>
                    @error('bankStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_bankSend')
