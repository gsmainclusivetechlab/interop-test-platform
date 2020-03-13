@extends('layouts.app')

@section('title', $session->name)

@section('content')
    @include('sessions.includes.header', $session)
    <div class="row align-items-start">
        @include('sessions.test-cases.includes.sidebar', $testCase)
        <div class="col-9 mt-3">
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
                                    <th class="text-nowrap w-auto">{{ __('Date') }}</th>
                                    <th class="text-nowrap w-1"></th>
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
                                            <span class="status-icon bg-{{ $testRun->status_type }}"></span>
                                            {{ $testRun->status_label }}
                                        </td>
                                        <td>
                                            {{ $testRun->completed_at }}
                                        </td>
                                        <td></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="4">
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
                </div>
            </div>
        </div>
    </div>
@endsection
