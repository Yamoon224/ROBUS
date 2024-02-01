<ul class="nav nav-link-tabs flex-col sm:flex-row justify-center  text-center" role="tablist">
        <li id="profile-tab" class="nav-item" role="presentation">
            <a class="nav-link py-4 flex items-center {{ request()->is('master/account') ? 'active' : '' }}"
                href="{{ route('masters.account.profile') }}" aria-controls="profile" aria-selected="true" role="tab"> <i
                    class="w-4 h-4 mr-2" data-feather="user"></i> Profile </a>
        </li>
        <li id="change-password-tab" class="nav-item " role="presentation">
            <a class="nav-link py-4 flex items-center {{ request()->is('master/account/security/update') ? 'active' : '' }}"
                href="{{ route('masters.account.security') }}" data-tw-target="#change-password" aria-controls="change-password"
                aria-selected="false" role="tab"> <i class="w-4 h-4 mr-2" data-feather="lock"></i> Changer de mot de
                passe </a>
        </li>
    </ul>
    