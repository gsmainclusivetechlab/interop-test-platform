@extends('layouts.default')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title mb-3">
                    <b>{{ $scenario->name }}</b>
                </h1>
                <div class="row border-top border-bottom mb-5 align-items-center">
                    <div class="col-8">
                        <ul class="nav nav-tabs border-0">
                            <li class="nav-item">
                                <a href="{{ route('admin.scenarios.show', $scenario) }}" class="nav-link @if (request()->routeIs('admin.scenarios.show', $scenario)) active @endif">
                                    {{ __('Overview') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.scenarios.components.index', $scenario) }}" class="nav-link @if (request()->routeIs('admin.scenarios.components.index')) active @endif">
                                    {{ __('Components') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.scenarios.use-cases.index', $scenario) }}" class="nav-link @if (request()->routeIs('admin.scenarios.use-cases.index')) active @endif">
                                    {{ __('Use Cases') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.scenarios.test-cases.index', $scenario) }}" class="nav-link @if (request()->routeIs('admin.scenarios.test-cases.index')) active @endif">
                                    {{ __('Test Cases') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-4 text-right">
                        <b-dropdown variant="button" toggle-class="btn-primary" menu-class="dropdown-menu-arrow" right no-caret>
                            <template v-slot:button-content>
                                <i class="fe fe-upload mr-2"></i>
                                {{ __('Import') }}
                            </template>
                            <b-dropdown-item href="#">
                                {{ __('Test Cases') }}
                            </b-dropdown-item>
                        </b-dropdown>
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
    </div>
@endsection
