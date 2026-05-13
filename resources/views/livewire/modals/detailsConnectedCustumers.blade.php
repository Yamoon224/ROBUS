<div wire:ignore.self id="details-connected-custumers-modal" class="modal hide" data-tw-backdrop="static"
    style="margin-top: 0px; margin-left: 0px; padding-left: 0px; z-index: 10000; display: block;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">
                    Utilisateurs liés à la COM ZONE
                    @if ($selectedComZoneName)
                        <span class="text-slate-500">- {{ $selectedComZoneName }}</span>
                    @endif
                </h2>
                <button type="button" wire:click="closeLinkedUsersModal" class="btn btn-secondary">
                    Fermer
                </button>
            </div>

            <div class="modal-body p-5">
                @if (count($linkedUsers) > 0)
                    <div class="overflow-auto">
                        <table class="table table-bordered table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center whitespace-nowrap">Nom & prénom</th>
                                    <th class="text-center whitespace-nowrap">Contact</th>
                                    <th class="text-center whitespace-nowrap">Type</th>
                                    <th class="text-center whitespace-nowrap">Pays / ville</th>
                                    <th class="text-center whitespace-nowrap">Wallet</th>
                                    <th class="text-center whitespace-nowrap">Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($linkedUsers as $linkedUser)
                                    <tr>
                                        <td>
                                            <div class="font-medium whitespace-nowrap">
                                                {{ $linkedUser->firstname ?? '' }} {{ $linkedUser->lastname ?? '' }}
                                            </div>
                                           
                                        </td>
                                        <td>
                                            <div>{{ $linkedUser->phone ?? 'Non renseigné' }}</div>
                                            <div class="text-slate-500 text-xs mt-1">
                                                {{ $linkedUser->email ?? 'Non renseigné' }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if ($linkedUser->is_under_distributer)
                                                <span
                                                    class="alert alert-success inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-white bg-success rounded">
                                                    Sous distributeur
                                                </span>
                                            @elseif ($linkedUser->is_custumers)
                                                <span
                                                    class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 text-xs leading-none rounded">
                                                    Client
                                                </span>
                                            @elseif ($linkedUser->is_master)
                                                <span
                                                    class="alert alert-warning inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-white bg-pending rounded">
                                                    Master
                                                </span>
                                            @else
                                                <span
                                                    class="alert alert-secondary inline-flex items-center justify-center px-2 py-1 text-xs leading-none rounded">
                                                    Utilisateur
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $linkedUser->country->name ?? 'Non renseigné' }}</div>
                                            <div class="text-slate-500 text-xs mt-1">
                                                {{ $linkedUser->city->name ?? 'Non renseigné' }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ $linkedUser->wallet->wallet_id ?? 'Non renseigné' }}
                                        </td>
                                        <td class="text-center">
                                            @if ($linkedUser->status == '1')
                                                <span
                                                    class="alert alert-success inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-white bg-success rounded">
                                                    Activé
                                                </span>
                                            @else
                                                <span
                                                    class="alert alert-danger inline-flex items-center justify-center px-2 py-1 text-xs leading-none text-white bg-danger rounded">
                                                    Désactivé
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-slate-500 py-10">
                        Aucun utilisateur lié à cette COM ZONE.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
