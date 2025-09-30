<div class="col-span-12 mt-1" wire:loading.class="hidden" wire:target="addValidatedPush">
    <!-- Input mot de passe corrigé -->
    <div class="mb-1 mt-6 col-span-12 form-group required" x-data="{ show: true }">
        <div class="d-flex justify-content-between">
            <label class="form-label block text-sm font-medium text-gray-700 mb-2" for="password_validated">
                Mot de passe
            </label>
        </div>

        <div class="flex rounded-md shadow-sm">
            <input wire:model="password_validated" placeholder="Entrez votre mot de passe"
                :type="show ? 'password' : 'text'" id="password_validated" tabindex="2" wire:input="checkPassword"
                aria-describedby="password" name="password_validated" required autocomplete="current-password"
                class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">

            <button type="button" @click="show = !show"
                class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                <!-- Icône œil ouvert -->
                <svg class="h-5 w-5" fill="none" :class="{ 'hidden': !show, 'block': show }" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>

                <!-- Icône œil fermé -->
                <svg class="h-5 w-5" fill="none" :class="{ 'block': !show, 'hidden': show }" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>

        @error('password_validated')
            <span class="text-red-500 text-sm mt-1 block" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
