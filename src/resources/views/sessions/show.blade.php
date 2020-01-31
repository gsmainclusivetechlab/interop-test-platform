@extends('layouts.app')

@section('title', __('Session :name', ['name' => $session->name]))

@section('content')
    <div class="row border-bottom">
        <div class="col">
            <div class="page-header m-0 py-2">
                <h1 class="page-title">
                    <b>@yield('title')</b>
                </h1>
                <span class="badge badge-success ml-2 p-1">{{ __('Active') }}</span>
                <div class="ml-4 pt-1">
                    {{ __('Execution') }}:
                    <i class="fe fe-briefcase"></i>
                    <small>{{ $session->suites->count() }}</small>
                    <i class="fe fe-file-text"></i>
                    <small>{{ $session->cases->count() }}</small>
                </div>
                <div class="col-2">
                    <b-progress class="h-3 rounded-0"></b-progress>
                </div>
                <a href="#" class="btn btn-outline-primary ml-4">{{ __('Deactivate') }}</a>
            </div>
        </div>
    </div>
    <div class="row align-items-start">
        <div class="col-3 flex-fill bg-white p-0">
            <div class="card mb-0 p-0 border-0 rounded-0 shadow-none">
                <div class="card-header px-4">
                    <h3 class="card-title">{{ __('Select use cases') }}</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-unstyled">
                        @foreach($session->suites as $suite)
                            <li>
                                <b class="d-block dropdown-toggle py-2 px-4 border-bottom" v-b-toggle.suite-{{ $suite->id }}>
                                    {{ $suite->name }}
                                </b>
                                @if ($session->positiveCases->where('test_suite_id', $suite->id)->count())
                                    <b-collapse id="suite-{{ $suite->id }}" visible>
                                        <ul class="list-unstyled">
                                            <li>
                                                <span class="dropdown-toggle d-block px-5 py-2 font-weight-medium border-bottom" v-b-toggle.positive-cases-{{ $suite->id }}>
                                                    {{ __('Happy flow') }}
                                                </span>
                                                <b-collapse id="positive-cases-{{ $suite->id }}" visible>
                                                    <ul class="list-unstyled">
                                                        @foreach($session->positiveCases->where('test_suite_id', $suite->id) as $case)
                                                            <li class="list-group-item-action d-flex justify-content-between align-items-center px-6 py-2 border-bottom">
                                                                <a href="{{ route('sessions.cases.show', [$session, $case]) }}">{{ $case->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </b-collapse>
                                            </li>
                                        </ul>
                                    </b-collapse>
                                @endif

                                @if ($session->negativeCases->where('test_suite_id', $suite->id)->count())
                                    <b-collapse id="suite-{{ $suite->id }}" visible>
                                        <ul class="list-unstyled">
                                            <li>
                                                <span class="dropdown-toggle d-block px-5 py-2 font-weight-medium border-bottom" v-b-toggle.negative-cases-{{ $suite->id }}>
                                                    {{ __('Unhappy flow') }}
                                                </span>
                                                <b-collapse id="negative-cases-{{ $suite->id }}" visible>
                                                    <ul class="list-unstyled">
                                                        @foreach($session->negativeCases->where('test_suite_id', $suite->id) as $case)
                                                            <li class="list-group-item-action d-flex justify-content-between align-items-center px-6 py-2 border-bottom">
                                                                <a href="{{ route('sessions.cases.show', [$session, $case]) }}">{{ $case->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </b-collapse>
                                            </li>
                                        </ul>
                                    </b-collapse>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-9 mt-3">

        </div>
    </div>
@endsection
