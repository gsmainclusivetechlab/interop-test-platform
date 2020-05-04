@extends('layouts.sessions.test-case', [$session, $testCase])

@section('title', $session->name)

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <a href="{{ route('sessions.test-cases.show', [$session, $testCase]) }}" class="text-decoration-none">
                    <i class="fe fe-chevron-left"></i>
                </a>
                {{ $testRun->uuid }}
            </h3>
            <div class="card-options">
                @if ($sucessful = $testRun->testResults()->successful()->count())
                    <span class="text-success mr-2">
                        <i class="fe fe-check"></i>
                        {{ __(':n Pass', ['n' => $sucessful]) }}
                    </span>
                @endif

                @if ($unsucessful = $testRun->testResults()->unsuccessful()->count())
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
                        {{ $component->id }}({{$component->name}})@if($session->suts->contains($component)):::is-active @endif;
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
                                @if($stepResult = $testRun->testResults()->completed()->where('test_step_id', $step->id)->first())
                                    <li class="list-group-item-action @if($step->is($testResult->testStep)) bg-light @endif">
                                        <a href="{{ route('sessions.test-cases.test-runs.show', [$session, $testCase, $testRun, $step->position]) }}" class="d-flex justify-content-between align-items-center py-2 px-4 text-reset text-decoration-none">
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
                                            <div class="text-truncate" title="{{ $step->forward }}">
                                                {{ $step->forward }}
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-9 pl-0 border-left">
                        <div class="lead p-4">
                            <div class="d-flex justigy-content-between">
                                <b class="text-nowrap">
                                    {{ __('Step :n', ['n' => $testResult->testStep->position]) }}
                                </b>
                                <small class="d-inline-block ml-auto">
                                    Status:
                                    <span class="text-success">
                                        {{ __('HTTP :status', ['status' => ($testResult->response) ? $testResult->response->status() : __('Unknown')]) }}
                                    </span>
                                </small>
                            </div>
                            <div class="text-truncate">
                                <u class="mr-2">{{ $testResult->request->method() }} {{ $testResult->request->path() }}</u>
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

                        <div class="p-4">
                            <div class="d-flex mb-2">
                                <strong class="lead d-block mr-auto font-weight-bold">
                                    {{ __('Performed tests') }}
                                </strong>
                            </div>
                            <ul class="m-0 p-0">
                                @foreach($testResult->testExecutions as $testExecution)
                                    <li class="d-flex flex-wrap py-2">
                                        <div class="d-flex align-items-center">
                                            <span class="badge d-flex align-items-center justify-content-center flex-shrink-0 h-5 mr-2 w-8 text-uppercase @if($testExecution->successful) bg-success @else bg-danger @endif">
                                                @if ($testExecution->successful)
                                                    {{ __('Pass') }}
                                                @else
                                                    {{ __('Fail') }}
                                                @endif
                                            </span>
                                            <span class="d-flex align-items-center" @if ($testExecution->message) v-b-toggle="'{{ $testExecution->id }}'" @endif>
                                                {{ $testExecution->name }}
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
                                <div class="d-flex mb-2">
                                    <strong class="lead d-block mr-auto font-weight-bold">
                                        {{ __('Request') }}
                                    </strong>
                                </div>
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
                                                <div class="mb-0 p-0 bg-transparent">
                                                    <json-tree :code='@json($request->headers(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)'></json-tree>
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
                                                <div class="mb-0 p-0 bg-transparent">
                                                    <json-tree :code='@json($request->json(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)'></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </b-collapse>
                                </div>
                            </div>
                        @endif

                        @if($response = $testResult->response)
                            <div class="p-4">
                                <div class="d-flex mb-2">
                                    <strong class="lead d-block mr-auto font-weight-bold">
                                        {{ __('Response') }}
                                    </strong>
                                </div>
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
                                                <div class="mb-0 p-0 bg-transparent">
                                                    <json-tree :code='@json($response->headers(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)'></json-tree>
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
                                                <div class="mb-0 p-0 bg-transparent">
                                                    <json-tree :code='@json($response->json(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)'></json-tree>
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
@endsection