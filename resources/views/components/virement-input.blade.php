<div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
    <div class="col-span-12 mt-space form-group required">
        <label for="account_number_user_bank" class="form-label">Numero du compte </label>
        <input wire:model.defer="account_number_user_bank" id="account_number_user_bank" type="text" class="form-control"
            placeholder="Numero du compte" required>

        @error('account_number_user_bank')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
    <div class="col-span-6 mt-space form-group required">
        <label for="firstname_user" class="form-label">Nom du client </label>
        <input wire:model.defer="firstname_user" id="firstname_user" type="text" class="form-control"
            placeholder="Nom du client" required>
        @error('firstname_user')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-span-6 mt-space form-group required">
        <label for="lastname_user" class="form-label">Prenom du client </label>
        <input wire:model.defer="lastname_user" id="lastname_user" type="text" class="form-control"
            placeholder="Prenom du client" required>
        @error('lastname_user')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<div class="modal-body-virement grid grid-cols-12 gap-4 gap-y-3">
    <div class="col-span-6 mt-space form-group required">
        <label for="phone_user" class="form-label">Telephone </label>
        <input wire:model.defer="phone_user" id="phone_user" type="text" class="form-control"
            placeholder="Numero du telephone" required>
        @error('phone_user')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-span-6 mt-space form-group required">
        <label for="amount_virement" class="form-label">Montant </label>
        <input wire:model.debounce.10ms="amount_virement" id="amount_virement" type="number" min="1"
            class="form-control" placeholder="Montant" required>
        @error('amount_virement')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

</div>
