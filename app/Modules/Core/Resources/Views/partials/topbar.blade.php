<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <button class="btn btn-link d-md-none rounded-circle mr-3" id="sidebarToggleTop">
        <i class="fa fa-bars"></i>
    </button>

    @foreach (Module::enabled()->sortByDesc('order') as $module)
        @if (Auth::user()->is_backend)
            @includeIf($module['slug'].'::partials.backend.topbar-left')
        @else
            @includeIf($module['slug'].'::partials.frontend.topbar-left')
        @endif
    @endforeach

    <ul class="navbar-nav ml-auto">
        @foreach (Module::enabled()->sortByDesc('order') as $module)
            @if (Auth::user()->is_backend)
                @includeIf($module['slug'].'::partials.backend.topbar-right')
            @else
                @includeIf($module['slug'].'::partials.frontend.topbar-right')
            @endif
        @endforeach

        <div class="topbar-divider d-none d-sm-block"></div>

        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="userDropdown">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                    {{ Auth::user()->email }}
                </span>

                <i class="fas fa-user-circle fa-2x"></i>
            </a>

            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                @foreach (Module::enabled()->sortByDesc('order') as $module)
                    @if (Auth::user()->is_backend)
                        @includeIf($module['slug'].'::partials.backend.topbar-user')
                    @else
                        @includeIf($module['slug'].'::partials.frontend.topbar-user')
                    @endif
                @endforeach

                <div class="dropdown-divider"></div>

                <a class="dropdown-item" data-toggle="modal" data-target="#logoutModal" href="#">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>

                    {{ __('Logout') }}
                </a>
            </div>
        </li>
    </ul>
</nav>

<div class="modal fade" id="logoutModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{ __('Ready to Leave?') }}
                </h5>

                <button class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                {{ __('Select "Logout" below if you are ready to end your current session.') }}
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" type="button">
                    {{ __('Cancel') }}
                </button>

                <a class="btn btn-primary" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>

                <form action="{{ route('logout') }}" id="logout-form" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
