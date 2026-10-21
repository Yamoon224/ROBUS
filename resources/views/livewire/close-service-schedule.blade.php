<div>

    <h2 class="intro-y text-lg font-medium mt-10">
        Horaires d'ouverture — {{ $serviceLabel }}
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5 mb-3">

        <div class="intro-y col-span-12">
            <div class="box p-5">
                <div class="flex flex-wrap items-center gap-3">
                    <div class="font-medium text-base mr-auto">Activation manuelle</div>
                    @if ($availability['open'])
                        <span class="text-success font-medium"><i class="fas fa-unlock-alt mr-1"></i> Service actuellement ouvert</span>
                    @else
                        <span class="text-danger font-medium"><i class="fas fa-lock mr-1"></i> Service actuellement fermé</span>
                    @endif
                </div>
                <div class="text-slate-500 mt-2">
                    Le mode manuel prime sur les horaires ci-dessous. En mode « Automatique », seules les plages horaires activées s'appliquent.
                    <strong class="text-slate-700">
                        Mode actuel :
                        @if ($availability['manual_mode'] === 'open')
                            activé manuellement.
                        @elseif ($availability['manual_mode'] === 'closed')
                            désactivé manuellement.
                        @else
                            automatique.
                        @endif
                    </strong>
                </div>
                @if (Auth::guard('web')->user()->hasPermissionTo('open-robus'))
                    <div class="mt-4">
                        <label class="form-label">Message affiché aux clients en cas de désactivation manuelle (optionnel)</label>
                        <input type="text" wire:model.defer="manual_message" class="form-control" maxlength="255"
                            placeholder="Ex : Maintenance en cours">
                    </div>
                    <div class="flex flex-wrap gap-2 mt-4">
                        <button type="button" wire:click="setManualMode('open')"
                            class="btn {{ $availability['manual_mode'] === 'open' ? 'btn-success' : 'btn-outline-success' }}">
                            <i class="fas fa-unlock-alt mr-1"></i> Forcer l'activation
                        </button>
                        <button type="button" wire:click="setManualMode('closed')"
                            class="btn {{ $availability['manual_mode'] === 'closed' ? 'btn-danger' : 'btn-outline-danger' }}">
                            <i class="fas fa-lock mr-1"></i> Forcer la désactivation
                        </button>
                        <button type="button" wire:click="setManualMode('auto')"
                            class="btn {{ $availability['manual_mode'] === null ? 'btn-primary' : 'btn-outline-primary' }}">
                            <i class="fas fa-clock mr-1"></i> Automatique (horaires)
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
            <table class="table table-bordered table-hover -mt-2 -intro-x ">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center whitespace-nowrap">Jour</th>
                        <th class="text-center whitespace-nowrap">Heure debut</th>
                        <th class="text-center whitespace-nowrap">Heure Fin</th>
                        <th class="text-center whitespace-nowrap">Motif</th>
                        <th class="text-center whitespace-nowrap">Règle horaire</th>
                        <th class="text-center whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($closeapps as $item)
                        <tr class="intro-x">
                            <td class="w-35 text-center">
                                <div class="text-slate-500 text-xs mt-0.5">{{ $item->day_of_week }}</div>
                            </td>
                            <td class="w-35 text-center">
                                <div class="text-slate-500 text-xs mt-0.5">{{ $item->time_start }}</div>
                            </td>
                            <td class="w-35 text-center">
                                <div class="text-slate-500 text-xs mt-0.5">{{ $item->time_end }}</div>
                            </td>
                            <td class="w-35 text-center">
                                <div class="text-slate-500 text-xs mt-0.5">{{ $item->description }}</div>
                            </td>
                            <td class="w-40">
                                @if (Auth::guard('web')->user()->hasPermissionTo('open-robus'))
                                    <div class="flex items-center justify-center {{ $item->status ? 'text-success' : 'text-danger' }}">
                                        <a wire:click="edit({{ $item->id }}, 'show-disabled-App-modal'); $emit('show-disabled-App-modal')"
                                            class="flex items-center mr-3" href="javascript:;"
                                            title="{{ $item->status ? 'Désactiver' : 'Activer' }}">
                                            @if ($item->status)
                                                <i class="fas fa-unlock-alt w-4 h-4 mr-2"></i> Activée
                                            @else
                                                <i class="fas fa-lock w-4 h-4 mr-2"></i> Désactivée
                                            @endif
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td class="w-56">
                                <div class="flex justify-center items-center">
                                    @if (Auth::guard('web')->user()->hasPermissionTo('open-robus'))
                                        <a wire:click="edit({{ $item->id }}, 'update-AppRobus-modal')"
                                            class="btn btn-primary mr-1 mb-2 btn-sm" href="javascript:;"
                                            title="Modifier">
                                            Modifier
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @include('livewire.commissions.modals.disabledCloseApp')
    @include('livewire.commissions.modals.updateAppClose')

</div>
