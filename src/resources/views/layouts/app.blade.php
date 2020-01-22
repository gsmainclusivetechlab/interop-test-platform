<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('includes.head')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="page">
        <div class="flex-fill">
            @include('includes.header')
            <div class="my-3 my-md-5">
                <div class="container">
                    @include('includes.alerts')
                    @yield('content')
                </div>
            </div>
        </div>
        @include('includes.footer')
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
