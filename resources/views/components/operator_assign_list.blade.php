    <h2 class="text-lg font-medium truncate mr-5 mt-6">Liste des operateurs :</h2>
    @foreach ($user->operator_services as $opera)
        <span
            class="alert alert-success font-assign-list inline-flex items-center justify-center px-2 py-1 mb-3 text-xs leading-none text-white bg-indigo-700 rounded">
            {{ $opera->operator->name ?? null }}
        </span>
    @endforeach