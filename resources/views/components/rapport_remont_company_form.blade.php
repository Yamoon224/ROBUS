    <div class="col-span-12 sm:col-span-4 2xl:col-span-2 box p-5 cursor-pointer zoom-in">
        <form method="POST"
            action="{{ route('remonte.remonteAllCommission.unders', ['amount' => $company_gains, 'type' => 'COMMISSION', 'id' => $user->id]) }}">
            @csrf
            <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 mr-3">
                    <rect x="2" y="5" width="20" height="14" rx="2" ry="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
                Tout remonté
            </button>
        </form>

        <div class="font-medium text-historique mt-6">Total Gain</div>

        <div class="text-slate-500 mt-3">
            {{ number_format($company_gains, 2, ',', ' ') ?? null }}
            {{ $usersCountry }}</div>

    </div>
    <div class="intro-y grid grid-cols-12 gap-5 mt-5">

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box bg-primary p-5 cursor-pointer zoom-in style-success">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_payments, 'type' => 'Paiements', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>

            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_payments, 'type' => 'Retrait Paiements', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label text-white">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-sm text-dark bg-white border-white mt-3" title="Modifier"
                    style="color: black;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>

            <div class="font-medium text-historique text-white mt-6">Paiements</div>


            <div class="text-slate-500 mt-3">
                {{ number_format($gain_payments, 2, ',', ' ') ?? null }}
                {{ $usersCountry }}</div>

        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-error">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_depots, 'type' => 'Depots', 'id' => $user->id]) }}">
                @csrf


                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>

            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_depots, 'type' => 'Retrait Depots', 'id' => $user->id]) }}">
                @csrf
                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label text-white">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>

            <div class="font-medium text-historique mt-6">Dépot Robus</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_depots, 2, ',', ' ') ?? null }} {{ $usersCountry }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in ">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_retraits, 'type' => 'Retraits', 'id' => $user->id]) }}">
                @csrf


                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>


            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_retraits, 'type' => 'Retrait Retraits', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>
            <div class="font-medium text-historique mt-6">Rétrait Robus</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_retraits, 2, ',', ' ') ?? null }} {{ $usersCountry }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style-error">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_moneys, 'type' => 'Moneys', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>


            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_moneys, 'type' => 'Retrait Moneys', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label text-white">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>
            <div class="font-medium text-historique mt-6">Mobile Money</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_moneys, 2, ',', ' ') ?? null }} {{ $usersCountry }}
            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_user1">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_phone_credits, 'type' => 'Crédits', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>
            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_phone_credits, 'type' => 'Retrait Crédits', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label text-white">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>

            <div class="font-medium text-historique mt-6">Credits</div>
            <div class="text-slate-500 mt-3"> {{ number_format($gain_phone_credits, 2, ',', ' ') ?? null }}
                {{ $usersCountry }}</div>

        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_inters, 'type' => 'International', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>

            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_inters, 'type' => 'Retrait International', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>

            <div class="font-medium text-historique mt-6">Inters</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_inters, 2, ',', ' ') ?? null }}
                {{ $usersCountry }}</div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table2">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_banks, 'type' => 'Virements', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>
            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_banks, 'type' => 'Retrait Virements', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label text-white">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>
            <div class="font-medium text-historique mt-6">Virements</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_banks, 2, ',', ' ') ?? null }}
                {{ $usersCountry }}</div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in style_table_factures">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_deposits, 'type' => 'Rechargements', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>

            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_deposits, 'type' => 'Retrait Rechargements', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label text-white">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>

            <div class="font-medium text-historique mt-6">Réchargements cartes</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_deposits, 2, ',', ' ') ?? null }}
                {{ $usersCountry }}


            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_xbets, 'type' => 'Xbets', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>

            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_xbets, 'type' => 'Retrait Xbet', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>

            <div class="font-medium text-historique mt-6">Réchargements Xbet</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_deposits, 2, ',', ' ') ?? null }}
                {{ $usersCountry }}


            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in btn-indigo">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_pushs, 'type' => 'Push', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>

            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_pushs, 'type' => 'Retrait Push', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label text-white">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>

            <div class="font-medium text-historique mt-6">Réchargements Push</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_deposits, 2, ',', ' ') ?? null }}
                {{ $usersCountry }}


            </div>
        </div>

        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in btn-orange">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_versments, 'type' => 'Versement', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>

            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_versments, 'type' => 'Retrait Versement', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label text-white">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>

            <div class="font-medium text-historique mt-6">Réchargements versement</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_deposits, 2, ',', ' ') ?? null }}
                {{ $usersCountry }}


            </div>
        </div>
        <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in btn-pink">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_tresorts, 'type' => 'Tresort', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>

            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_tresorts, 'type' => 'Retrait Tresort', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label text-white">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>

            <div class="font-medium text-historique mt-6">Réchargements tresort Money</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_deposits, 2, ',', ' ') ?? null }}
                {{ $usersCountry }}


            </div>
        </div>

          <div class="col-span-12 sm:col-span-4 2xl:col-span-3 box p-5 cursor-pointer zoom-in btn-purple">

            <form method="POST"
                action="{{ route('remonte.commissions.unders', ['amount' => $gain_djamo, 'type' => 'Djamo', 'id' => $user->id]) }}">
                @csrf
                <button type="submit" class="btn btn-warning mr-1 mb-2 btn-sm" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    Rémonter
                </button>
            </form>

            <form method="POST"
                action="{{ route('remonte.commissions.companies.admins', ['amount' => $gain_djamo, 'type' => 'Retrait Djamo', 'id' => $user->id]) }}">
                @csrf

                <div class="col-span-12 lg:col-span-8">
                    <label class="form-label text-white">Montant retrait<span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('amountDrawal') is-invalid @enderror"
                        style="color: black" name="amountDrawal" id="amountDrawal" maxlength="15">
                </div>
                <button type="submit" class="btn btn-success mr-1 mb-2 btn-sm text-white mt-3" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 mr-3">
                        <rect x="2" y="5" width="20" height="14" rx="2" ry="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="2" y1="10" x2="22" y2="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="6" y1="15" x2="10" y2="15"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <line x1="14" y1="15" x2="18" y2="15"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Retrait
                </button>
            </form>

            <div class="font-medium text-historique mt-6">Réchargements Djamo</div>
            <div class="text-slate-500 mt-3">
                {{ number_format($gain_deposits, 2, ',', ' ') ?? null }}
                {{ $usersCountry }}


            </div>
        </div>




    </div>
