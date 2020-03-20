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
</head>
<body>
    <div id="app" class="page">
        <div class="page-single">
            <div class="container">
                <div class="row">
                    <div class="col col-login mx-auto">
                        <div class="text-center mb-5">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('images/logo.png') }}" class="mb-2" alt="{{ config('app.name')  }}">
                            </a>
                            <div class="text-primary">
                                <h1 class="col-login__title mb-1">{{ env('APP_COMPANY_LAB') }}</h1>
                                <h2 class="col-login__subtitle mb-0">{{ config('app.name')  }}</h2>
                            </div>
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
