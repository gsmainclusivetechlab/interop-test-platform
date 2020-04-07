@extends('layouts.sessions.register', $scenario)

@section('title', __('Configure components'))

@section('content')
    <form class="flow-chart-content" action="{{ route('sessions.register.config', $session) }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">@yield('title')</h3>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ __('Please add your custom URL for the component(s) you want to use as SUT and select the existing Simulators for the other Components') }}</p>
                <div class="mt-4">
                    @foreach($components as $component)
                        <div class="form-group">
                            <strong class="d-inline-block mb-1">
                                {{ $component->name }}
                            </strong>
                            <div class="row">
                                <div class="col-5">
                                    <label class="form-label font-weight-normal">
                                        {{ __('Type') }}
                                    </label>
                                    <select class="form-control custom-select" @change="handleSessionComponentsSelect">
                                        <option value="">{{ __('SUT') }}</option>
                                        @foreach($component->api->apiServers as $apiServer)
                                            <option value="{{ $apiServer->base_url }}">{{ $apiServer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-7">
                                    <label class="form-label font-weight-normal">{{ __('URL') }}</label>
                                    <input class="form-control" value="https://sp.com" disabled>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <a href="{{ route('sessions.register.edit', $session) }}" class="btn btn-outline-primary">{{ __('Back') }}</a>
            <button type="submit" class="btn btn-primary">{{ __('Finish') }}</button>
        </div>
    </form>
@endsection
