    @if ($item->status == 'PENDING' || $item->status == 'PROCESSING')

        @if ($item->updated_by)
            <div class="transaction-progress-alert w-full text-center text-blue-500">
                Choisi par
                {{ sidUserInfo($item->updated_by) }}
            </div>
        @endif
        <div class="flex items-center justify-center text-pending mt-3">
            <span
                class="alert alert-pending inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                En-attente
            </span>

        </div>
    @elseif ($item->status == 'ERROR')
        <div class="flex items-center justify-center text-danger">
            <div class="flex items-center justify-center text-pending">
                <span
                    class="alert alert-danger  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                    Refusé
                </span>

            </div>
        </div>
        <div class="flex items-center justify-center text-pending">
            <span
                class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                style="color: rgb(110, 83, 83);">
                Traiter
            </span>

        </div>
    @elseif($item->status == 'REFUND')
        <div class="flex items-center justify-center text-primary">
            <div class="flex items-center justify-center text-pending">
                <span
                    class="alert alert-primary   inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                    Remboursé
                </span>

            </div>
        </div>
        <div class="flex items-center justify-center text-pending">
            <span
                class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                style="color: white;">
                Traiter
            </span>

        </div>
    @else
        <div class="flex items-center justify-center text-success">
            <div class="flex items-center justify-center text-pending">
                <span
                    class="alert  alert-success inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                    style="color: white;">
                    Accepté
                </span>

            </div>

        </div>
        <div class="flex items-center justify-center text-pending">
            <span
                class="alert  alert-dark inline-flex items-center justify-center px-2 py-1 mb-3 
                                                                  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded"
                style="color: white;">
                Traiter
            </span>

        </div>
    @endif
