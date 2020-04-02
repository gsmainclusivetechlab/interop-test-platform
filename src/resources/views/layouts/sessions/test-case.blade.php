@extends('layouts.default')

@section('main')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row border-bottom pb-5 align-items-center">
                    <div class="col-6 d-flex flex-wrap">
                        <h1 class="page-title mr-2">
                            <b>{{ $session->name }}</b>
                        </h1>
                    </div>
                    <div class="ml-auto col-2">
                        <div class="mb-1">
                            {{ __('Execution') }}:
                            <i class="fe fe-briefcase"></i>
                            <small>{{ $session->testCases->unique('use_case_id')->count() }}</small>
                            <i class="fe fe-file-text"></i>
                            <small>{{ $session->testCases->count() }}</small>
                        </div>
                        <div style="min-width: 180px">
                            <x-sessions.latest-test-runs-progress :session="$session" />
                        </div>
                    </div>
                </div>
                <div class="row align-items-start">
                    <div class="col-3 mt-3 pr-0">
                        <div class="card mb-0">
                            <div class="card-header px-4">
                                <h3 class="card-title">
                                    <a href="{{ route('sessions.show', $session) }}" class="text-decoration-none">
                                        <i class="fe fe-chevron-left"></i>
                                    </a>
                                    {{ $testCase->name }}
                                </h3>
                            </div>
                            <div class="card-body p-0">
                                @if ($testCase->description || $testCase->precondition)
                                    <ul class="list-unstyled">
                                        @if ($testCase->description)
                                            <li class="py-3 px-4 border-bottom" v-pre>
                                                <p>
                                                    <strong>{{ __('Description') }}</strong>
                                                </p>
                                                <p>{{ $testCase->description }}</p>
                                            </li>
                                        @endif

                                        @if ($testCase->precondition)
                                            <li class="py-3 px-4 border-bottom" v-pre>
                                                <p>
                                                    <strong>{{ __('Precondition') }}</strong>
                                                </p>
                                                <p>{{ $testCase->precondition }}</p>
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-9 mt-3">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex align-items-start border-bottom mb-4">
                                    <ul class="nav nav-tabs mx-0 border-0">
                                        <li class="nav-item">
                                            <a href="{{ route('sessions.test-cases.show', [$session, $testCase]) }}" class="nav-link @if (request()->routeIs('sessions.test-cases.show') || request()->routeIs('sessions.test-cases.test-runs.*')) active @endif">
                                                {{ __('Overview') }}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('sessions.test-cases.test-data.index', [$session, $testCase]) }}" class="nav-link @if (request()->routeIs('sessions.test-cases.test-data.*')) active @endif">
                                                {{ __('Test Data') }}
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="col-5 ml-auto pr-0 pt-1">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 btn-group btn-group-sm mr-2" role="group" aria-label="Test case data controls">
                                                <button type="button" class="btn btn-secondary border" data-fancybox data-src="#flow-diagram">
                                                    {{ __('Use case flow') }}
                                                </button>
                                            </div>
                                            <div class="input-group">
                                                <input id="run-url-{{ $testCase->id }}" type="text" class="form-control h-100" readonly value="{{ route('testing.run', ['session' => $session, 'testCase' => $testCase]) }}">
                                                <span class="input-group-append">
                                                    <button class="btn btn-white border" type="button" data-clipboard-target="#run-url-{{ $testCase->id }}" v-b-tooltip.hover title="{{ __('Copy URL') }}">
                                                        <i class="fe fe-copy"></i>
                                                    </button>
                                                </span>
                                            </div>
                                            <form action="#" class="flex-shrink-0 ml-2">
                                                <button class="btn btn-primary">
                                                    {{ __('Run') }}
                                                </button>
                                            </form>
                                        </div>
                                        <div id="flow-diagram" class="col-8 p-0 rounded" style="display: none">
                                            <div class="card mb-0 bg-light">
                                                <div class="card-header">
                                                    <h2 class="card-title">
                                                        <b>{{ __('Use case flow') }}</b>
                                                    </h2>
                                                </div>
                                                <div class="card-body">
                                                    <flow-chart>
                                                        sequenceDiagram;
                                                        @foreach ($testCase->testSteps as $testStep)
                                                            {{ $testStep->source->name }}->>{{ $testStep->target->name }}: {{ $testStep->forward }};
                                                            {{ $testStep->target->name }}-->>{{ $testStep->source->name }}: {{ $testStep->backward }};
                                                        @endforeach
                                                    </flow-chart>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
