@extends('layouts.app')

@section('title', $session->name)

@section('content')
    @include('sessions.includes.header', ['session' => $session])
    <div class="row align-items-start">
        <div class="col-3 flex-fill bg-white p-0">
            <div class="card mb-0 p-0 border-0 rounded-0 shadow-none">
                <div class="card-header px-4">
                    <h3 class="card-title">{{ __('Select use cases') }}</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-unstyled">
                        @foreach($suites as $suite)
                            <li>
                                <b class="d-block dropdown-toggle py-2 px-4 border-bottom" v-b-toggle.suite-{{ $suite->id }}>
                                    {{ $suite->name }}
                                </b>
                                @if ($session->positiveCases->where('suite_id', $suite->id)->count())
                                    <b-collapse id="suite-{{ $suite->id }}" visible>
                                        <ul class="list-unstyled">
                                            <li>
                                                <span class="dropdown-toggle d-block px-5 py-2 font-weight-medium border-bottom" v-b-toggle.positive-cases-{{ $suite->id }}>
                                                    {{ __('Happy flow') }}
                                                </span>
                                                <b-collapse id="positive-cases-{{ $suite->id }}" visible>
                                                    <ul class="list-unstyled">
                                                        @foreach($session->positiveCases->where('suite_id', $suite->id) as $case)
                                                            <li class="list-group-item-action d-flex justify-content-between align-items-center px-6 py-2 border-bottom">
                                                                <a href="{{ route('sessions.cases.show', [$session, $case]) }}">{{ $case->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </b-collapse>
                                            </li>
                                        </ul>
                                    </b-collapse>
                                @endif

                                @if ($session->negativeCases->where('suite_id', $suite->id)->count())
                                    <b-collapse id="suite-{{ $suite->id }}" visible>
                                        <ul class="list-unstyled">
                                            <li>
                                                <span class="dropdown-toggle d-block px-5 py-2 font-weight-medium border-bottom" v-b-toggle.negative-cases-{{ $suite->id }}>
                                                    {{ __('Unhappy flow') }}
                                                </span>
                                                <b-collapse id="negative-cases-{{ $suite->id }}" visible>
                                                    <ul class="list-unstyled">
                                                        @foreach($session->negativeCases->where('suite_id', $suite->id) as $case)
                                                            <li class="list-group-item-action d-flex justify-content-between align-items-center px-6 py-2 border-bottom">
                                                                <a href="{{ route('sessions.cases.show', [$session, $case]) }}">{{ $case->name }}</a>
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
                </div>
            </div>
        </div>
        <div class="col-9 mt-3">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">
                                <b>{{ __('Latest test runs') }}</b>
                            </h2>
                        </div>
                        <div class="table-responsive mb-0">
                            <table class="table table-striped table-hover card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-nowrap w-auto">{{ __('Test Case') }}</th>
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
                                                <a href="{{ route('sessions.cases.show', [$run->session, $run->case]) }}">
                                                    {{ $run->case->name }}
                                                </a>
                                            </td>
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
                                                {{ __(':n ms', ['n' => $run->duration]) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="text-center" colspan="6">
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
