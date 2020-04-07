@extends('layouts.sessions.register', $scenario)

@section('title', __('Session info'))

@section('content')
    <form class="flow-chart-content" action="{{ route('sessions.register.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="row">
                <div class="col-6">
                    <div class="card-header border-0">
                        <h3 class="card-title">@yield('title')</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">
                                {{ __('Name') }}
                            </label>
                            <input name="name" value="{{ old('name') }}" type="text" class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">
                                {{ __('Description') }}
                            </label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card-header border-0">
                        <h3 class="card-title">{{ __('Select use cases') }}</h3>
                    </div>
                    <div class="card-body pl-0">
                        <ul class="list-group overflow-auto" style="height: 320px">
                            @foreach($useCases as $useCase)
                                <li class="list-group-item">
                                    <div class="d-flex align-items-baseline">
                                        <b class="dropdown-toggle" v-b-toggle.use-case-{{ $useCase->id }}>
                                            {{ $useCase->name }}
                                        </b>
                                        <button type="button" class="btn btn-link py-0 font-weight-normal text-decoration-none" @click.prevent="toggleCheckboxes"><i class="fe fe-check-square"></i></button>
                                    </div>
                                    @if ($useCase->positiveTestCases->count())
                                        <b-collapse id="use-case-{{ $useCase->id }}" visible>
                                            <ul class="list-group">
                                                <li class="list-group-item border-0 py-0">
                                                    <div class="d-flex align-items-baseline">
                                                        <span class="d-inline-block dropdown-toggle py-2 font-weight-medium" v-b-toggle.positive-test-cases-{{ $useCase->id }}>
                                                            {{ __('Happy flow') }}
                                                        </span>
                                                        <button type="button" class="btn btn-link py-0 font-weight-normal text-decoration-none" @click.prevent="toggleCheckboxes"><i class="fe fe-check-square"></i></button>
                                                    </div>
                                                    <b-collapse id="positive-test-cases-{{ $useCase->id }}" visible>
                                                        <ul class="list-group">
                                                            @foreach($useCase->positiveTestCases as $testCase)
                                                                <li class="list-group-item">
                                                                    <label class="custom-control custom-checkbox mb-0">
                                                                        <input name="test_cases[{{ $testCase->id }}]" value="{{ $testCase->id }}" type="checkbox" class="custom-control-input" {{ old("test_cases.{$testCase->id}") ? 'checked' : '' }}>
                                                                        <span class="custom-control-label">
                                                                            {{ $testCase->name }}
                                                                        </span>
                                                                    </label>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </b-collapse>
                                                </li>
                                            </ul>
                                        </b-collapse>
                                    @endif

                                    @if ($useCase->negativeTestCases->count())
                                        <b-collapse id="use-case-{{ $useCase->id }}" visible>
                                            <ul class="list-group">
                                                <li class="list-group-item border-0 py-0">
                                                    <div class="d-flex align-items-baseline">
                                                        <span class="d-inline-block dropdown-toggle py-2 font-weight-medium" v-b-toggle.negative-test-cases-{{ $useCase->id }}>
                                                            {{ __('Unhappy flow') }}
                                                        </span>
                                                        <button type="button" class="btn btn-link py-0 font-weight-normal text-decoration-none" @click.prevent="toggleCheckboxes"><i class="fe fe-check-square"></i></button>
                                                    </div>
                                                    <b-collapse id="negative-test-cases-{{ $useCase->id }}" visible>
                                                        <ul class="list-group">
                                                            @foreach($useCase->negativeTestCases as $testCase)
                                                                <li class="list-group-item">
                                                                    <label class="custom-control custom-checkbox mb-0">
                                                                        <input name="test_cases[{{ $testCase->id }}]" value="{{ $testCase->id }}" type="checkbox" class="custom-control-input" {{ old("test_cases.{$testCase->id}") ? 'checked' : '' }}>
                                                                        <span class="custom-control-label">
                                                                            {{ $testCase->name }}
                                                                        </span>
                                                                    </label>
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
                        @error('test_cases')
                            <div class="text-danger small mt-3">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">{{ __('Next') }}</button>
        </div>
    </form>
@endsection
