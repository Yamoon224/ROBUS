<ul class="nav nav-link-tabs flex-col sm:flex-row justify-center  text-center" role="tablist">
    <li id="profile-tab" class="nav-item" role="presentation">
        <a class="nav-link py-4 flex items-center {{ request()->is('account') ? 'active' : '' }}"
            href="{{ route('account.profile') }}" aria-controls="profile" aria-selected="true" role="tab"> <i
                class="w-4 h-4 mr-2" data-feather="user"></i> Profile </a>
    </li>
    {{-- <li id="account-tab" class="nav-item" role="presentation">
    <a  class="nav-link py-4 flex items-center {{ (request()->is('account/compliance')) ? 'active' : '' }}" href="{{ route('compliance') }}"
        aria-controls="account" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
            data-feather="shield"></i> Compilance </a>
</li> --}}
    <li id="change-password-tab" class="nav-item " role="presentation">
        <a class="nav-link py-4 flex items-center {{ request()->is('account/security') ? 'active' : '' }}"
            href="{{ route('account.security') }}" data-tw-target="#change-password" aria-controls="change-password"
            aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2" data-feather="lock"></i> Changer de mot de
            passe </a>
    </li>
    {{-- <li id="settings-tab" class="nav-item" role="presentation">
    <a href="javascript:;" class="nav-link py-4 flex items-center" data-tw-target="#settings"
        aria-controls="settings" aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2"
            data-feather="settings"></i> Reglages </a>
</li> --}}
</ul>
