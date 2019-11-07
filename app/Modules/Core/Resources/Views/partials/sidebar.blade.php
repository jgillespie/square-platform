<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion{{ session('sidebarToggled') ? ' toggled' : '' }}" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
        <div class="sidebar-brand-icon">
            @if (config('core.logo') == false)
                <i class="fas fa-square"></i>
            @else
                <img src="{{ config('core.logo') }}" width="90%">
            @endif
        </div>

        <div class="sidebar-brand-text mx-3">
            {{ config('app.name') }}
        </div>
    </a>

    <hr class="sidebar-divider my-0">

    <div class="sidebar-heading">
        {{ __('Main Menu') }}
    </div>

    @foreach (Module::enabled()->sortByDesc('order') as $module)
        @if (Auth::user()->is_backend)
            @includeIf($module['slug'].'::partials.backend.sidebar')
        @else
            @includeIf($module['slug'].'::partials.frontend.sidebar')
        @endif
    @endforeach

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

@push('scripts')
    <script>
        $('#sidebarToggle, #sidebarToggleTop').click(function() {
            axios.post('{{ route('common.sidebar.toggle') }}')
            .catch(function (error) {
                console.log(error);
            });
        });
    </script>
@endpush
