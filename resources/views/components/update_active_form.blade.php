<div class="grid grid-cols-12 gap-6 mt-3">
    <div class="intro-y col-span-12">
        <div class="intro-y box">
            <div class="p-5">
                @if ($user->tokens->isNotEmpty())
                    <form action="{{ route('logoutUsers.browse', ['id' => $user->id]) }}" method="post">
                        @csrf
                        <button type="submit"
                            title="Déconnecter l'utilisateur"
                            class="btn btn-danger flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-red-600 border border-red-600 rounded-lg hover:bg-red-600 hover:text-white transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7" />
                            </svg>
                            Déconnecter
                        </button>
                        
                    </form>
                @else
                    <button disabled
                        title="Aucun token actif pour cet utilisateur"
                        class="btn btn-primary flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-gray-400 border border-gray-300 bg-gray-100 rounded-lg cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7" />
                        </svg>
                        Utilisateur est deconnecté
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<form class="form-vertical row" action="{{ route('admin.update_password', ['id' => $user->id]) }}" method="post"
    enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-12 gap-6 mt-3 items-center">
        <div class="col-span-12 lg:col-span-4">
            <label class="form-label">Mot de passe <span class="text-danger"> *</span></label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                id="password" required maxlength="4">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-span-12 lg:col-span-4">
            <label class="form-label">Confirmer Mot de passe <span class="text-danger">*</span></label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                name="password_confirmation" id="password_confirmation" required maxlength="4">
        </div>
        <div class="col-span-12 lg:col-span-4">
            <div class="text-center">
                <button type="submit" class="btn btn-primary w-24  mb-2 mt-6 ">Enregistrer</button>
            </div>
        </div>
    </div>
</form>
