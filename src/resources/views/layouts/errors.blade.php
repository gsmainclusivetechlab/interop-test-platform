<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('includes.head')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="page">
        <div class="page-content">
            <div class="container text-center">
                <div class="display-1 text-muted mb-5"><i class="si si-exclamation"></i> @yield('code')</div>
                <h1 class="h2 mb-3">
                    {{ __('Oops.. You just found an error page..') }}
                </h1>
                <p class="h4 text-muted font-weight-normal mb-7">
                    @yield('message')
                </p>
                <a class="btn btn-primary" href="{{ url()->previous() }}">
                    <i class="fe fe-arrow-left mr-2"></i>{{ __('Go back') }}
                </a>
            </div>
        </div>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
