@extends('layouts.session', $session)

@section('title', $session->name)

@section('session-header-right')
    <div class="d-flex">
        <div class="flex-shrink-0 btn-group btn-group-sm mr-2" role="group" aria-label="Test case data controls">
            <button type="button" class="btn btn-secondary border" data-fancybox data-src="#flow-diagram">
                {{ __('Use case flow') }}
            </button>
{{--            <button type="button" class="btn btn-secondary border" data-fancybox data-src="#test-data">--}}
{{--                {{ __('Diagram') }}--}}
{{--            </button>--}}
        </div>
        <div class="input-group">
            <input id="run-url-{{ $testCase->id }}" type="text" class="form-control" readonly value="{{ route('testing.run', ['session' => $session, 'testCase' => $testCase]) }}">
            <span class="input-group-append">
                <button class="btn btn-white border" type="button" data-clipboard-target="#run-url-{{ $testCase->id }}">
                    <i class="fe fe-copy"></i>
                </button>
            </span>
        </div>
    </div>

    <div id="flow-diagram" class="col-8 p-0 rounded" style="display: none">
        @include('sessions.includes.test-case-flow-chart', $testCase)
    </div>

{{--    <div id="test-data" class="col-6 p-0 rounded" style="display: none">--}}
{{--        <div class="card mb-0">--}}
{{--            <div class="card-header">--}}
{{--                <h2 class="card-title">--}}
{{--                    <b>{{ $testCase->name }}</b>--}}
{{--                </h2>--}}
{{--            </div>--}}
{{--            <div class="card-body bg-light p-0">--}}
{{--                <div class="py-3 px-4">--}}
{{--                    <p><strong>Precondition</strong></p>--}}
{{--                    <p class="mb-0">--}}
{{--                        <b>Headers</b>--}}
{{--                        <pre class="mb-0 p-0">--}}
{{--<code>{--}}
{{--    "Accept": "application/json",--}}
{{--    "Content-Type": "application/json",--}}
{{--    "X-Callback-URL": "http://example.com/example",--}}
{{--    "X-Date": "2020-02-20T10:28:44.466Z"--}}
{{--}</code>--}}
{{--                        </pre>--}}
{{--                    </p>--}}
{{--                    <p class="mb-0">--}}
{{--                        <b>Data body</b>--}}
{{--                        <pre class="mb-0 p-0">--}}
{{--<code>{--}}
{{--    "amount":"100.00",--}}
{{--    "currency":"USD",--}}
{{--    "type":"merchantpay",--}}
{{--    "debitParty": [{"key":"msisdn", "value":"+33555123456"}],--}}
{{--    "creditParty": [{"key":"msisdn", "value":"+33555789123"}]--}}
{{--}</code>--}}
{{--                        </pre>--}}
{{--                    </p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection

@section('session-sidebar')
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
@endsection

@section('session-content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <a href="{{ route('sessions.test_cases.show', [$session, $testCase]) }}" class="text-decoration-none">
                            <i class="fe fe-chevron-left"></i>
                        </a>
                        {{ $testRun->uuid }}
                    </h3>
                    <div class="card-options">
                        @if ($sucessful = $testRun->testResults()->where('successful', true)->count())
                            <span class="text-success mr-2">
                                <i class="fe fe-check"></i>
                                {{ __(':n Pass', ['n' => $sucessful]) }}
                            </span>
                        @endif

                            @if ($unsucessful = $testRun->testResults()->where('successful', false)->count())
                            <span class="text-danger mr-2">
                                <i class="fe fe-alert-circle"></i>
                                {{ __(':n Fail', ['n' => $unsucessful]) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body bg-light p-0">
                    <div class="px-4 py-6">
                        <flow-chart>
                            graph LR;
                            @foreach($session->scenario->components as $component)
                                {{ $component->id }}({{$component->name}})@if($component->sut):::is-active @endif;
                                @foreach ($component->paths as $connection)
                                    {{ $component->id }}
                                    @if($component->simulated && $connection->simulated) --> @else -.-> @endif
                                    @if($component->is($testResult->testStep->source) && $connection->is($testResult->testStep->target))
                                        |{{ __('Step :n', ['n' => $testResult->testStep->position]) }}| {{ $connection->id }};
                                    @else
                                        {{ $connection->id }};
                                    @endif
                                @endforeach
                            @endforeach
                        </flow-chart>
                    </div>
                    <div class="rounded-0 bg-white border-top">
                        <div class="row">
                            <div class="col-3 pr-0">
                                <ul class="list-unstyled mb-0">
                                    @foreach ($testRun->testSteps as $step)
                                        @if($stepResult = $testRun->testResults->where('test_step_id', $step->id)->first())
                                            <li class="list-group-item-action @if($step->is($testResult->testStep)) bg-light @endif">
                                                <a href="{{ route('sessions.test_cases.results', [$session, $testCase, $testRun, $step->position]) }}" class="d-flex justify-content-between align-items-center py-2 px-4 text-reset text-decoration-none">
                                                    <div class="mr-1 text-truncate">
                                                        <b>
                                                            {{ __('Step :n', ['n' => $step->position]) }}
                                                        </b>

                                                        <div class="d-flex align-items-baseline text-truncate">
                                                            @switch($stepResult->request->method())
                                                                @case('POST')
                                                                <span class="font-weight-bold text-orange">
                                                                    {{ $stepResult->request->method() }}
                                                                </span>
                                                                @break

                                                                @case('PUT')
                                                                <span class="font-weight-bold text-blue">
                                                                    {{ $stepResult->request->method() }}
                                                                </span>
                                                                @break

                                                                @case('DELETE')
                                                                <span class="font-weight-bold text-red">
                                                                    {{ $stepResult->request->method() }}
                                                                </span>
                                                                @break

                                                                @default
                                                                <span class="font-weight-bold text-mint">
                                                                    {{ $stepResult->request->method() }}
                                                                </span>
                                                            @endswitch

                                                            <span class="d-inline-block ml-1 text-truncate" title="{{ $stepResult->request->method() }} {{ $stepResult->request->path() }}">
                                                                {{ $stepResult->request->path() }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <span class="flex-shrink-0 status-icon mr-0 @if($stepResult->successful) bg-success @else bg-danger @endif"></span>
                                                </a>
                                            </li>
                                        @else
                                            <li class="d-flex align-items-center py-2 px-4 text-black-50">
                                                <div class="text-truncate">
                                                    <b>
                                                        {{ __('Step :n', ['n' => $step->position]) }}
                                                    </b>
                                                    <div class="text-truncate" title="{{ $step->name }}">
                                                        {{ $step->name }}
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-9 pl-0 border-left">
                                <div class="lead p-4">
                                    <b class="text-nowrap">
                                        {{ __('Step :n', ['n' => $testResult->testStep->position]) }}
                                    </b>
                                    <div class="d-flex align-items-baseline text-truncate">
                                        <u class="mr-2">{{ $testResult->request->method() }} {{ $testResult->request->path() }}</u>
                                        <span class="badge px-4 ml-3 py-2 bg-success">
                                            {{ __('HTTP :status', ['status' => ($testResult->response) ? $testResult->response->status() : __('Unknown')]) }}
                                        </span>
                                    </div>
                                </div>
                                @if($testResult->successful)
                                    <div class="lead alert-success p-4">
                                        {{ __('Pass') }}
                                    </div>
                                @else
                                    <div class="lead alert-danger p-4">
                                        {{ __('Fail') }}
                                    </div>
                                @endif
                                <div class="px-4 py-2">
                                    <ul class="m-0 p-0">
                                        @foreach($testResult->testRequestExecutions as $testExecution)
                                        <li class="d-flex flex-wrap py-2">
                                            <div class="d-flex align-items-center">
                                                <span class="badge d-flex align-items-center justify-content-center flex-shrink-0 h-5 mr-2 w-8 text-uppercase bg-{{ $testExecution->status_type }}">
                                                    {{ $testExecution->status_label }}
                                                </span>
                                                <span class="d-flex align-items-center" @if ($testExecution->message) v-b-toggle="'{{ $testExecution->id }}'" @endif>
                                                    {{ $testExecution->testScript->name }}
                                                </span>
                                            </div>
                                            <b-collapse id="{{ $testExecution->id }}" class="w-100 ml-8 pl-2">
                                                @if ($testExecution->message)
                                                    <p class="mb-0 small">{{ $testExecution->message }}</p>
                                                @endif
                                            </b-collapse>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @if($request = $testResult->request)
                                    <div class="p-4">
                                        <strong class="lead d-block mb-2 font-weight-bold">
                                            {{ __('Request') }}
                                        </strong>
                                        <div class="border">
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border">
                                                    <strong>{{ __('Url') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ $request->url() }}
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border">
                                                    <strong>{{ __('Method') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ $request->method() }}
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.request-headers-{{ $testResult->id }}>
                                                    <strong>{{ __('Headers') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ __('(:n) params', ['n' => count($request->headers())]) }}
                                                </div>
                                            </div>
                                            <b-collapse id="request-headers-{{ $testResult->id }}">
                                                <div class="d-flex">
                                                    <div class="w-25 px-4 py-2 border"></div>
                                                    <div class="w-75 px-4 py-2 border">
                                                        <div class="mb-0 p-0 bg-transparent json-tree">
                                                            <code v-pre class="json-tree-code">@json($request->headers(), JSON_PRETTY_PRINT)</code>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.request-body-{{ $testResult->id }}>
                                                    <strong>{{ __('Body') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ __('(:n) params', ['n' => count($request->json())]) }}
                                                </div>
                                            </div>
                                            <b-collapse id="request-body-{{ $testResult->id }}">
                                                <div class="d-flex">
                                                    <div class="w-25 px-4 py-2 border"></div>
                                                    <div class="w-75 px-4 py-2 border">
                                                        <div class="mb-0 p-0 bg-transparent json-tree">
                                                            <code v-pre class="json-tree-code">@json($request->json(), JSON_PRETTY_PRINT)</code>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                        </div>
                                    </div>
                                @endif

                                <div class="px-4 py-2">
                                    <ul class="m-0 p-0">
                                        @foreach($testResult->testResponseExecutions as $testExecution)
                                            <li class="d-flex flex-wrap py-2">
                                                <div class="d-flex align-items-center">
                                                <span class="badge d-flex align-items-center justify-content-center flex-shrink-0 h-5 mr-2 w-8 text-uppercase bg-{{ $testExecution->status_type }}">
                                                    {{ $testExecution->status_label }}
                                                </span>
                                                    <span class="d-flex align-items-center" @if ($testExecution->message) v-b-toggle="'{{ $testExecution->id }}'" @endif>
                                                    {{ $testExecution->testScript->name }}
                                                </span>
                                                </div>
                                                <b-collapse id="{{ $testExecution->id }}" class="w-100 ml-8 pl-2">
                                                    @if ($testExecution->message)
                                                        <p class="mb-0 small">{{ $testExecution->message }}</p>
                                                    @endif
                                                </b-collapse>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                @if($response = $testResult->response)
                                    <div class="p-4">
                                        <strong class="lead d-block mb-2 font-weight-bold">
                                            {{ __('Response') }}
                                        </strong>
                                        <div class="border">
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border">
                                                    <strong>{{ __('Status') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ $response->status() }}
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.response-headers-{{ $testResult->id }}>
                                                    <strong>{{ __('Headers') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ __('(:n) params', ['n' => count($response->headers())]) }}
                                                </div>
                                            </div>
                                            <b-collapse id="response-headers-{{ $testResult->id }}">
                                                <div class="d-flex">
                                                    <div class="w-25 px-4 py-2 border"></div>
                                                    <div class="w-75 px-4 py-2 border">
                                                        <div class="mb-0 p-0 bg-transparent json-tree">
                                                            <code v-pre class="json-tree-code">@json($response->headers(), JSON_PRETTY_PRINT)</code>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.response-body-{{ $testResult->id }}>
                                                    <strong>{{ __('Body') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ __('(:n) params', ['n' => count($response->json())]) }}
                                                </div>
                                            </div>
                                            <b-collapse id="response-body-{{ $testResult->id }}">
                                                <div class="d-flex">
                                                    <div class="w-25 px-4 py-2 border"></div>
                                                    <div class="w-75 px-4 py-2 border">
                                                        <div class="mb-0 p-0 bg-transparent json-tree">
                                                            <code v-pre class="json-tree-code">@json($response->json(), JSON_PRETTY_PRINT)</code>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
