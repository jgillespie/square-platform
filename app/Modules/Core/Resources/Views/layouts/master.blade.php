<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- meta --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @stack('meta')

        {{-- title --}}
        <title>{{ config('app.name') }}</title>

        {{-- styles --}}
        <link href="{{ mix('modules/css/core.css') }}" rel="stylesheet">
        @stack('styles')
    </head>

    <body id="page-top"{{ session('sidebarToggled') ? ' class=sidebar-toggled' : '' }}>
        <div id="wrapper">
            {{-- sidebar --}}
            @include('core::partials.sidebar')

            <div class="d-flex flex-column" id="content-wrapper">
                <div id="content">
                    {{-- topbar --}}
                    @include('core::partials.topbar')

                    <div class="container-fluid">
                        {{-- flash --}}
                        @include('core::partials.flash')

                        {{-- content --}}
                        @yield('content')
                    </div>
                </div>

                {{-- footer --}}
                @include('core::partials.footer')
            </div>
        </div>

        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        {{-- scripts --}}
        <script src="{{ mix('modules/js/core.js') }}"></script>
        @stack('scripts')
    </body>
</html>
