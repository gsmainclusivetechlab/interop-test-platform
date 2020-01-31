<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.includes.head')
</head>
<body>
    <div id="app" class="page">
        <div class="page-content">
            <div class="container text-center">
                <div class="display-1 text-muted mb-5"><i class="si si-exclamation"></i> @yield('code')</div>
                <h1 class="h2 mb-3">
                    {{ __('Oops.. You just found an error page..') }}
                </h1>
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
