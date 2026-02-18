<div wire:ignore.self id="update-interTransfers-modal" class="modal hide overflow-y-auto" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            @include('components.modal-header', [
                'title' => 'Valider demande internationale',
                'icon' => 'fas fa-check-circle',
                'service' => 'Internationale',
                'serviceIcon' => 'fas fa-dice',
                'onClose' => 'cancelInterField',
            ])


            <div class="modal-body mt-1">
                @if ($choose_type_paiement == 1)
                    <div class="space-y-6">
                        <!-- Section Images -->
                        @if ($type_inter_image == 'Passeport')
                            <div class="form-section">
                                <h4 class="section-title mb-4">Documents d'identité</h4>
                                <div class="field-group">
                                    <div class="field field-full">
                                        <label class="form-label required">
                                            <i class="fas fa-qrcode mr-2 text-primary"></i>
                                            Image de la carte
                                        </label>
                                        <div class="field-value flex items-center gap-4">
                                            @if ($image_frontend_inter_edit)
                                                @php
                                                    $img =
                                                        asset_url($image_frontend_inter_edit) ??
                                                        asset_url('image_inters/' . $image_frontend_inter_edit);
                                                @endphp
                                                <a href="{{ $img }}" download target="_blank"
                                                    class="relative group">
                                                    <img src="{{ $img }}"
                                                        class="w-24 h-24 object-contain rounded-lg border-2 border-gray-200 hover:border-primary transition-all" />
                                                    <span
                                                        class="absolute inset-0 flex items-center justify-center bg-black/40 text-white opacity-0 group-hover:opacity-100 rounded-lg transition">
                                                        <i class="fas fa-download text-lg"></i>
                                                    </span>
                                                </a>
                                            @else
                                                <span class="text-gray-500">Aucune image disponible</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="form-section">
                                <h4 class="section-title mb-4">Documents d'identité</h4>
                                <div class="field-group">
                                    <div class="field">
                                        <label class="form-label required">
                                            <i class="fas fa-qrcode mr-2 text-primary"></i>
                                            Face avant
                                        </label>
                                        <div class="field-value">
                                            @if ($image_frontend_inter_edit)
                                                @php $imgFront = asset_url($image_frontend_inter_edit) ?? asset_url('image_inters/' . $image_frontend_inter_edit); @endphp
                                                <a href="{{ $imgFront }}" download target="_blank"
                                                    class="relative group inline-block">
                                                    <img src="{{ $imgFront }}"
                                                        class="w-24 h-24 object-contain rounded-lg border-2 border-gray-200 hover:border-primary" />
                                                    <span
                                                        class="absolute inset-0 flex items-center justify-center bg-black/40 text-white opacity-0 group-hover:opacity-100 rounded-lg">
                                                        <i class="fas fa-download"></i>
                                                    </span>
                                                </a>
                                            @else
                                                <span class="text-gray-500">Aucune image</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="form-label required">
                                            <i class="fas fa-qrcode mr-2 text-primary"></i>
                                            Face arrière
                                        </label>
                                        <div class="field-value">
                                            @if ($image_backend_inter_edit)
                                                @php $imgBack = asset_url($image_backend_inter_edit) ?? asset_url('image_inters/' . $image_backend_inter_edit); @endphp
                                                <a href="{{ $imgBack }}" download target="_blank"
                                                    class="relative group inline-block">
                                                    <img src="{{ $imgBack }}"
                                                        class="w-24 h-24 object-contain rounded-lg border-2 border-gray-200 hover:border-primary" />
                                                    <span
                                                        class="absolute inset-0 flex items-center justify-center bg-black/40 text-white opacity-0 group-hover:opacity-100 rounded-lg">
                                                        <i class="fas fa-download"></i>
                                                    </span>
                                                </a>
                                            @else
                                                <span class="text-gray-500">Aucune image</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Informations de transfert -->
                        <div class="form-section mb-3 mt-3">
                            <h4 class="section-title mb-4">Détails du transfert</h4>
                            <div class="field-group">
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-money-bill-transfer mr-2 text-primary"></i>
                                        Canal de transfert
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-money-bill-transfer"></i>
                                        <span
                                            class="font-medium">{{ $provider_phone_credit_inter_edit ?? 'Non spécifié' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-phone mr-2 text-primary"></i>
                                        Numéro bénéficiaire
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-phone"></i>
                                        <span
                                            class="font-mono">{{ $number_phone_credit_inter_edit ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Informations pièce d'identité -->
                        <div class="form-section mb-3">
                            <h4 class="section-title mb-4">Informations pièce d'identité</h4>
                            <div class="field-group">
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-id-card mr-2 text-primary"></i>
                                        Numéro de pièce
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span class="font-medium">{{ $num_piece_inter_edit ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-calendar-check mr-2 text-primary"></i>
                                        Validité
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-globe"></i>
                                        <span
                                            class="font-medium">{{ $validated_piece_inter_edit ?? 'Non spécifié' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations personnes -->
                        <div class="form-section mb-3">
                            <h4 class="section-title mb-4">Personnes concernées</h4>
                            <div class="field-group">
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-user mr-2 text-primary"></i>
                                        Nom du bénéficiaire
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-user"></i>
                                        <span
                                            class="font-medium">{{ $name_benef_inter_edit ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-user-circle mr-2 text-primary"></i>
                                        Nom du déposant
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-globe"></i>
                                        <span
                                            class="font-medium">{{ $name_sender_inter_edit ?? 'Non spécifié' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Montant et Pays -->
                        <div class="form-section mb-3">
                            <h4 class="section-title mb-4">Transaction</h4>
                            <div class="field-group">
                                <div class="field-full">
                                    <label class="form-label required">
                                        <i class="fas fa-coins mr-2 text-primary"></i>
                                        Montant
                                    </label>
                                    <div class="field-value amount-display">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span
                                            class="text-xl font-bold text-primary">{{ number_format($amount_virement_inter_edit ?? 0, 0, ',', ' ') }}</span>
                                        <span class="ml-2 text-sm text-gray-600">{{ $devise_user ?? 'XOF' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($choose_type_paiement == 5)
                    <!-- Paiement facture -->
                    <div class="space-y-6">
                        <div class="flex justify-center mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                <i class="fas fa-file-invoice mr-2"></i>
                                Paiement de facture
                            </span>
                        </div>

                        <div class="form-section mb-3">
                            <div class="field-group">
                                <div class="field field-full">
                                    <label class="form-label required">
                                        <i class="fas fa-credit-card mr-2 text-primary"></i>
                                        Canal de paiement
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-credit-card"></i>
                                        <span
                                            class="font-medium">{{ $provider_phone_credit_inter_edit ?? 'Non spécifié' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($provider_inter_edit == 52 && !is_null($paiementChoix_phone_edit))
                            @php
                                $phoneNumberLabel = $paiementChoix_phone_edit == 1 ? 'Téléphone' : 'Décodeur';
                                $phoneNumberValue =
                                    $paiementChoix_phone_edit == 1
                                        ? $paiement_phone_canal_edit ?? 'Non renseigné'
                                        : $paiement_decoder_canal_edit ?? 'Non renseigné';
                            @endphp

                            <div class="form-section mb-3">
                                <h4 class="section-title mb-4">Détails du paiement</h4>
                                <div class="field-group">
                                    <div class="field">
                                        <label class="form-label required">
                                            <i class="fas fa-tag mr-2 text-primary"></i>
                                            Offre
                                        </label>
                                        <div class="field-value">
                                            <i class="fas fa-tag"></i>
                                            <span
                                                class="font-medium">{{ $offer_paiement_edit ?? 'Non spécifié' }}</span>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="form-label required">
                                            <i class="fas fa-mobile-alt mr-2 text-primary"></i>
                                            {{ $phoneNumberLabel }}
                                        </label>
                                        <div class="field-value">
                                            <i class="fas fa-mobile-alt"></i>
                                            <span class="font-mono">{{ $phoneNumberValue }}</span>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="form-label required">
                                            <i class="fas fa-user mr-2 text-primary"></i>
                                            Nom & Prénom
                                        </label>
                                        <div class="field-value">
                                            <i class="fas fa-user"></i>
                                            <span class="font-mono">{{ $phoneNumberValue }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($provider_inter_edit == 53 && !is_null($facture_choix_type_edit))
                            @php
                                $paiementInvoiceLabel =
                                    $facture_choix_type_edit == 1
                                        ? 'Identifiant facture (11 chiffres)'
                                        : 'Identifiant facture (09 chiffres)';
                            @endphp

                            <div class="form-section mb-3">
                                <h4 class="section-title mb-4">Informations facture</h4>
                                <div class="field-group">
                                    <div class="field">
                                        <label class="form-label required">
                                            <i class="fas fa-file-invoice mr-2 text-primary"></i>
                                            Nom de la facture
                                        </label>
                                        <div class="field-value">
                                            <i class="fas fa-file-invoice"></i>
                                            <span
                                                class="font-medium">{{ $paiement_name_cie_edit ?? 'Non spécifié' }}</span>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="form-label required">
                                            <i class="fas fa-hashtag mr-2 text-primary"></i>
                                            {{ $paiementInvoiceLabel }}
                                        </label>
                                        <div class="field-value">
                                            <i class="fas fa-hashtag"></i>
                                            <span
                                                class="font-mono">{{ $paiement_id_invoice_edit ?? 'Non renseigné' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($provider_inter_edit == 54)
                            <div class="form-section mb-3">
                                <h4 class="section-title mb-4">Informations contrat</h4>
                                <div class="field-group">
                                    <div class="field">
                                        <label class="form-label required">
                                            <i class="fas fa-file-invoice mr-2 text-primary"></i>
                                            Nom de la facture
                                        </label>
                                        <div class="field-value">
                                            <i class="fas fa-file-invoice"></i>
                                            <span
                                                class="font-medium">{{ $paiement_name_cie_edit ?? 'Non spécifié' }}</span>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label class="form-label required">
                                            <i class="fas fa-file-contract mr-2 text-primary"></i>
                                            Numéro du contrat
                                        </label>
                                        <div class="field-value">
                                            <i class="fas fa-file-contract"></i>
                                            <span
                                                class="font-mono">{{ $paiement_num_contrat_edit ?? 'Non renseigné' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Montant et Pays pour paiement -->
                        <div class="form-section">
                            <div class="field-group">
                                <div class="field-full">
                                    <label class="form-label required">
                                        <i class="fas fa-coins mr-2 text-primary"></i>
                                        Montant
                                    </label>
                                    <div class="field-value amount-display">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span
                                            class="text-xl font-bold text-primary">{{ number_format($amount_virement_inter_edit ?? 0, 0, ',', ' ') }}</span>
                                        <span class="ml-2 text-sm text-gray-600">{{ $devise_user ?? 'XOF' }}</span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                @elseif($choose_type_paiement == 6)
                    <!-- Crédit téléphonique -->
                    <div class="space-y-6">
                        <div class="flex justify-center mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                <i class="fas fa-sim-card mr-2"></i>
                                Crédit téléphonique
                            </span>
                        </div>

                        <div class="form-section mb-3">
                            <div class="field-group">
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-sim-card mr-2 text-primary"></i>
                                        Opérateur
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-sim-card"></i>
                                        <span
                                            class="font-medium">{{ $provider_phone_credit_inter_edit ?? 'Non spécifié' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-phone mr-2 text-primary"></i>
                                        Numéro
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-phone"></i>
                                        <span
                                            class="font-mono">{{ $number_phone_credit_inter_edit ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <div class="field-group">
                                <div class="field-full">
                                    <label class="form-label required">
                                        <i class="fas fa-coins mr-2 text-primary"></i>
                                        Montant
                                    </label>
                                    <div class="field-value amount-display">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span
                                            class="text-xl font-bold text-primary">{{ number_format($amount_phone_credit_inter_edit ?? 0, 0, ',', ' ') }}</span>
                                        <span class="ml-2 text-sm text-gray-600">{{ $devise_user ?? 'XOF' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($choose_type_paiement == 7)
                    <!-- Versement bancaire -->
                    <div class="space-y-6">
                        <div class="flex justify-center mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                <i class="fas fa-university mr-2"></i>
                                Versement bancaire
                            </span>
                        </div>

                        <div class="form-section mb-3">
                            <div class="field-group">
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-university mr-2 text-primary"></i>
                                        Banque
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-university"></i>
                                        <span
                                            class="font-medium">{{ $provider_phone_credit_inter_edit ?? 'Non spécifié' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-phone mr-2 text-primary"></i>
                                        Numéro
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-phone"></i>
                                        <span
                                            class="font-mono">{{ $number_phone_credit_inter_edit ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <div class="field-group">
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-user mr-2 text-primary"></i>
                                        Titulaire du compte
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-user"></i>
                                        <span
                                            class="font-medium">{{ $firstname_user_inter_edit ?? 'Non spécifié' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-credit-card mr-2 text-primary"></i>
                                        Numéro de compte
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-credit-card"></i>
                                        <span
                                            class="font-mono">{{ $account_number_user_bank_inter_edit ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section mb-3">
                            <div class="field-group">
                                <div class="field-full">
                                    <label class="form-label required">
                                        <i class="fas fa-coins mr-2 text-primary"></i>
                                        Montant
                                    </label>
                                    <div class="field-value amount-display">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span
                                            class="text-xl font-bold text-primary">{{ number_format($amount_virement_inter_edit ?? 0, 0, ',', ' ') }}</span>
                                        <span class="ml-2 text-sm text-gray-600">{{ $devise_user ?? 'XOF' }}</span>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                @elseif($choose_type_paiement == 8)
                    <!-- Canal rechargement de carte bancaire -->
                    <div class="space-y-6">
                        <div class="flex justify-center mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                <i class="fas fa-university mr-2"></i>
                                Canal rechargement de carte
                            </span>
                        </div>

                        <div class="form-section mb-3">
                            <div class="field-group">
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-university mr-2 text-primary"></i>
                                        Banque
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-university"></i>
                                        <span
                                            class="font-medium">{{ $provider_phone_credit_inter_edit ?? 'Non spécifié' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-phone mr-2 text-primary"></i>
                                        Numéro
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-phone"></i>
                                        <span
                                            class="font-mono">{{ $phone_user_auth_inter_edit ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-section mb-3">
                            <div class="field-group">
                                <div class="field-full">
                                    <label class="form-label required">
                                        <i class="fas fa-coins mr-2 text-primary"></i>
                                        Montant
                                    </label>
                                    <div class="field-value amount-display">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span
                                            class="text-xl font-bold text-primary">{{ number_format($amount_virement_inter_edit ?? 0, 0, ',', ' ') }}</span>
                                        <span class="ml-2 text-sm text-gray-600">{{ $devise_user ?? 'XOF' }}</span>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                @elseif($choose_type_paiement == 9)
                    <!-- Canal rechargement de carte bancaire -->
                    <div class="space-y-6">
                        <div class="flex justify-center mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">
                                <i class="fas fa-university mr-2"></i>
                                Canal versement banciare
                            </span>
                        </div>

                        <div class="form-section mb-3">
                            <div class="field-group">
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-university mr-2 text-primary"></i>
                                        Banque
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-university"></i>
                                        <span
                                            class="font-medium">{{ $provider_Inter_virement_edit ?? 'Non spécifié' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-phone mr-2 text-primary"></i>
                                        Numéro du compte
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-phone"></i>
                                        <span class="font-mono">{{ $num_accompte ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-phone mr-2 text-primary"></i>
                                        Code banque
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-phone"></i>
                                        <span class="font-mono">{{ $bank_code_edit ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-phone mr-2 text-primary"></i>
                                        Code Guichet
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-phone"></i>
                                        <span class="font-mono">{{ $guichet_edit ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-phone mr-2 text-primary"></i>
                                        Clé RIB
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-phone"></i>
                                        <span class="font-mono">{{ $num_auther_edit ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="form-label required">
                                        <i class="fas fa-phone mr-2 text-primary"></i>
                                        Nom & Prénom
                                    </label>
                                    <div class="field-value">
                                        <i class="fas fa-phone"></i>
                                        <span class="font-mono">{{ $name ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-section mb-3">
                            <div class="field-group">
                                <div class="field-full">
                                    <label class="form-label required">
                                        <i class="fas fa-coins mr-2 text-primary"></i>
                                        Montant
                                    </label>
                                    <div class="field-value amount-display">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <span
                                            class="text-xl font-bold text-primary">{{ number_format($versementAmount ?? 0, 0, ',', ' ') }}</span>
                                        <span class="ml-2 text-sm text-gray-600">{{ $devise_user ?? 'XOF' }}</span>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-section mb-3 mt-3">
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
                                    class="font-medium">{{ $country_inter_robus_inter_edit ?? 'Non spécifié' }}</span>
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
                                    class="font-medium">{{ $country_inter_robus_recevied ?? 'Non spécifié' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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
                    <input wire:click="tryOpenModalInter('validated-inter-modal')" wire:model="interStatus"
                        id="bordered-radio-50" type="radio" value="SUCCESS"
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
                    <input wire:click="tryOpenModalInter('validated-inter-modal')" wire:model="interStatus"
                        id="bordered-radio-51" type="radio" value="ERROR"
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
