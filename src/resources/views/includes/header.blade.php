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
                            <a class="dropdown-item @if (request()->routeIs('settings.*')) active @endif" href="{{ route('settings.users.index') }}">
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
                        <a href="{{ route('home') }}" class="nav-link @if (request()->routeIs('home')) active @endif">
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sessions.index') }}" class="nav-link @if (request()->routeIs('sessions.*')) active @endif">
                            {{ __('Sessions') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
