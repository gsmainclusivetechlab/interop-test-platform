@extends('layouts.session', $session)

@section('title', $session->name)

@section('session-header-right')
    <div class="d-flex">
        <div class="flex-shrink-0 btn-group btn-group-sm mr-2" role="group" aria-label="Test case data controls">
            <button type="button" class="btn btn-secondary border" data-fancybox data-src="#flow-diagram">
                {{ __('Use case flow') }}
            </button>
{{--            <button type="button" class="btn btn-secondary border" data-fancybox data-src="#test-data">--}}
{{--                {{ __('Test Data') }}--}}
{{--            </button>--}}
        </div>
        <div class="input-group">
            <input id="run-url-{{ $testCase->id }}" type="text" class="form-control" readonly value="{{ route('testing.run', ['session' => $session, 'testCase' => $testCase]) }}">
            <span class="input-group-append">
                <button class="btn btn-white border" type="button" data-clipboard-target="#run-url-{{ $testCase->id }}">
                    <i class="fe fe-copy"></i>
                </button>
            </span>
        </div>
    </div>

    <div id="flow-diagram" class="col-8 p-0 rounded" style="display: none">
        @include('sessions.includes.test-case-flow-chart', $testCase)
    </div>

{{--    <div id="test-data" class="col-6 p-0 rounded" style="display: none">--}}
{{--        <div class="card mb-0">--}}
{{--            <div class="card-header">--}}
{{--                <h2 class="card-title">--}}
{{--                    <b>{{ $testCase->name }}</b>--}}
{{--                </h2>--}}
{{--            </div>--}}
{{--            <div class="card-body bg-light p-0">--}}
{{--                <div class="py-3 px-4">--}}
{{--                    <p><strong>Precondition</strong></p>--}}
{{--                    <p class="mb-0">--}}
{{--                        <b>Headers</b>--}}
{{--                        <pre class="mb-0 p-0">--}}
{{--<code>{--}}
{{--    "Accept": "application/json",--}}
{{--    "Content-Type": "application/json",--}}
{{--    "X-Callback-URL": "http://example.com/example",--}}
{{--    "X-Date": "2020-02-20T10:28:44.466Z"--}}
{{--}</code>--}}
{{--                        </pre>--}}
{{--                    </p>--}}
{{--                    <p class="mb-0">--}}
{{--                        <b>Data body</b>--}}
{{--                        <pre class="mb-0 p-0">--}}
{{--<code>{--}}
{{--    "amount":"100.00",--}}
{{--    "currency":"USD",--}}
{{--    "type":"merchantpay",--}}
{{--    "debitParty": [{"key":"msisdn", "value":"+33555123456"}],--}}
{{--    "creditParty": [{"key":"msisdn", "value":"+33555789123"}]--}}
{{--}</code>--}}
{{--                        </pre>--}}
{{--                    </p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
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
                            <th class="text-nowrap w-auto">{{ __('Status') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Duration') }}</th>
                            <th class="text-nowrap w-auto">{{ __('Date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($testRuns as $testRun)
                            <tr>
                                <td>
                                    <a href="{{ route('sessions.test_cases.results', ['session' => $session, 'testCase' => $testCase, 'testRun' => $testRun]) }}">
                                        {{ $testRun->uuid }}
                                    </a>
                                </td>
                                <td>
                                    @if($testRun->completed_at)
                                        @if ($testRun->successful)
                                            <span class="status-icon bg-success"></span>
                                            {{ __('Pass') }}
                                        @else
                                            <span class="status-icon bg-danger"></span>
                                            {{ __('Fail') }}
                                        @endif
                                    @else
                                        <span class="status-icon bg-secondary"></span>
                                        {{ __('Incomplete') }}
                                    @endif
                                </td>
                                <td>
                                    {{ __(':n ms', ['n' => $testRun->duration]) }}
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
                @if ($testRuns->count())
                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                {{ __('Showing :from to :to of :total entries', [
                                    'from' => (($testRuns->currentPage() - 1) * $testRuns->perPage()) + 1,
                                    'to' => (($testRuns->currentPage() - 1) * $testRuns->perPage()) + $testRuns->count(),
                                    'total' => $testRuns->total(),
                                ]) }}
                            </div>
                            <div class="col-md-6">
                                <div class="justify-content-end d-flex">
                                    {{ $testRuns->appends(request()->all())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
