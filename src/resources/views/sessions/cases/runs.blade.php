@extends('layouts.app')

@section('title', $session->name)

@section('content')
    @include('sessions.includes.header', ['session' => $session])
    <div class="row align-items-start">
        @include('sessions.cases.includes.sidebar', ['case' => $case])
        <div class="col-9 mt-3">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <a href="{{ route('sessions.cases.show', [$session, $case]) }}" class="text-decoration-none">
                                    <i class="fe fe-chevron-left"></i>
                                </a>
                                {{ $run->uuid }} ({{ $case->name }})
                            </h3>
                            <div class="card-options">
                                @if ($run->pass_results_count)
                                    <span class="text-success mr-2">
                                        <i class="fe fe-check"></i>
                                        {{ __(':n Pass', ['n' => $run->pass_results_count]) }}
                                    </span>
                                @endif

                                @if ($run->fail_results_count)
                                    <span class="text-danger mr-2">
                                        <i class="fe fe-alert-circle"></i>
                                        {{ __(':n Fail', ['n' => $run->fail_results_count]) }}
                                    </span>
                                @endif

                                @if ($run->error_results_count)
                                    <span class="text-warning mr-2">
                                        <i class="fe fe-alert-triangle"></i>
                                        {{ __(':n Error', ['n' => $run->error_results_count]) }}
                                    </span>
                                @endif

                                @if ($run->steps_count - $run->results_count)
                                    <span class="text-secondary mr-2">
                                        <i class="fe fe-alert-octagon"></i>
                                        {{ __(':n Not Executed', ['n' => $run->steps_count - $run->results_count]) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body bg-light p-0">
                            <div class="px-4 py-6">
                                <flow-chart v-cloak>
                                    graph LR;
                                        @foreach($session->scenario->components as $component)
                                            {{ $component->id }}({{$component->name}})@if($component->name == 'Service Provider'):::is-active @endif;
                                            @foreach ($component->connections as $connection)
                                                {{ $component->id }}
                                                @if($connection->pivot->simulated) --> @else -.-> @endif
                                                @if($component->is($result->step->source) && $connection->is($result->step->target))
                                                    |active| {{ $connection->id }}
                                                @else
                                                    {{ $connection->id }}
                                                @endif
                                            @endforeach
                                        @endforeach
                                        classDef node fill:#fff,stroke:#fff,color:#242529
                                </flow-chart>
                            </div>
                            <div class="rounded-0 bg-white border-top">
                                <div class="row">
                                    <div class="col-3 pr-0">
                                        <ul class="list-unstyled mb-0">
                                            @foreach ($run->steps as $step)
                                                @if($stepResult = $run->results()->where('step_id', $step->id)->first())
                                                    <li class="list-group-item-action d-flex align-items-baseline py-3 px-4 @if($step->is($result->step)) bg-light @endif">
                                                        <a href="{{ route('sessions.cases.runs.show', [$session, $case, $run, $step->position]) }}" class="d-flex flex-wrap align-items-center text-reset text-decoration-none">
                                                            <b class="text-nowrap">
                                                                {{ __('Step :n', ['n' => $step->position]) }}
                                                            </b>
                                                            @switch($step->method)
                                                                @case('POST')
                                                                <span class="badge d-flex justify-content-center align-items-center mx-2 w-8 h-5 bg-mint">
                                                                    {{ $step->method }}
                                                                </span>
                                                                @break

                                                                @case('PUT')
                                                                <span class="badge d-flex justify-content-center align-items-center mx-2 w-8 h-5 bg-orange">
                                                                    {{ $step->method }}
                                                                </span>
                                                                @break

                                                                @case('DELETE')
                                                                <span class="badge d-flex justify-content-center align-items-center mx-2 w-8 h-5 bg-red">
                                                                    {{ $step->method }}
                                                                </span>
                                                                @break

                                                                @default
                                                                <span class="badge d-flex justify-content-center align-items-center mx-2 w-8 h-5 bg-blue">
                                                                    {{ $step->method }}
                                                                </span>
                                                            @endswitch
                                                            /{{ $step->path }}
                                                        </a>
                                                        <span class="flex-shrink-0 status-icon ml-auto mr-0 bg-{{ $stepResult->status_type }}"></span>
                                                    </li>
                                                @else
                                                    <li class="d-flex align-items-baseline py-3 px-4 text-black-50">
                                                        <span class="d-flex flex-wrap align-items-center">
                                                            <b class="text-nowrap">
                                                                {{ __('Step :n', ['n' => $step->position]) }}
                                                            </b>
                                                            <span class="badge d-flex justify-content-center align-items-center mx-2 w-8 h-5 bg-gray">
                                                                {{ $step->method }}
                                                            </span>
                                                            /{{ $step->path }}
                                                        </span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-9 pl-0 border-left">
                                        <div class="lead p-4">
                                            <b class="text-nowrap">
                                                {{ __('Step :n', ['n' => $result->step->position]) }}
                                            </b>
                                            <u class="mr-2">{{ $result->step->method }} /{{ $result->step->path }}</u>
                                            <span class="badge px-4 ml-3 py-2 bg-success">
                                                {{ __('HTTP :status', ['status' => Arr::get($result->response, 'status', __('Unknown'))]) }}
                                            </span>
                                        </div>
                                        <div class="lead alert-{{ $result->status_type }} p-4">
                                            {{ $result->status_label }}
                                            @if($result->status_message)
                                                <p class="small">{{ $result->status_message }}</p>
                                            @endif
                                        </div>
                                        @if($request = $result->request)
                                            <div class="p-4">
                                                <strong class="lead d-block mb-2 font-weight-bold">
                                                    {{ __('Request') }}
                                                </strong>
                                                <div class="border">
                                                    @if($requestUri = Arr::get($request, 'uri'))
                                                        <div class="d-flex">
                                                            <div class="w-25 px-4 py-2 border">
                                                                <strong>{{ __('Url') }}</strong>
                                                            </div>
                                                            <div class="w-75 px-4 py-2 border">
                                                                {{ $requestUri }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($requestMethod = Arr::get($request, 'method'))
                                                        <div class="d-flex">
                                                            <div class="w-25 px-4 py-2 border">
                                                                <strong>{{ __('Method') }}</strong>
                                                            </div>
                                                            <div class="w-75 px-4 py-2 border">
                                                                {{ $requestMethod }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($requestHeaders = Arr::get($request, 'headers'))
                                                        <div class="d-flex">
                                                            <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.request-headers-{{ $result->id }}>
                                                                <strong>{{ __('Headers') }}</strong>
                                                            </div>
                                                            <div class="w-75 px-4 py-2 border">
                                                                {{ __('(:n) params', ['n' => count($requestHeaders)]) }}
                                                            </div>
                                                        </div>
                                                        <b-collapse id="request-headers-{{ $result->id }}">
                                                            <div class="d-flex">
                                                                <div class="w-25 px-4 py-2 border"></div>
                                                                <div class="w-75 px-4 py-2 border">
                                                                    <div class="mb-0 p-0 bg-transparent json-tree">
                                                                        <code class="json-tree-code">@json($requestHeaders, JSON_PRETTY_PRINT)</code>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </b-collapse>
                                                    @endif

                                                    @if($requestQuery = Arr::get($request, 'query'))
                                                        <div class="d-flex">
                                                            <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.request-query-{{ $result->id }}>
                                                                <strong>{{ __('Query') }}</strong>
                                                            </div>
                                                            <div class="w-75 px-4 py-2 border">
                                                                {{ __('(:n) params', ['n' => count($requestQuery)]) }}
                                                            </div>
                                                        </div>
                                                        <b-collapse id="request-query-{{ $result->id }}">
                                                            <div class="d-flex">
                                                                <div class="w-25 px-4 py-2 border"></div>
                                                                <div class="w-75 px-4 py-2 border">
                                                                    <div class="mb-0 p-0 bg-transparent json-tree">
                                                                        <code class="json-tree-code">@json($requestQuery, JSON_PRETTY_PRINT)</code>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </b-collapse>
                                                    @endif

                                                    @if($requestBody = Arr::get($request, 'body'))
                                                        <div class="d-flex">
                                                            <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.request-body-{{ $result->id }}>
                                                                <strong>{{ __('Body') }}</strong>
                                                            </div>
                                                            <div class="w-75 px-4 py-2 border">
                                                                {{ __('(:n) params', ['n' => count($requestBody)]) }}
                                                            </div>
                                                        </div>
                                                        <b-collapse id="request-body-{{ $result->id }}">
                                                            <div class="d-flex">
                                                                <div class="w-25 px-4 py-2 border"></div>
                                                                <div class="w-75 px-4 py-2 border">
                                                                    <div class="mb-0 p-0 bg-transparent json-tree">
                                                                        <code class="json-tree-code">@json($requestBody, JSON_PRETTY_PRINT)</code>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </b-collapse>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if($response = $result->response)
                                            <div class="p-4">
                                                <strong class="lead d-block mb-2 font-weight-bold">
                                                    {{ __('Response') }}
                                                </strong>
                                                <div class="border">
                                                    @if($responseStatus = Arr::get($response, 'status'))
                                                        <div class="d-flex">
                                                            <div class="w-25 px-4 py-2 border">
                                                                <strong>{{ __('Status') }}</strong>
                                                            </div>
                                                            <div class="w-75 px-4 py-2 border">
                                                                {{ $responseStatus }}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($responseHeaders = Arr::get($response, 'headers'))
                                                        <div class="d-flex">
                                                            <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.response-headers-{{ $result->id }}>
                                                                <strong>{{ __('Headers') }}</strong>
                                                            </div>
                                                            <div class="w-75 px-4 py-2 border">
                                                                {{ __('(:n) params', ['n' => count($responseHeaders)]) }}
                                                            </div>
                                                        </div>
                                                        <b-collapse id="response-headers-{{ $result->id }}">
                                                            <div class="d-flex">
                                                                <div class="w-25 px-4 py-2 border"></div>
                                                                <div class="w-75 px-4 py-2 border">
                                                                    <div class="mb-0 p-0 bg-transparent json-tree">
                                                                        <code class="json-tree-code">@json($responseHeaders, JSON_PRETTY_PRINT)</code>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </b-collapse>
                                                    @endif

                                                    @if($responseBody = Arr::get($response, 'body'))
                                                        <div class="d-flex">
                                                            <div class="w-25 px-4 py-2 border dropdown-toggle" v-b-toggle.response-body-{{ $result->id }}>
                                                                <strong>{{ __('Body') }}</strong>
                                                            </div>
                                                            <div class="w-75 px-4 py-2 border">
                                                                {{ __('(:n) params', ['n' => count($responseBody)]) }}
                                                            </div>
                                                        </div>
                                                        <b-collapse id="response-body-{{ $result->id }}">
                                                            <div class="d-flex">
                                                                <div class="w-25 px-4 py-2 border"></div>
                                                                <div class="w-75 px-4 py-2 border">
                                                                    <div class="mb-0 p-0 bg-transparent json-tree">
                                                                        <code class="json-tree-code">@json($responseBody, JSON_PRETTY_PRINT)</code>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </b-collapse>
                                                    @endif
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
        </div>
    </div>
@endsection
