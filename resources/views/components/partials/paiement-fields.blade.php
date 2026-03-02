   @if ($provider_inter_out_civ == 52)

       <div class="col-span-12">
           <div class="alert alert-secondary show mb-2" role="alert">
               Faire le choix entre les deux numero
           </div>
       </div>
       <div class="col-span-12 form-group required mt-3">
           <label class="form-label" for="paiementChoix_phone">Le choix de numero</label>
           <select wire:model="paiementChoix_phone" id="paiementChoix_phone" class="form-control" required>
               <option value="" selected>Selectionnez type numero</option>
               <option value="1">Numero de telephone</option>
               <option value="2">Numero du decodeur</option>
           </select>
           @error('paiementChoix_phone')
               <span class="text-danger">{{ $message }}</span>
           @enderror
       </div>
       @if (!is_null($paiementChoix_phone))
           @php
               $phoneNumberLabel =
                   $paiementChoix_phone == 1 ? 'Numero du téléphone(10 chiffres)' : 'Numero du décodeur(14 chiffres)';
               $phoneNumberInputId = $paiementChoix_phone == 1 ? 'paiement_phone_canal' : 'paiement_decoder_canal';
           @endphp
           <div class="col-span-6 form-group required mt-3">
               <label for="{{ $phoneNumberInputId }}" class="form-label">{{ $phoneNumberLabel }}</label>
               <input wire:model.defer="{{ $phoneNumberInputId }}" id="{{ $phoneNumberInputId }}" type="text"
                   class="form-control" value="" maxlength="{{ $paiementChoix_phone == 1 ? 10 : 14 }}"
                   placeholder="{{ $phoneNumberLabel }}" required>
               @error($phoneNumberInputId)
                   <span class="text-danger">{{ $message }}</span>
               @enderror
           </div>

           <div class="col-span-6 form-group required mt-3">
               <label for="offer_paiement" class="form-label">Offre </label>
               <select wire:model.debounce.10ms="offer_paiement" class="form-control" required>
                   <option value="" selected>Selectionnez offre</option>
                   @foreach ($offers_canal as $item)
                       <option value="{{ $item->id }}">{{ $item->name }}</option>
                   @endforeach
               </select>
               @error('offer_paiement')
                   <span class="text-danger">{{ $message }}</span>
               @enderror
           </div>

           <div class="col-span-12 form-group required mt-3">
               <label for="paiement_name" class="form-label">Nom & Prénom</label>
               <input wire:model.defer="paiement_name" id="paiement_name" type="text" class="form-control"
                   placeholder="Nom & Prénom" required>
               @error('paiement_name')
                   <span class="text-danger">{{ $message }}</span>
               @enderror
           </div>

       @endif

   @endif

   @if ($provider_inter_out_civ == 53)

       <div class="col-span-12 form-group required">
           <label class="form-label" for="facture_choix_type">Le choix de type de facture </label>
           <select wire:model="facture_choix_type" id="facture_choix_type" class="form-control" required>
               <option value="" selected>Choix type facture</option>
               <option value="1">Facture prépayée</option>
               <option value="2">Facture normale</option>
           </select>
           @error('facture_choix_type')
               <span class="text-danger">{{ $message }}</span>
           @enderror

       </div>

       @if (!is_null($facture_choix_type))

           @php
               $paiementInvoiceLabel =
                   $facture_choix_type == 1 ? 'Identifiant facture(11 chiffres)' : 'Identifiant facture(09 chiffres)';
               $paiementNumberInputId = $facture_choix_type == 1 ? 'paiement_id_invoice_pre' : 'paiement_id_invoice';
           @endphp

           <div class="col-span-12 form-group required mt-3">
               <label for="{{ $paiementNumberInputId }}" class="form-label">{{ $paiementInvoiceLabel }}</label>
               <input wire:model.defer="{{ $paiementNumberInputId }}" id="{{ $paiementNumberInputId }}" type="text"
                   class="form-control" value="" maxlength="11" placeholder="{{ $paiementInvoiceLabel }}"
                   required>
               @error('{{ $paiementNumberInputId }}')
                   <span class="text-danger">{{ $message }}</span>
               @enderror
           </div>
           <div class="col-span-6 form-group required mt-3">
               <label for="paiement_name_cie" class="form-label">Nom de la facture</label>
               <input wire:model.defer="paiement_name_cie" id="paiement_name_cie" type="text" class="form-control"
                   placeholder="Nom facture" required>
               @error('paiement_name_cie')
                   <span class="text-danger">{{ $message }}</span>
               @enderror
           </div>
           <div class="col-span-6 mt-3">
               <label for="pre_phone_invoice" class="form-label">Numéro </label>
               <input wire:model.defer="pre_phone_invoice" id="pre_phone_invoice" type="number" class="form-control"
                   placeholder="Numero de telephone">
               @error('pre_phone_invoice')
                   <span class="text-danger">{{ $message }}</span>
               @enderror
           </div>

          

       @endif

   @endif

   @if ($provider_inter_out_civ == 54)

       <div class="col-span-12 form-group required">
           <label for="paiement_name_sodecie" class="form-label">Nom de la facture</label>
           <input wire:model.defer="paiement_name_sodecie" id="paiement_name_sodecie" type="text"
               class="form-control" placeholder="Nom facture" required>
           @error('paiement_name_sodecie')
               <span class="text-danger">{{ $message }}</span>
           @enderror
       </div>
       <div class="col-span-6 form-group required mt-3">
           <label for="paiement_num_contrat" class="form-label">Numero du contrat (09
               chiffres) </label>
           <input wire:model.defer="paiement_num_contrat" id="paiement_num_contrat" type="text"
               class="form-control" placeholder="Numero contrat" value="" maxlength="9" required>
           @error('paiement_num_contrat')
               <span class="text-danger">{{ $message }}</span>
           @enderror
       </div>
      
   @endif
