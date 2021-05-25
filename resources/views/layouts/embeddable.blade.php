<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>AutoSeguro365</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="url" content="{{ url('/') }}">
    <style>
        html, body{
            overflow-y: auto !important;
            margin: 0px;
            min-height: 100vh;
            width: 100vw;
        }
    </style>
</head>
<body>
    <div id="app">
        @stack('scripts')
        @yield('content')
    </div>
</body>
</html>
