<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.includes.head')
    @stack('styles')
    @stack('scripts')
</head>
<body>
    <div id="app"></div>
</body>
</html>
