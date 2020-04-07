@extends('layouts.default')

@section('main')
    <div class="page-header">
        <h1 class="page-title mx-auto">
            <b>{{ __('Create new session') }}</b>
        </h1>
    </div>
    <div class="row d-flex justify-content-center">
        <div class="col-md-8">
            <div class="steps steps-counter steps-primary">
                <span class="step-item @if (request()->routeIs('sessions.register.create') || request()->routeIs('sessions.register.edit')) active @endif">
                    <span class="d-inline-block mt-2">{{ __('Session info') }}</span>
                </span>
                <span class="step-item @if (request()->routeIs('sessions.register.config')) active @endif">
                    <span class="d-inline-block mt-2">{{ __('Configure components') }}</span>
                </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="d-flex flex-column align-items-center justify-content-center">
                <div class="w-100 flow-chart-wrapper">
                    <div class="mb-6">
                        <flow-chart>
                            graph LR;
                            @foreach($scenario->components as $component)
                                {{ $component->id }}({{$component->name}})@if($component->sut):::is-active @endif;
                                @foreach ($component->paths as $connection)
                                    {{ $component->id }} @if($component->simulated && $connection->simulated) --> @else -.-> @endif {{ $connection->id }};
                                @endforeach
                            @endforeach
                        </flow-chart>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="col-3">
                                <div class="flow-chart-legend d-flex flex-column">
                                    <div class="d-inline-flex align-items-center">
                                        <span class="ic-arrow-right mr-2"></span>
                                        <small>{{ __('Simulated') }}</small>
                                    </div>
                                    <div class="d-inline-flex align-items-center">
                                        <span class="ic-arrow-right mr-2 border-dashed"></span>
                                        <small>{{ __('Not Simulated') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-9">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
