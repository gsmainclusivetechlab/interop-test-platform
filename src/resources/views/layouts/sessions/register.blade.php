@extends('layouts.default')

@section('main')
    <div class="page-header">
        <h1 class="page-title mx-auto">
            <b>{{ __('Create new session') }}</b>
        </h1>
    </div>
    <div class="container">
        <div class="row">
            <div class="col">
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
        <div class="row mb-5">
            <div class="col">
                <flow-chart class="mb-3">
                    graph LR;
                    @foreach($scenario->components as $component)
                        {{ $component->id }}({{$component->name}});
                        @foreach ($component->paths as $connection)
                            {{ $component->id }} @if($component->simulated && $connection->simulated) --> @else -.-> @endif {{ $connection->id }};
                        @endforeach
                    @endforeach
                </flow-chart>

                <div class="d-flex justify-content-center">
                    <div class="d-inline-flex align-items-center mx-2">
                        <span class="ic-arrow-right mr-2"></span>
                        <small>{{ __('Simulated') }}</small>
                    </div>
                    <div class="d-inline-flex align-items-center mx-2">
                        <span class="ic-arrow-right mr-2 border-dashed"></span>
                        <small>{{ __('Not Simulated') }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col mx-auto">
                @yield('content')
            </div>
        </div>
    </div>
@endsection
