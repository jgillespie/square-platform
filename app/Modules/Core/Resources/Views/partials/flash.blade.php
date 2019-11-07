@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('status') }}

        <button class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@error('permission')
    <div class="alert alert-danger alert-dismissible fade show">
        {{ $message }}

        <button class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@enderror

@error('deny')
    <div class="alert alert-danger alert-dismissible fade show">
        {{ $message }}

        <button class="close" data-dismiss="alert" type="button">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@enderror

@foreach (Module::enabled()->sortByDesc('order') as $module)
    @if (Auth::user()->is_backend)
        @includeIf($module['slug'].'::partials.backend.flash')
    @else
        @includeIf($module['slug'].'::partials.frontend.flash')
    @endif
@endforeach
