@extends('layouts.app')

@section('title', $session->name)

@section('content')
    @include('sessions.includes.header', ['session' => $session])
    <div class="row align-items-start">
        @include('sessions.cases.includes.sidebar', ['case' => $case])
        <div class="col-9 mt-3">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <b>{{ __('Latest test runs of :name', ['name' => $case->name]) }}</b>
                            </h2>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover card-table">
                                <thead class="thead-light">
                                <tr>
                                    <th class="text-nowrap w-auto">{{ __('Run ID') }}</th>
                                    <th class="text-nowrap w-auto">{{ __('Status') }}</th>
                                    <th class="text-nowrap w-auto">{{ __('Date') }}</th>
                                    <th class="text-nowrap w-auto">{{ __('Duration') }}</th>
                                    <th class="text-nowrap w-1"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($runs as $run)
                                    <tr>
                                        <td>
                                            <a href="{{ route('sessions.cases.runs.show', [$run->session, $run->case, $run]) }}">
                                                {{ $run->uuid }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="status-icon bg-{{ $run->status_type }}"></span>
                                            {{ $run->status_label }}
                                        </td>
                                        <td>
                                            {{ $run->completed_at }}
                                        </td>
                                        <td>
                                            {{ \Carbon\CarbonInterval::microseconds($run->duration)->forHumans() }}
                                        </td>
                                        <td></td>
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
                            @include('components.grid.pagination', ['paginator' => $runs])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
