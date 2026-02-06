<div wire:ignore.self id="update-interTransfers-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            @include('components.modal-header', [
                'title' => 'Valider demande internationale',
                'icon' => 'fas fa-check-circle',
                'service' => 'Internationale',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelInterField',
            ])


            @if ($choose_type_paiement == 1)
                <!-- Transfers d'argent -->
                @if ($type_inter_image == 'Passeport')
                    <div class="col-span-12">
                        <div class="field field-full">
                            <label class="form-label required">Image de la carte</label>

                            <div class="field-value flex items-center gap-2">
                                <i class="fas fa-qrcode"></i>

                                @if ($image_frontend_inter_edit)
                                    @php
                                        $img = asset_url($image_frontend_inter_edit) ?? asset_url('image_inters/' . $image_frontend_inter_edit);
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

                                @if ($image_frontend_inter_edit)
                                    @php
                                        $imgFront =
                                            asset_url($image_frontend_inter_edit) ?? asset_url('image_inters/' . $image_frontend_inter_edit);
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

                                @if ($image_backend_inter_edit)
                                    @php
                                        $imgBack =
                                            asset_url($image_backend_inter_edit) ??
                                            asset_url('image_inters/' . $image_backend_inter_edit);
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
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Numero piece <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-money-bill-wave text-gray-500 mr-3"></i>
                            <span class="text-gray-800 font-medium">{{ $num_piece_inter_edit ?? '0' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Validite piece <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-globe text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $validated_piece_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-6">
                        <label class="form-label">Canal transfers d'argent <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-money-bill-transfer text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $provider_phone_credit_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6">
                        <label class="form-label">Numéro du bénéficiaire<span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-phone text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-mono">{{ $number_phone_credit_inter_edit ?? 'Non renseigné' }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Nom du béneficiaire <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-money-bill-wave text-gray-500 mr-3"></i>
                            <span class="text-gray-800 font-medium">{{ $name_benef_inter_edit ?? '0' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Nom du déposant <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-globe text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $name_sender_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                </div>
                 <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Montant <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-money-bill-wave text-gray-500 mr-3"></i>
                            <span class="text-gray-800 font-medium">{{ $amount_virement_inter_edit ?? '0' }}</span>
                            <span class="ml-2 text-gray-600 font-mono">{{ $devise_user ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Pays <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-globe text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $country_inter_robus_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                </div>
            @elseif($choose_type_paiement == 5)
                <!-- Paiement facture -->
                <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Paiement facture</div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-1">
                    <div class="col-span-12">
                        <label class="form-label">Canal paiement facture <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-credit-card text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $provider_phone_credit_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                </div>

                @if ($provider_inter_edit == 52)
                    @if (!is_null($paiementChoix_phone_edit))
                        @php
                            $phoneNumberLabel =
                                $paiementChoix_phone_edit == 1
                                    ? 'Numéro du téléphone (10 chiffres)'
                                    : 'Numéro du décodeur (14 chiffres)';
                            $phoneNumberValue =
                                $paiementChoix_phone_edit == 1
                                    ? $paiement_phone_canal_edit ?? 'Non renseigné'
                                    : $paiement_decoder_canal_edit ?? 'Non renseigné';
                        @endphp
                        <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-space-padding">
                                <label class="form-label">Offre <span class="text-danger">*</span></label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="fas fa-tag text-gray-500 mr-3"></i>
                                    <span
                                        class="text-gray-800 font-medium">{{ $offer_paiement_edit ?? 'Non spécifié' }}</span>
                                </div>
                            </div>
                            <div class="col-span-6 mt-space-padding">
                                <label class="form-label">{{ $phoneNumberLabel }} <span
                                        class="text-danger">*</span></label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="fas fa-mobile-alt text-gray-500 mr-3"></i>
                                    <span class="text-gray-800 font-mono">{{ $phoneNumberValue }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-space-padding">
                                <label class="form-label">Montant <span class="text-danger">*</span></label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="fas fa-money-bill-wave text-gray-500 mr-3"></i>
                                    <span
                                        class="text-gray-800 font-medium">{{ $amount_virement_inter_edit ?? '0' }}</span>
                                    <span class="ml-2 text-gray-600 font-mono">{{ $devise_user ?? '' }}</span>
                                </div>
                            </div>
                            <div class="col-span-6 mt-space-padding">
                                <label class="form-label">Pays <span class="text-danger">*</span></label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="fas fa-globe text-gray-500 mr-3"></i>
                                    <span
                                        class="text-gray-800 font-medium">{{ $country_inter_robus_inter_edit ?? 'Non spécifié' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                @endif

                @if ($provider_inter_edit == 53)
                    @if (!is_null($facture_choix_type_edit))
                        @php
                            $paiementInvoiceLabel =
                                $facture_choix_type_edit == 1
                                    ? 'Identifiant facture (11 chiffres)'
                                    : 'Identifiant facture (09 chiffres)';
                        @endphp
                        <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6">
                                <label class="form-label">Nom de la facture <span class="text-danger">*</span></label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="fas fa-file-invoice text-gray-500 mr-3"></i>
                                    <span
                                        class="text-gray-800 font-medium">{{ $paiement_name_cie_edit ?? 'Non spécifié' }}</span>
                                </div>
                            </div>
                            <div class="col-span-6">
                                <label class="form-label">{{ $paiementInvoiceLabel }} <span
                                        class="text-danger">*</span></label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="fas fa-hashtag text-gray-500 mr-3"></i>
                                    <span
                                        class="text-gray-800 font-mono">{{ $paiement_id_invoice_edit ?? 'Non renseigné' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                            <div class="col-span-6 mt-space-padding">
                                <label class="form-label">Montant <span class="text-danger">*</span></label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="fas fa-money-bill-wave text-gray-500 mr-3"></i>
                                    <span
                                        class="text-gray-800 font-medium">{{ $amount_virement_inter_edit ?? '0' }}</span>
                                    <span class="ml-2 text-gray-600 font-mono">{{ $devise_user ?? '' }}</span>
                                </div>
                            </div>
                            <div class="col-span-6 mt-space-padding">
                                <label class="form-label">Pays <span class="text-danger">*</span></label>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <i class="fas fa-globe text-gray-500 mr-3"></i>
                                    <span
                                        class="text-gray-800 font-medium">{{ $country_inter_robus_inter_edit ?? 'Non spécifié' }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if ($provider_inter_edit == 54)
                    <div class="modal-body-transfers grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6">
                            <label class="form-label">Nom de la facture <span class="text-danger">*</span></label>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <i class="fas fa-file-invoice text-gray-500 mr-3"></i>
                                <span
                                    class="text-gray-800 font-medium">{{ $paiement_name_cie_edit ?? 'Non spécifié' }}</span>
                            </div>
                        </div>
                        <div class="col-span-6">
                            <label class="form-label">Numéro du contrat <span class="text-danger">*</span></label>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <i class="fas fa-file-contract text-gray-500 mr-3"></i>
                                <span
                                    class="text-gray-800 font-mono">{{ $paiement_num_contrat_edit ?? 'Non renseigné' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                        <div class="col-span-6 mt-space-padding">
                            <label class="form-label">Montant <span class="text-danger">*</span></label>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <i class="fas fa-money-bill-wave text-gray-500 mr-3"></i>
                                <span
                                    class="text-gray-800 font-medium">{{ $amount_virement_inter_edit ?? '0' }}</span>
                                <span class="ml-2 text-gray-600 font-mono">{{ $devise_user ?? '' }}</span>
                            </div>
                        </div>
                        <div class="col-span-6 mt-space-padding">
                            <label class="form-label">Pays <span class="text-danger">*</span></label>
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <i class="fas fa-globe text-gray-500 mr-3"></i>
                                <span
                                    class="text-gray-800 font-medium">{{ $country_inter_robus_inter_edit ?? 'Non spécifié' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @elseif($choose_type_paiement == 6)
                <!-- Crédit téléphonique -->
                <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Crédit téléphonique
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-6">
                        <label class="form-label">Canal crédit téléphonique <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-sim-card text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $provider_phone_credit_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6">
                        <label class="form-label">Numéro <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-phone text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-mono">{{ $number_phone_credit_inter_edit ?? 'Non renseigné' }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Montant <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-money-bill-wave text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $amount_phone_credit_inter_edit ?? '0' }}</span>
                            <span class="ml-2 text-gray-600 font-mono">{{ $devise_user ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Pays <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-globe text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $country_inter_robus_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                </div>
            @elseif($choose_type_paiement == 7)
                <!-- Versement bancaire -->
                <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Versement bancaire
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-6">
                        <label class="form-label">Canal <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-university text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $provider_phone_credit_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6">
                        <label class="form-label">Numéro <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-phone text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-mono">{{ $number_phone_credit_inter_edit ?? 'Non renseigné' }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Montant <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-money-bill-wave text-gray-500 mr-3"></i>
                            <span class="text-gray-800 font-medium">{{ $amount_virement_inter_edit ?? '0' }}</span>
                            <span class="ml-2 text-gray-600 font-mono">{{ $devise_user ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Pays <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-globe text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $country_inter_robus_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Nom et prénom <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-user text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $firstname_user_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Numéro compte <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-credit-card text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-mono">{{ $account_number_user_bank_inter_edit ?? 'Non renseigné' }}</span>
                        </div>
                    </div>
                </div>
            @elseif($choose_type_paiement == 8)
                <!-- Rechargement carte -->
                <div class="mb-2 mr-6 ml-6 text-center text-primary dark:text-white text-xl mt-3">Rechargement carte
                </div>
                <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">
                    <div class="col-span-6">
                        <label class="form-label">Canal rechargement de carte <span
                                class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-credit-card text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $provider_phone_credit_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6">
                        <label class="form-label">Numéro <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-phone text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-mono">{{ $phone_user_auth_inter_edit ?? 'Non renseigné' }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Montant <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-money-bill-wave text-gray-500 mr-3"></i>
                            <span class="text-gray-800 font-medium">{{ $amount_virement_inter_edit ?? '0' }}</span>
                            <span class="ml-2 text-gray-600 font-mono">{{ $devise_user ?? '' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Pays <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-globe text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $country_inter_robus_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Nom et prénom <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-user text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-medium">{{ $name_relo_user_inter_edit ?? 'Non spécifié' }}</span>
                        </div>
                    </div>
                    <div class="col-span-6 mt-space-padding">
                        <label class="form-label">Numéro compte <span class="text-danger">*</span></label>
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <i class="fas fa-credit-card text-gray-500 mr-3"></i>
                            <span
                                class="text-gray-800 font-mono">{{ $account_number_user_bank_inter_edit ?? 'Non renseigné' }}</span>
                        </div>
                    </div>
                </div>
            @endif
            <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-3">
                <div class="col-span-12 mt-space">
                    <label for="inter_description_robus" class="form-label">Commentaire</label>
                    <textarea wire:model="inter_description_robus" id="inter_description_robus" type="text" class="form-control"
                        cols="13" rows="3">
                    </textarea>

                </div>
            </div>
              <div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3 mt-6">

                <div
                    class=" col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="tryOpenModalInter('validated-inter-modal')"
                        wire:model="interStatus" id="bordered-radio-50" type="radio" value="SUCCESS"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-50"Modi
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                         <i class="fas fa-check-circle style_success"></i>
                        
                        Accepté</label>
                    @error('interStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div
                    class="col-span-6 mt-space flex items-center pl-4 rounded border border-gray-200 dark:border-gray-700">
                    <input wire:click="tryOpenModalInter('validated-inter-modal')"
                        wire:model="interStatus" id="bordered-radio-51" type="radio" value="ERROR"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300  dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="bordered-radio-51"
                        class="py-4 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300">
                         <i class="fas fa-times-circle style_error"></i>
                        Refusé</label>
                    @error('interStatus')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
@include('livewire.commissions.modals.validated_inter')
