<div class="header py-4">
    <div class="container">
        <div class="row d-flex align-items-center">
            <a class="col-2 header-brand mr-0" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" class="h-7" alt="{{ config('app.name') }}">
            </a>
            <div class="col text-center text-primary">
                <h1 class="col-login__title mb-1">{{ env('APP_COMPANY_LAB') }}</h1>
                <h2 class="col-login__subtitle mb-0">{{ config('app.name') }}</h2>
            </div>
            <div class="col-3 d-flex ml-auto">
                <div class="dropdown ml-auto">
                    <a href="#" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                        <span class="avatar flex-shrink-0">
                            <i class="fe fe-user"></i>
                        </span>
                        <span class="ml-2 d-none d-lg-inline-block text-truncate">
                            <span class="text-default">{{ auth()->user()->name }}</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        <a class="dropdown-item" href="{{ route('account.profile.edit') }}">
                            <i class="dropdown-icon fe fe-user"></i>
                            {{ __('Account') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item" type="submit">
                                <i class="dropdown-icon fe fe-log-out"></i>
                                {{ __('Logout') }}
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
            <div class="col-lg-3 ml-auto my-3 my-lg-0 text-lg-right">
                <a href="{{ route('sessions.register.selection.index') }}" class="btn btn-outline-primary">
                    <i class="fe fe-plus mr-2"></i>
                    {{ __('New Session') }}
                </a>
            </div>
            <div class="col-lg order-lg-first">
                <ul class="nav nav-tabs border-0 flex-column flex-lg-row">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link @if (request()->routeIs('home')) active @endif">
                            <i class="fe fe-activity"></i>
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('sessions.index') }}" class="nav-link @if (request()->routeIs('sessions.*')) active @endif">
                            <i class="fe fe-box"></i>
                            {{ __('Sessions') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fe fe-help-circle"></i>
                            {{ __('Tutorial') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ env('APP_COMPANY_LAB_URL') }}" class="nav-link" target="_blank">
                            <i class="fe fe-link"></i>
                            {{ __('The Lab') }}
                        </a>
                    </li>
                    @if(auth()->user()->can('viewAny', \App\Models\User::class) || auth()->user()->can('viewAny', \App\Models\TestSession::class))
                        <li class="nav-item dropdown">
                            <a href="#" class="nav-link @if (request()->routeIs('admin.*')) active @endif" data-toggle="dropdown">
                                <i class="fe fe-settings"></i>
                                {{ __('Administration') }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-arrow">
                                @can('viewAny', \App\Models\User::class)
                                    <a href="{{ route('admin.users.index') }}" class="dropdown-item @if (request()->routeIs('admin.users.*')) active @endif">
                                        {{ __('Users') }}
                                    </a>
                                @endcan

                                @can('viewAny', \App\Models\TestSession::class)
                                    <a href="{{ route('admin.sessions.index') }}" class="dropdown-item @if (request()->routeIs('admin.sessions.*')) active @endif">
                                        {{ __('Sessions') }}
                                    </a>
                                @endcan
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
