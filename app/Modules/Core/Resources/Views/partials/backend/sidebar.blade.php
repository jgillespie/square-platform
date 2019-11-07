<li class="nav-item{{ Request::is(config('core.backend_routes_prefix').'/security/*') ? ' active' : '' }}">
    <a class="nav-link{{ Request::is(config('core.backend_routes_prefix').'/security/*') ? '' : ' collapsed' }}" data-toggle="collapse" data-target="#security" href="#">
        <i class="fas fa-fw fa-user-shield"></i>

        <span>
            {{ __('Security') }}
        </span>
    </a>

    <div class="collapse{{ Request::is(config('core.backend_routes_prefix').'/security/*') && !session('sidebarToggled') ? ' show' : '' }}" data-parent="#accordionSidebar" id="security">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">
                {{ __('Manage:') }}
            </h6>

            <a class="collapse-item{{ Request::is(config('core.backend_routes_prefix').'/security/account/*') ? ' active' : '' }}" href="{{ route('backend.security.account.index') }}">
                {{ __('Accounts') }}
            </a>

            <a class="collapse-item{{ Request::is(config('core.backend_routes_prefix').'/security/role/*') ? ' active' : '' }}" href="{{ route('backend.security.role.index') }}">
                {{ __('Roles') }}
            </a>

            <a class="collapse-item{{ Request::is(config('core.backend_routes_prefix').'/security/permission/*') ? ' active' : '' }}" href="{{ route('backend.security.permission.index') }}">
                {{ __('Permissions') }}
            </a>
        </div>
    </div>
</li>
