<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        {{-- meta --}}
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- title --}}
        <title>{{ config('app.name') }}</title>

        {{-- styles --}}
        <link href="{{ mix('modules/css/core.css') }}" rel="stylesheet">
        <style>
            .bg-auth-image {
                background: url('{{ config('core.auth_image') }}');
                background-position: 50%;
                background-size: cover;
            }
        </style>
    </head>

    <body class="bg-gradient-primary">
        <div class="container">
            {{-- content --}}
            @yield('content')
        </div>

        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span class="text-light">
                    {{ date('Y') }} &copy;

                    <a class="text-light" href="{{ config('core.copyright_link') }}" target="_blank">
                        {{ config('core.copyright_name') }}
                    </a>

                    -

                    {{ config('app.name') }}
                </span>
            </div>
        </div>

        {{-- scripts --}}
        <script src="{{ mix('modules/js/core.js') }}"></script>
    </body>
</html>
