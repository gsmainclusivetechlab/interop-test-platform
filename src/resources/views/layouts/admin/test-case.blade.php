@extends('layouts.default')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title mb-3">
                    <b>{{ $testCase->name }}</b>
                </h1>
                <ul class="nav nav-tabs mb-5">
                    <li class="nav-item">
                        <a href="{{ route('admin.test-cases.show', $testCase) }}" class="nav-link @if (request()->routeIs('admin.test-cases.show', $testCase)) active @endif">
                            {{ __('Overview') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.test-cases.test-steps.index', $testCase) }}" class="nav-link @if (request()->routeIs('admin.test-cases.test-steps.index')) active @endif">
                            {{ __('Test Steps') }}
                        </a>
                    </li>
                </ul>
                @yield('content')
            </div>
        </div>
    </div>
@endsection
