<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon.png') }}">
    <link rel="apple-touch-icon" type="image/png" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <title>@yield('title') - {{ config('app.name') }} - {{ env('APP_COMPANY_NAME') }}</title>
    <link href="{{ mix('css/vendor.css', 'assets') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css', 'assets') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js', 'assets') }}" defer></script>
    <script src="{{ asset('assets/js/tutorials/jquery-3.2.1.min.js') }}"></script>
</head>
<body>
    <div id="app" class="page">
        <div class="flex-fill">
            @include('layouts.includes.header')
            <div class="my-3 my-md-5">
                @yield('main')
            </div>
        </div>
        @include('layouts.includes.footer')
        @stack('scripts')
    </div>
</body>
</html>
