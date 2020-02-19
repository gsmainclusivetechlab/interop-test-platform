@extends('layouts.app')

@section('title', __('Session :name', ['name' => $session->name]))

@section('content')
    @include('sessions.includes.header', ['session' => $session])
    <div class="row align-items-start">
        <div class="col-3 flex-fill bg-white p-0">
            <div class="card mb-0 p-0 border-0 rounded-0 shadow-none">
                <div class="card-header px-4">
                    <h3 class="card-title">
                        <a href="{{ route('sessions.show', $session) }}" class="text-decoration-none">
                            <i class="fe fe-chevron-left"></i>
                        </a>
                        <span>{{ $case->name }}</span>
                    </h3>
                    <a href="#" class="lead ml-auto text-decoration-none">
                        <i class="fe fe-download"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($case->description || $case->preconditions)
                        <ul class="list-unstyled">
                            @if ($case->description)
                                <li class="py-3 px-4 border-bottom">
                                    <strong class="d-block mb-1">{{ __('Description') }}</strong>
                                    <p class="mb-0">
                                        {{ $case->description }}
                                    </p>
                                </li>
                            @endif

                            @if ($case->preconditions)
                                <li class="py-3 px-4 border-bottom">
                                    <strong class="d-block mb-1">{{ __('Preconditions') }}</strong>
                                    <p class="mb-0">
                                        {{ $case->preconditions }}
                                    </p>
                                </li>
                            @endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-9 mt-3">
            {{ route('testing.run', [$case->pivot->uuid]) }}
        </div>
    </div>
@endsection
