<!DOCTYPE html>
<html class="has-aside-left has-aside-mobile-transition has-aside-expanded">
<head>
    <title>AutoSeguro365</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    @if(auth()->user())
    <meta name="api-token" content="{{ auth()->user()->api_token }}">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url" content="{{ url('/') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <script src="" defer></script>
    @routes
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    @inertia
    @if(auth()->user())
        <script>
            window.USER = {
                ...@json(auth()->user()),
                permissions: @json(auth()->user()->getPermissionsViaRoles()).map(x => ({
                    'description': x.description,
                    'name': x.name
                })),
                roles: @json(auth()->user()->roles).map(x => ({
                    'name': x.name,
                    'description': x.description
                }))
            }
            window.TENANT = @json(auth()->user()->currentTenant())
        </script>
    @endif
    {{-- <script>
        window.default_locale = "{{ config('app.lang') }}";
        window.fallback_locale = "{{ config('app.fallback_locale') }}";
        window.messages = @json($messages);
    </script> --}}

</body>
</html>
