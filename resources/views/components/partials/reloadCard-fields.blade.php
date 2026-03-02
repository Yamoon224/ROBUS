 <div class="col-span-12 form-group required">
     <label for="account_number_reload_card" class="form-label">Numéro du compte </label>
     <input wire:model.defer="account_number_reload_card" id="account_number_reload_card" type="text"
         class="form-control" placeholder="Numéro du compte" maxlength="10" required>
     @error('account_number_reload_card')
         <span class="text-danger">{{ $message }}</span>
     @enderror
 </div>

 <div class="col-span-6 form-group required mt-3">
     <label for="name_reloadcard" class="form-label">Nom et prénom </label>
     <input wire:model.defer="name_reloadcard" id="name_reloadcard" type="text" class="form-control"
         placeholder="Nom et prénom" required>
     @error('name_reloadcard')
         <span class="text-danger">{{ $message }}</span>
     @enderror
 </div>

