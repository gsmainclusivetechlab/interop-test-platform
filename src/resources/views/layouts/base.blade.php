<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.includes.head')
    @stack('styles')
    @stack('scripts')
</head>
<body>
    @yield('page')
</body>
</html>
