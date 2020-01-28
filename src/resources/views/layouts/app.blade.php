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
                    <div class="row">
                        @hasSection('sidebar')
                            <div class="col-md-3">
                                @yield('sidebar')
                            </div>
                            <div class="col-md-9">
                                @yield('content')
                            </div>
                        @else
                            <div class="col-md-12">
                                @yield('content')
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.includes.footer')
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
