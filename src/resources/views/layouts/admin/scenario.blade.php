@extends('layouts.default')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title mb-3">
                    <b>{{ $scenario->name }}</b>
                </h1>
                <ul class="nav nav-tabs mb-5">
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
                @yield('content')
            </div>
        </div>
    </div>
@endsection
