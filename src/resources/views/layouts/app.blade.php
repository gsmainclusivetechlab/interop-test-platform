<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.includes.head')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="page">
        <div class="flex-fill">
            @include('layouts.includes.header')
            <div class="my-3 my-md-5">
                <div class="container">
                    @include('layouts.includes.flashes')
                    @yield('content')
                </div>
            </div>
        </div>
        @include('layouts.includes.footer')
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
