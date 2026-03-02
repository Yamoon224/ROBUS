
  @include('components.transaction-custumer-input', [
      'id' => 'name_custumer',
      'model' => 'name_custumer',
      'label' => 'Nom & prenom',
      'error' => 'name_custumer',
  ])

  <div class="col-span-6 mt-3">
      <label for="phone_inter_out_civ" class="form-label">Numero de
          telephone<span class="text-danger">*</span></label>

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
