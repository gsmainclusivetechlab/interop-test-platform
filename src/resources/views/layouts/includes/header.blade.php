<div class="header py-4">
    <div class="container-fluid">
        <div class="row d-flex align-items-center">
            <div class="col-2">
                <a class="header-brand mr-0" href="{{ route('home') }}">
                    <img src="{{ asset('images/logo.png') }}" class="h-7" alt="{{ config('app.name') }}">
                </a>
            </div>
            <div class="col text-center text-primary">
                <h1 class="col-login__title mb-1">{{ env('APP_COMPANY_LAB') }}</h1>
                <h2 class="col-login__subtitle mb-0">{{ config('app.name') }}</h2>
            </div>
            <div class="col-3 d-flex ml-auto pr-0">
                <b-navbar-nav class="ml-auto">
                    <b-nav-item-dropdown class="ml-auto p-0" menu-class="dropdown-menu-arrow" right toggle-class="pr-0 leading-none">
                        <template v-slot:button-content>
                            <span class="avatar flex-shrink-0">
                                <i class="fe fe-user"></i>
                            </span>
                            <span class="ml-2 d-none d-lg-inline-block text-truncate">
                                <span class="text-default">{{ auth()->user()->name }}</span>
                            </span>
                        </template>
                        <b-dropdown-item href="#">
                            <i class="dropdown-icon fe fe-user"></i>
                            {{ __('Profile') }}
                        </b-dropdown-item>
                        <b-dropdown-item href="{{ route('settings.profile.edit') }}" @if (request()->routeIs('settings.profile.edit')) active @endif>
                            <i class="dropdown-icon fe fe-settings"></i>
                            {{ __('Settings') }}
                        </b-dropdown-item>
                        <b-dropdown-divider></b-dropdown-divider>
                        <b-dropdown-form action="{{ route('logout') }}" method="POST" form-class="p-0">
                            @csrf
                            <button class="dropdown-item" type="submit">
                                <i class="dropdown-icon fe fe-log-out"></i>
                                {{ __('Logout') }}
                            </button>
                        </b-dropdown-form>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
            </div>
            <b-navbar-toggle target="header-menu" class="header-toggler d-lg-none ml-3 ml-lg-0">
                <span class="header-toggler-icon"></span>
            </b-navbar-toggle>
        </div>
    </div>
</div>
<b-collapse id="header-menu" class="header d-lg-flex p-0" is-nav>
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-lg-3 ml-auto my-3 my-lg-0 text-lg-right">
                <a href="{{ route('sessions.register.selection.create') }}" class="btn btn-outline-primary">
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
                    @if(auth()->user()->can('viewAny', \App\Models\User::class) ||
                        auth()->user()->can('viewAny', \App\Models\TestSession::class) ||
                        auth()->user()->can('viewAny', \App\Models\Environment::class))
                        <b-nav-item-dropdown menu-class="dropdown-menu-arrow" toggle-class="@if (request()->routeIs('admin.*')) active @endif">
                            <template v-slot:button-content>
                                <i class="fe fe-lock"></i>
                                {{ __('Administration') }}
                            </template>
                            @can('viewAny', \App\Models\User::class)
                                <b-dropdown-item href="{{ route('admin.users.index') }}" @if (request()->routeIs('admin.users.*')) active @endif>
                                    {{ __('Users') }}
                                </b-dropdown-item>
                            @endcan

                            @can('viewAny', \App\Models\TestSession::class)
                                <b-dropdown-item href="{{ route('admin.sessions.index') }}" @if (request()->routeIs('admin.sessions.*')) active @endif>
                                    {{ __('Sessions') }}
                                </b-dropdown-item>
                            @endcan

                            @can('viewAny', \App\Models\Environment::class)
                                <b-dropdown-item href="{{ route('admin.environments.index') }}" @if (request()->routeIs('admin.environments.*')) active @endif>
                                    {{ __('Environments') }}
                                </b-dropdown-item>
                            @endcan
                        </b-nav-item-dropdown>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</b-collapse>
