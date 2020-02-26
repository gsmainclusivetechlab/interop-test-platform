@extends('layouts.app')

@section('title', $session->name)

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
{{--                    <a href="#" class="lead ml-auto text-decoration-none">--}}
{{--                        <i class="fe fe-download"></i>--}}
{{--                    </a>--}}
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
                                            {{ $run->completed_at->format('d M Y, H:m') }}
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
