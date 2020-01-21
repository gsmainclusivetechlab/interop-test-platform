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
        <div class="flex-fill">
            <div class="header py-4">
                <div class="container">
                    <div class="d-flex">
                        <a class="header-brand" href="{{ route('home') }}">
                            <img src="{{ asset('images/logo.png') }}" class="header-brand-img" alt="{{ config('app.name') }}">
                        </a>
                        <div class="d-flex order-lg-2 ml-auto">
                            <div class="dropdown">
                                <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                                    <span class="avatar">
                                        <i class="fe fe-user"></i>
                                    </span>
                                    <span class="ml-2 d-none d-lg-block">
                                        <span class="text-default">{{ auth()->user()->name }}</span>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                    <a class="dropdown-item" href="#">
                                        <i class="dropdown-icon fe fe-user"></i>
                                        {{ __('Profile') }}
                                    </a>
                                    @can('administer')
                                        <a class="{{ request()->routeIs('settings.*') ? 'dropdown-item active' : 'dropdown-item' }}" href="{{ route('settings.users.index') }}">
                                            <i class="dropdown-icon fe fe-settings"></i>
                                            {{ __('Settings') }}
                                        </a>
                                    @endcan
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="dropdown-icon fe fe-log-out"></i>
                                            {{ __('Sign out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <a href="#" class="header-toggler d-lg-none ml-3 ml-lg-0" data-toggle="collapse" data-target="#header-menu">
                            <span class="header-toggler-icon"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div id="header-menu" class="header collapse d-lg-flex p-0">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3 ml-auto text-right">
                            <a href="{{ route('sessions.create') }}" class="btn btn-outline-primary">
                                <i class="fe fe-plus mr-2"></i>
                                {{ __('New Session') }}
                            </a>
                        </div>
                        <div class="col-lg order-lg-first">
                            <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                                <li class="nav-item">
                                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'nav-link active' : 'nav-link' }}">
                                        {{ __('Dashboard') }}
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('sessions.index') }}" class="{{ request()->routeIs('sessions.*') ? 'nav-link active' : 'nav-link' }}">
                                        {{ __('Sessions') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-3 my-md-5">
                <div class="container">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert"></button>
                            {{ session('success') }}
                        </div>
                    @elseif(session('danger'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert"></button>
                            {{ session('danger') }}
                        </div>
                    @elseif(session('primary'))
                        <div class="alert alert-primary alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert"></button>
                            {{ session('primary') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
        <footer class="footer">
            <div class="container">
                <div class="row align-items-center flex-row-reverse">
                    <div class="col-auto ml-lg-auto">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <ul class="list-inline list-inline-dots mb-0">
                                    <li class="list-inline-item">
                                        <a href="{{ env('APP_COMPANY_LEGAL_URL') }}" target="_blank">{{ __('Legal') }}</a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="{{ env('APP_COMPANY_COOKIES_URL') }}" target="_blank">{{ __('Cookies') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-auto mt-3 mt-lg-0 text-center">
                        {{ __('Copyright © :year', ['year' => now()->year]) }}
                        <a href="{{ env('APP_COMPANY_URL') }}" target="_blank">{{ env('APP_COMPANY_NAME') }}</a>.
                        {{ __('All rights reserved') }}.
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
