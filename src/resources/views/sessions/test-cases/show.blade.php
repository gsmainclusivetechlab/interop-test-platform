@extends('layouts.session', $session)

@section('title', $session->name)

@section('session-header-right')
    <div class="input-group">
        <input id="run-url-{{ $testCase->id }}" type="text" class="form-control" readonly value="{{ route('testing.run', ['session' => $session, 'testCase' => $testCase]) }}">
        <span class="input-group-append">
            <button class="btn btn-white border" type="button" data-clipboard-target="#run-url-{{ $testCase->id }}">
                <i class="fe fe-copy"></i>
            </button>
        </span>
    </div>
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
                    <h2 class="card-title">
                        <b>{{ __('Latest test runs of :name', ['name' => $testCase->name]) }}</b>
                    </h2>
                </div>
                <div class="table-responsive mb-0">
                    <table class="table table-striped table-hover card-table">
                        <thead class="thead-light">
                        <tr>
                            <th class="text-nowrap w-auto">{{ __('Run ID') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Total') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Successful') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Unsuccessful') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($testRuns as $testRun)
                            <tr>
                                <td>
                                    <a href="{{ route('sessions.test_cases.results', ['session' => $testRun->session, 'testCase' => $testRun->testCase, 'testRun' => $testRun]) }}">
                                        {{ $testRun->uuid }}
                                    </a>
                                </td>
                                <td>
                                    {{ $testRun->total }}
                                </td>
                                <td>
                                    {{ $testRun->successful }}
                                </td>
                                <td>
                                    {{ $testRun->unsuccessful }}
                                </td>
                                <td>
                                    {{ $testRun->created_at }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="5">
                                    {{ __('No Results') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    @include('components.grid.pagination', ['paginator' => $testRuns])
                </div>
            </div>
            @include('sessions.includes.test-case-flow-chart', $testCase)
        </div>
    </div>
@endsection
