<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon.png') }}">
    <link rel="apple-touch-icon" type="image/png" href="{{ asset('images/favicon/apple-touch-icon.png') }}">
    <title>@yield('title') - {{ config('app.name') }} - {{ env('APP_COMPANY_NAME') }}</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="page">
        <div class="page-single">
            <div class="container">
                <div class="row">
                    <div class="col col-login mx-auto">
                        <div class="text-center mb-6">
                            <img src="{{ asset('images/logo.png') }}" class="h-6" alt="{{ config('app.name')  }}">
                        </div>
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
