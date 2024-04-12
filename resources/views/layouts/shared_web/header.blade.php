@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
@endphp

<nav class="navbar navbar-expand-md navbar-dark fixed-top  style_header">
    <div class="container-fluid margin-menu-all">
        <a class="navbar-brand" href="#"><img
                src="https://robus.africa/wp-content/uploads/2023/07/LOGO_ROBUS-web.png" alt="Brighton" title="Brighton"
                height="70"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse margin-menu" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link text-white" aria-current="page" href="https://robus.africa/">Acceuil</a>
                </li>
                <li class="nav-item dropdown ">
                    <a class="nav-link dropdown-toggle text-white {{ $route == 'web.transactions_reloads' ||  $route == 'web.transactions_pushs' ? 'show' : '' }}" href="#" id="dropdown10"
                        data-bs-toggle="dropdown" aria-expanded="{{ $route == 'web.transactions_reloads' ||  $route == 'web.transactions_pushs' ? 'true' : 'false' }}">Récharger ma carte</a>
                    <ul class="dropdown-menu {{ $route == 'web.transactions_reloads' ||  $route == 'web.transactions_pushs' ? 'show' : '' }}" aria-labelledby="dropdown10" data-bs-popper="{{ $route == 'web.transactions_reloads' ||  $route == 'web.transactions_pushs' ? 'none' : '' }}">
                        <li>
                            <a class="dropdown-item {{ $route == 'web.transactions_reloads' ? 'active' : '' }}" href="{{ route('web.transactions_reloads') }}">Carte Visa/Master
                                Carte</a></li>
                        <li><a class="dropdown-item {{  $route == 'web.transactions_pushs' ? 'active' : '' }}" href="{{ route('web.transactions_pushs') }}">Carte PUSH</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white {{ $route == 'web.transactions_transactions' ? 'active' : '' }}"
                        href="{{ route('web.transactions_transactions') }}">Transfert et paiment de
                        credit</a>
                </li>

                <li class="nav-item"><a class="nav-link text-white {{ $route == 'web.transactions_robus' ? 'active' : '' }}"
                        href="{{ route('web.transactions_robus') }}">Récharger mon compte ROBUS</a></li>
                <li class="nav-item"><a class="nav-link  text-white {{ $route == 'web.transactions_xbets' ? 'active' : '' }}"
                        href="{{ route('web.transactions_xbets') }}">Récharger mon compte 1XBET</a></li>

                        <li class="nav-item"><a class="nav-link  text-white {{ $route == 'web.transactions_inters' ? 'active' : '' }}"
                            href="{{ route('web.transactions_inters') }}">Transfert a l'international</a></li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="https://robus.africa/#contacts">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
