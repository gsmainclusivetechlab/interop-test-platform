@extends('layouts.session', $session)

@section('title', $session->name)

@section('session-header-right')
    <div class="input-group">
        <input id="run-url-{{ $testCase->id }}" type="text" class="form-control" readonly value="{{ route('testing.run', ['testPlan' => $testCase->pivot]) }}">
        <span class="input-group-append">
            <button class="btn border" type="button" data-clipboard-target="#run-url-{{ $testCase->id }}">
                <i class="fe fe-copy"></i>
            </button>
        </span>
    </div>
@endsection

@section('session-sidebar')
    <div class="card mb-0 p-0 border-0 rounded-0 shadow-none">
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
                        {{--                                @if ($run->pass_results_count)--}}
                        {{--                                    <span class="text-success mr-2">--}}
                        {{--                                        <i class="fe fe-check"></i>--}}
                        {{--                                        {{ __(':n Pass', ['n' => $run->pass_results_count]) }}--}}
                        {{--                                    </span>--}}
                        {{--                                @endif--}}

                        {{--                                @if ($run->fail_results_count)--}}
                        {{--                                    <span class="text-danger mr-2">--}}
                        {{--                                        <i class="fe fe-alert-circle"></i>--}}
                        {{--                                        {{ __(':n Fail', ['n' => $run->fail_results_count]) }}--}}
                        {{--                                    </span>--}}
                        {{--                                @endif--}}

                        {{--                                @if ($run->error_results_count)--}}
                        {{--                                    <span class="text-warning mr-2">--}}
                        {{--                                        <i class="fe fe-alert-triangle"></i>--}}
                        {{--                                        {{ __(':n Error', ['n' => $run->error_results_count]) }}--}}
                        {{--                                    </span>--}}
                        {{--                                @endif--}}

                        {{--                                @if ($run->steps_count - $run->results_count)--}}
                        {{--                                    <span class="text-secondary mr-2">--}}
                        {{--                                        <i class="fe fe-alert-octagon"></i>--}}
                        {{--                                        {{ __(':n Not Executed', ['n' => $run->steps_count - $run->results_count]) }}--}}
                        {{--                                    </span>--}}
                        {{--                                @endif--}}
                    </div>
                </div>
                <div class="card-body bg-light p-0">
                    <div class="px-4 py-6">
                        <flow-chart>
                            graph LR;
                            @foreach($session->scenario->components as $component)
                                {{ $component->id }}({{$component->name}})@if($component->name == 'Service Provider'):::is-active @endif;
                                @foreach ($component->paths as $connection)
                                    {{ $component->id }}
                                    @if($connection->pivot->simulated) --> @else -.-> @endif
                                    @if($component->is($testResult->testStep->source) && $connection->is($testResult->testStep->target))
                                        |active| {{ $connection->id }}
                                    @else
                                        {{ $connection->id }}
                                    @endif
                                @endforeach
                            @endforeach
                            classDef node fill:#fff,stroke:#fff,color:#242529
                            classDef clickable fill:#fff,stroke:#fff,color:#242529
                        </flow-chart>
                    </div>
                    <div class="rounded-0 bg-white border-top">
                        <div class="row">
                            <div class="col-3 pr-0">
                                <ul class="list-unstyled mb-0">
                                    @foreach ($testRun->testSteps as $step)
                                        @if($stepResult = $testRun->testResults()->where('test_step_id', $step->id)->first())
                                            <li class="list-group-item-action d-flex align-items-baseline py-3 px-4 @if($step->is($testResult->testStep)) bg-light @endif">
                                                <a href="{{ route('sessions.test_cases.results', [$session, $testCase, $testRun, $step->position]) }}" class="d-flex flex-wrap align-items-center text-reset text-decoration-none">
                                                    <b class="text-nowrap">
                                                        {{ __('Step :n', ['n' => $step->position]) }}
                                                    </b>
                                                    @switch($stepResult->request->getMethod())
                                                        @case('POST')
                                                        <span class="d-inline-block w-8 mx-2 text-center font-weight-bold text-orange">
                                                            {{ $stepResult->request->getMethod() }}
                                                        </span>
                                                        @break

                                                        @case('PUT')
                                                        <span class="d-inline-block w-8 mx-2 text-center font-weight-bold text-blue">
                                                            {{ $stepResult->request->getMethod() }}
                                                        </span>
                                                        @break

                                                        @case('DELETE')
                                                        <span class="d-inline-block w-8 mx-2 text-center font-weight-bold text-red">
                                                            {{ $stepResult->request->getMethod() }}
                                                        </span>
                                                        @break

                                                        @default
                                                        <span class="d-inline-block w-8 mx-2 text-center font-weight-bold text-mint">
                                                            {{ $stepResult->request->getMethod() }}
                                                        </span>
                                                    @endswitch
                                                    {{ $stepResult->request->getUri()->getPath() }}
                                                </a>
                                                <span class="flex-shrink-0 status-icon ml-auto mr-0 bg-{{ $stepResult->status_type }}"></span>
                                            </li>
                                        @else
                                            <li class="d-flex align-items-center py-3 px-4 text-black-50">
                                                        <span class="d-flex flex-wrap align-items-baseline">
                                                            <b class="text-nowrap">
                                                                {{ __('Step :n', ['n' => $step->position]) }}
                                                            </b>
                                                            <span class="d-flex justify-content-baseline align-items-center mx-2">
                                                                {{ $step->name }}
                                                            </span>
                                                        </span>
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
                                    <u class="mr-2">{{ $testResult->request->getMethod() }} {{ $testResult->request->getUri()->getPath() }}</u>
                                    <span class="badge px-4 ml-3 py-2 bg-success">
                                                {{ __('HTTP :status', ['status' => ($testResult->response) ? $testResult->response->getStatusCode() : __('Unknown')]) }}
                                            </span>
                                </div>
                                <div class="lead p-4">
                                    <ul class="m-0 p-0">
                                        @foreach($testResult->testExecutions as $testExecution)
                                            <li class="d-flex align-items-center py-2">
                                                        <span class="badge d-flex align-items-center justify-content-center flex-shrink-0 h-5 mr-2 w-8 text-uppercase bg-{{ $testExecution->status_type }}">
                                                            {{ $testExecution->status_label }}
                                                        </span>
                                                <p class="small mb-0">
                                                    {{ $testExecution->name }}@if ($testExecution->exception): {{ $testExecution->exception }}@endif
                                                </p>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                @if($testResult->exception)
                                    <div class="lead alert-danger p-4">
                                        {{ $testResult->exception }}
                                    </div>
                                @endif

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
                                                    {{ $request->getUri() }}
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border">
                                                    <strong>{{ __('Method') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ $request->getMethod() }}
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.request-headers-{{ $testResult->id }}>
                                                    <strong>{{ __('Headers') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ __('(:n) params', ['n' => count($request->getHeaders())]) }}
                                                </div>
                                            </div>
                                            <b-collapse id="request-headers-{{ $testResult->id }}">
                                                <div class="d-flex">
                                                    <div class="w-25 px-4 py-2 border"></div>
                                                    <div class="w-75 px-4 py-2 border">
                                                        <div class="mb-0 p-0 bg-transparent json-tree">
                                                            <code v-pre class="json-tree-code">@json($request->getHeaders(), JSON_PRETTY_PRINT)</code>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.request-body-{{ $testResult->id }}>
                                                    <strong>{{ __('Body') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ __('(:n) params', ['n' => count(json_decode($request->getBody()->__toString(), true) ?? [])]) }}
                                                </div>
                                            </div>
                                            <b-collapse id="request-body-{{ $testResult->id }}">
                                                <div class="d-flex">
                                                    <div class="w-25 px-4 py-2 border"></div>
                                                    <div class="w-75 px-4 py-2 border">
                                                        <div class="mb-0 p-0 bg-transparent json-tree">
                                                            <code v-pre class="json-tree-code">@json(json_decode($request->getBody()->__toString(), true) ?? [], JSON_PRETTY_PRINT)</code>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                        </div>
                                    </div>
                                @endif

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
                                                    {{ $response->getStatusCode() }}
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.response-headers-{{ $testResult->id }}>
                                                    <strong>{{ __('Headers') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ __('(:n) params', ['n' => count($response->getHeaders())]) }}
                                                </div>
                                            </div>
                                            <b-collapse id="response-headers-{{ $testResult->id }}">
                                                <div class="d-flex">
                                                    <div class="w-25 px-4 py-2 border"></div>
                                                    <div class="w-75 px-4 py-2 border">
                                                        <div class="mb-0 p-0 bg-transparent json-tree">
                                                            <code v-pre class="json-tree-code">@json($response->getHeaders(), JSON_PRETTY_PRINT)</code>
                                                        </div>
                                                    </div>
                                                </div>
                                            </b-collapse>
                                            <div class="d-flex">
                                                <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.response-body-{{ $testResult->id }}>
                                                    <strong>{{ __('Body') }}</strong>
                                                </div>
                                                <div class="w-75 px-4 py-2 border">
                                                    {{ __('(:n) params', ['n' => count(json_decode($response->getBody()->__toString(), true) ?? [])]) }}
                                                </div>
                                            </div>
                                            <b-collapse id="response-body-{{ $testResult->id }}">
                                                <div class="d-flex">
                                                    <div class="w-25 px-4 py-2 border"></div>
                                                    <div class="w-75 px-4 py-2 border">
                                                        <div class="mb-0 p-0 bg-transparent json-tree">
                                                            <code v-pre class="json-tree-code">@json(json_decode($response->getBody()->__toString(), true) ?? [], JSON_PRETTY_PRINT)</code>
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
