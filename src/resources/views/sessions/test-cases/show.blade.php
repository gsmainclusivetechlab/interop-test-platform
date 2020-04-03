@extends('layouts.sessions.test-case', [$session, $testCase])

@section('title', $session->name)

@section('content')
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
                            <a href="{{ route('sessions.test-cases.test-runs.show', ['session' => $session, 'testCase' => $testCase, 'testRun' => $testRun]) }}">
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
@endsection
