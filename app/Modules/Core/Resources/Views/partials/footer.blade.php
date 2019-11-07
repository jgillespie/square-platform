<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>
                {{ date('Y') }} &copy;

                <a href="{{ config('core.copyright_link') }}" target="_blank">
                    {{ config('core.copyright_name') }}
                </a>

                -

                {{ config('app.name') }}
            </span>
        </div>
    </div>
</footer>
