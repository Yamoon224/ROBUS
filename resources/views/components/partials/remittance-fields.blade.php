 @include('components.transaction-custumer-input', [
     'id' => 'name_sender',
     'model' => 'name_sender',
     'label' => 'Nom & prenom du bénéficiaire',
     'error' => 'name_sender',
 ])

 <div class="col-span-6 mt-3">
     <label for="phone_inter_out_civ" class="form-label">Numero de telephone du
         bénéficiaire<span class="text-danger">*</span></label>

     <div class="flex">
         <div class="z-10 pl-1 text-center pointer-events-none flex items-center justify-center style-indicatif">
             {{ $countrie_indicative->indecative ?? null }}
         </div>
         <input wire:model.defer="phone_inter_out_civ" id="phone_inter_out_civ" type="number" class="form-control"
             placeholder="Numero" value="" minlength="6" maxlength="15">
         @error('phone_inter_out_civ')
             <span class="text-danger">{{ $message }}</span>
         @enderror
     </div>
 </div>
 @include('components.transaction-custumer-input', [
     'id' => 'name',
     'model' => 'name',
     'label' => 'Nom de la banque',
     'error' => 'name',
 ])

 @include('components.transaction-custumer-input', [
     'id' => 'account_number',
     'model' => 'account_number',
     'label' => 'Numero du compte du bénéficiaire',
     'error' => 'account_number',
 ])

 @include('components.transaction-custumer-input', [
     'id' => 'name_custumer',
     'model' => 'name_custumer',
     'label' => 'Nom & prenom de l\'expediteur',
     'error' => 'name_custumer',
 ])

 @include('components.transaction-custumer-input', [
     'id' => 'num_piece_custumer',
     'model' => 'num_piece_custumer',
     'label' => 'Numero piece',
     'error' => 'num_piece_custumer',
 ])

 @include('components.transaction-date-input', [
     'id' => 'date_piece',
     'model' => 'date_piece',
     'label' => 'Date piece',
     'error' => 'date_piece',
 ])
