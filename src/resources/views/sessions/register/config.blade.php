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
                                    <div class="selectgroup w-100 @error("components.{$component->id}.sut") is-invalid @enderror">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="components[{{ $component->id }}][sut]" value="1" class="selectgroup-input" {{ old("components.{$component->id}.sut", 0) == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">
                                                {{ __('SUT') }}
                                            </span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="components[{{ $component->id }}][sut]" value="0" class="selectgroup-input" {{ old("components.{$component->id}.sut", 0) == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button">
                                                {{ __('Simulated') }}
                                            </span>
                                        </label>
                                    </div>
                                    @error("components.{$component->id}.sut")
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-7">
                                    <label class="form-label font-weight-normal">{{ __('URL') }}</label>
                                    <input class="form-control @error("components.{$component->id}.base_url") is-invalid @enderror" name="components[{{ $component->id }}][base_url]" value="{{ old("components.{$component->id}.base_url", $component->apiService->base_url) }}">
                                    @error("components.{$component->id}.base_url")
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
