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
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="page-title mb-5">{{ __('Settings') }}</h3>
                            <div>
                                <div class="list-group list-group-transparent mb-0">
                                    <a href="{{ route('settings.users.index') }}" class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('settings.users.*')) active @endif">
                                        {{ __('Users') }}
                                    </a>
                                    <a href="{{ route('settings.sessions.index') }}" class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('settings.sessions.*')) active @endif">
                                        {{ __('Sessions') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('includes.footer')
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
