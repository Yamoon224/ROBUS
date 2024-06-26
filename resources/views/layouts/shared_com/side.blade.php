<!-- BEGIN: Breadcrumb -->
{{-- <script src="{{ asset('public/js/script.js') }}"></script>
<script src="{{ asset('public/js/app.js') }}"></script> --}}
<nav aria-label="breadcrumb" class="-intro-x mr-auto hidden sm:flex">

    <ol class="breadcrumb ">
        <li class="breadcrumb-item"><a href="#">Application</a></li>
        <li class="breadcrumb-item active mr-6" aria-current="page">Tableau de bord</li>

        {{-- <li class="breadcrumb-item float-md-end d-none d-md-block">version 0.0.1 </li> --}}
        {{-- <span class="float-md-end d-none d-md-block">version 0.0.1 --}}
    </ol>
    <div class="mr-6 dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button"
        aria-expanded="false" data-tw-toggle="dropdown">
        <img alt="ROBUS" src="{{ asset(comzone()->country->image ?? 'public/dist/images/profil.png') }}">
    </div>
    <div class="mr-6 dropdown-toggle w-8 h-8 rounded-full shadow-lg image-fit zoom-in" role="button"
        aria-expanded="false" data-tw-toggle="dropdown">
      @if(comzone())
            <div class="font-medium whitespace-nowrap">COM ZONE  {{ comzone()->company->name ?? null }}</div> 
            <div class="text-slate-500  mt-0.5">
                <span
                    class="alert alert-secondary  inline-flex items-center justify-center px-2 py-1 mb-3 text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
                    {{ comzone()->wallet->wallet_id ?? null }}
                </span>
            </div>
        @endif

    </div>
</nav>
<!-- END: Breadcrumb -->

<!-- BEGIN: Search -->
<div class="intro-x relative mr-3 sm:mr-6">
    <div class="search hidden sm:block">
        <span class="float-md-end d-none d-md-block mr-5">version 0.1.2</span>
        <input type="text" class="search__input form-control border-transparent" placeholder="Search...">
        <i data-feather="search" class="search__icon dark:text-slate-500"></i>
    </div>

    <a class="notification sm:hidden" href=""> <i data-feather="search"
            class="notification__icon dark:text-slate-500"></i> </a>
</div>
<div class="intro-x relative mr-3 sm:mr-6">


    <span
        class="alert alert-primary inline-flex items-center justify-center px-2 py-1  text-xs font-bold leading-none text-indigo-100 bg-indigo-700 rounded">
          {{ comzone()->country->name ?? null }}
    </span>

</div>
<!-- END: Search -->
<!-- BEGIN: Notifications -->
<div id="app" class="intro-x dropdown mr-auto sm:mr-6">
    <div class="notification-content pt-2 dropdown-menu">
        <div class="notification-content__box dropdown-content">
            <div class="notification-content__title">Notifications</div>
            <admin-notification :userid="{{ comzone()->id }}"
                :unreads="{{ comzone()->unreadNotifications()->latest()->get() ?? '' }}">
            </admin-notification>

        </div>
    </div>
</div>
<!-- END: Notifications -->
<!-- BEGIN: Account Menu -->
<div class="intro-x dropdown w-8 h-8">
    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in" role="button"
        aria-expanded="false" data-tw-toggle="dropdown">
        <img alt="ROBUS" src="{{ asset(comzone()->image ?? 'public/dist/images/profil.png') }}">
    </div>
    <div class="dropdown-menu w-56">
        <ul class="dropdown-content bg-primary text-white">
            <li class="p-2">
                </span>
             
                <div class="font-medium"> {{ comzone()->firstname }}
                    {{ comzone()->lastname }}</div>
            </li>
            <li>
                <hr class="dropdown-divider border-white/[0.08]">
            </li>
            <li>
                <a href="{{ route('account.profile') }}" class="dropdown-item hover:bg-white/5"> <i data-feather="user"
                        class="w-4 h-4 mr-2"></i> Profile </a>
            </li>
            <li>
                <a href="" class="dropdown-item hover:bg-white/5"> <i data-feather="help-circle"
                        class="w-4 h-4 mr-2"></i> Help </a>
            </li>
            <li class="p-2">
                </span>
                <div class="font-medium"> Solde : {{ number_format(comzone()->wallet->amount, 2, ',', ' ' ) ?? null }}
                    {{ comzone()->country->currencie->code ?? null }}</div>
            </li>
            <li>
                <hr class="dropdown-divider border-white/[0.08]">
            </li>
            <li>
                <a href="{{ route('comzones.logout') }}" class="dropdown-item hover:bg-white/5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" icon-name="toggle-right" data-lucide="toggle-right" class="lucide lucide-toggle-right w-4 h-4 mr-2"><rect x="1" y="5" width="22" height="14" rx="7" ry="7"></rect><circle cx="16" cy="12" r="3"></circle></svg>Déconnexion
                </a>
                <form id="logout-form" action="{{ route('comzones.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
    {{-- @vite('resources/js/app.js') --}}
</div>
<!-- END: Account Menu -->

