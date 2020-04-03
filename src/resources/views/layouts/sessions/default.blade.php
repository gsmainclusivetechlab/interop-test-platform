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
                                <h3 class="card-title">{{ __('Select use cases') }}</h3>
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-unstyled">
                                    @foreach($session->testCases->mapWithKeys(function ($item) {return [$item->useCase];}) as $useCase)
                                        <li>
                                            <b class="d-block dropdown-toggle py-2 px-4 border-bottom" v-b-toggle.use-case-{{ $useCase->id }}>
                                                {{ $useCase->name }}
                                            </b>
                                            @if ($session->positiveTestCases->where('use_case_id', $useCase->id)->count())
                                                <b-collapse id="use-case-{{ $useCase->id }}" visible>
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <span class="dropdown-toggle d-block px-5 py-2 font-weight-medium border-bottom" v-b-toggle.positive-test-cases-{{ $useCase->id }}>
                                                                {{ __('Happy flow') }}
                                                            </span>
                                                            <b-collapse id="positive-test-cases-{{ $useCase->id }}" visible>
                                                                <ul class="list-unstyled">
                                                                    @foreach($session->positiveTestCases->where('use_case_id', $useCase->id) as $testCase)
                                                                        <li class="list-group-item-action d-flex justify-content-between align-items-center px-6 py-2 border-bottom">
                                                                            <a href="{{ route('sessions.test-cases.show', [$session, $testCase]) }}">{{ $testCase->name }}</a>
                                                                            @if($lastRun = $session->testRuns()->latest()->completed()->where('test_case_id', $testCase->id)->first())
                                                                                <span class="flex-shrink-0 mr-0 ml-1 status-icon @if($lastRun->successful) bg-success @else bg-danger @endif"></span>
                                                                            @else
                                                                                <span class="flex-shrink-0 mr-0 ml-1 status-icon bg-secondary"></span>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </b-collapse>
                                                        </li>
                                                    </ul>
                                                </b-collapse>
                                            @endif

                                            @if ($session->negativeTestCases->where('use_case_id', $useCase->id)->count())
                                                <b-collapse id="use-case-{{ $useCase->id }}" visible>
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <span class="dropdown-toggle d-block px-5 py-2 font-weight-medium border-bottom" v-b-toggle.negative-test-cases-{{ $useCase->id }}>
                                                                {{ __('Unhappy flow') }}
                                                            </span>
                                                            <b-collapse id="negative-test-cases-{{ $useCase->id }}" visible>
                                                                <ul class="list-unstyled">
                                                                    @foreach($session->negativeTestCases->where('use_case_id', $useCase->id) as $testCase)
                                                                        <li class="list-group-item-action d-flex justify-content-between align-items-center px-6 py-2 border-bottom">
                                                                            <a href="{{ route('sessions.test-cases.show', [$session, $testCase]) }}">{{ $testCase->name }}</a>
                                                                            @if($lastRun = $session->testRuns()->latest()->completed()->where('test_case_id', $testCase->id)->first())
                                                                                <span class="flex-shrink-0 mr-0 ml-1 status-icon @if($lastRun->successful) bg-success @else bg-danger @endif"></span>
                                                                            @else
                                                                                <span class="flex-shrink-0 mr-0 ml-1 status-icon bg-secondary"></span>
                                                                            @endif
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
                        <div class="row">
                            <div class="col">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
