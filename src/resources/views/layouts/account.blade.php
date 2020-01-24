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
                        <div class="col-md-3">
                            <h3 class="page-title mb-5">{{ __('Account') }}</h3>
                            <div>
                                <div class="list-group list-group-transparent mb-0">
                                    <a href="{{ route('account.profile.edit') }}" class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('account.profile.edit')) active @endif">
                                        {{ __('Profile') }}
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                                        {{ __('Change password') }}
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
        @include('layouts.includes.footer')
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
