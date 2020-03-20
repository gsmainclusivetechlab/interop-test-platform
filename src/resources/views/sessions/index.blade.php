@extends('layouts.default')

@section('title', __('Sessions'))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
    <div class="card">
        <div class="card-header">
            @include('components.grid.search')
        </div>
        <div class="table-responsive mb-0">
            <table class="table table-striped table-hover card-table">
                <thead class="thead-light">
                    <tr>
                        <th class="text-nowrap w-25">{{ __('Name') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Use Cases') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Test Cases') }}</th>
                        <th class="text-nowrap w-25">{{ __('Status') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Last Run') }}</th>
                        <th class="text-nowrap w-1"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($sessions as $session)
                    <tr>
                        <td>
                            <a href="{{ route('sessions.show', $session) }}">{{ $session->name }}</a>
                        </td>
                        <td>
                            {{ $session->testCases->unique('use_case_id')->count() }}
                        </td>
                        <td>
                            {{ $session->testCases->count() }}
                        </td>
                        <td>
                            @include('sessions.includes.runs-progress', $session)
                        </td>
                        <td>
                            @if($session->lastTestRun)
                                {{ $session->lastTestRun->created_at->diffForHumans() }}
                            @endif
                        </td>
                        <td class="text-center">
                            @canany(['delete'], $session)
                                @component('components.grid.actions')
                                    @can('delete', $session)
                                        @include('components.grid.actions.form', [
                                            'method' => 'DELETE',
                                            'route' => route('sessions.destroy', $session),
                                            'label' => __('Delete'),
                                            'confirmTitle' => __('Confirm delete'),
                                            'confirmText' => __('Are you sure you want to delete :name?', ['name' => $session->name]),
                                        ])
                                    @endcan
                                @endcomponent
                            @endcanany
                        </td>
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
            @include('components.grid.pagination', ['paginator' => $sessions])
        </div>
    </div>
@endsection
