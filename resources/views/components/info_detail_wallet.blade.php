    <div class="flex flex-col lg:flex-row border-b border-slate-200/60 dark:border-darkmode-400 pb-5 -mx-5">
        <div class="flex flex-1 px-5 items-center justify-center lg:justify-start">
            <div class="w-20 h-20 sm:w-24 sm:h-24 flex-none lg:w-32 lg:h-32 image-fit relative">
                <img alt="Midone - HTML Admin Template"
                    src="{{ asset($user->image ?? 'public/dist/images/profil.png') }}">
            </div>
            <div class="ml-5">
                <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg mb-3">
                    {{ $user->lastname ?? null }} {{ $user->firstname ?? null }}
                </div>
                <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">
                    Code wallet : <br> {{ $user->wallet->wallet_id ?? null }}
                </div>
            </div>

        </div>
        @if (Auth::guard('web')->user()->hasPermissionTo('modifier-plafond'))
            <div class="ml-5 mr-6">
                <div class="w-24 sm:w-40 truncate sm:whitespace-normal font-medium text-lg">

                    <form class="validate-form" method="POST"
                        action="{{ route('custumers.update_silver_ceiling', ['id' => $user->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <p for="silver_ceiling" class="mr-3">Augmenter le plafond
                            {{ $user->country->currencie->code ?? null }}
                        </p>
                        <input type="text" name="silver_ceiling"
                            class="form-control @error('silver_ceiling') is-invalid @enderror"
                            value="{{ $user->silver_ceiling ?? 'Application' }}" required>

                        @error('silver_ceiling')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <button type="submit" class="btn btn-primary w-20 me-1 mb-2 mt-6"
                            style="height: 2rem;">MAJ</button>
                    </form>
                </div>
            </div>
        @endif
        <div
            class="mt-6 lg:mt-0 flex-1 px-5 border-l border-r border-slate-200/60 dark:border-darkmode-400 border-t lg:border-t-0 pt-5 lg:pt-0">
            <div class="font-medium text-center lg:text-left lg:mt-3">Les details</div>
            <div class="flex flex-col justify-center items-center lg:items-start mt-4">
                <div class="truncate sm:whitespace-normal flex items-center"> <i data-feather="mail"
                        class="w-4 h-4 mr-2"></i> {{ $user->email ?? null }} </div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="mic" data-lucide="mic"
                        class="lucide lucide-mic w-4 h-4 text-slate-500 ml-auto mr-2">
                        <path d="M12 1a3 3 0 00-3 3v8a3 3 0 006 0V4a3 3 0 00-3-3z"></path>
                        <path d="M19 10v2a7 7 0 01-14 0v-2"></path>
                        <line x1="12" y1="19" x2="12" y2="23"></line>
                        <line x1="8" y1="23" x2="16" y2="23"></line>
                    </svg>
                    {{ $user->phone ?? null }}
                </div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                        class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg> Creer : {{ $user->created_at ?? null }}
                </div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                        class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg> Date connexion : {{ $user->last_login ?? null }}
                </div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3"> <svg
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" icon-name="clock" data-lucide="clock"
                        class="lucide lucide-clock w-4 h-4 text-slate-500 ml-auto mr-2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg> Date deconnexion : {{ $user->last_logout ?? null }}
                </div>

                <div class="truncate sm:whitespace-normal flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span
                        class="alert alert-primary  inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">

                        Pays : {{ $user->country->name ?? null }}

                    </span>
                </div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span
                        class="alert alert-primary  inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">Ville
                        : {{ $user->city->name ?? 'Inconnue' }}</span>
                </div>
                <div class="truncate sm:whitespace-normal flex items-center mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span
                        class="alert alert-primary  inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">Zone
                        : {{ $user->zone ?? 'Inconnue' }}</span>
                </div>
            </div>
        </div>
        {{-- Affichage du QR code du wallet --}}

        <div
            class="mt-6 lg:mt-0 flex-1 flex flex-col items-center px-5 border-t lg:border-0 mr-3 border-slate-200/60 dark:border-darkmode-400 pt-5 lg:pt-0 space-y-6">

            @if (Auth::guard('web')->user()->hasPermissionTo('voir-soldes'))
                {{-- Solde Wallet --}}
                <div
                    class="w-full bg-gradient-to-r from-indigo-600 to-indigo-400 text-white rounded-xl shadow-lg p-4 text-center">
                    <div class="amount-section">
                        <div class="amount-label">Solde actuel</div>
                        <div class="amount-balance">
                            <div class="flex items-baseline justify-between">
                                <span class="amount-balance-value">
                                    {{ number_format($user->wallet()->sum('amount'), 2, ',', ' ') ?? null }}
                                </span>
                                <span class="amount-balance-currency">
                                    {{ $user->country->currencie->code ?? null }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full flex justify-center mt-6">
                    @if ($user->wallet && $user->wallet->qr_code)
                        <div class="w-40 h-40 p-2 bg-white rounded-lg shadow-md flex items-center justify-center">
                            <img src="{{ asset($user->wallet->qr_code) }}" alt="QR Code Wallet"
                                class="w-full h-full object-contain">
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-2">QR code non disponible.</p>
                    @endif
                </div>
            @endif
            {{-- QR Code Wallet --}}


        </div>
    </div>
