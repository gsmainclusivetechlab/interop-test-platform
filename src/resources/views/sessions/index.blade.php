@extends('layouts.app')

@section('title', __('Sessions'))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
    <div class="card">
        <div class="card-header">
            @include('components.grid.search')
            <div class="card-options">
                <div class="btn-group">
                    <a href="{{ route('sessions.index') }}" class="btn btn-outline-primary @if (request()->routeIs('sessions.index') && !request()->route()->hasParameter('trashed')) active @endif">
                        {{ __('Active') }}
                    </a>
                    <a href="{{ route('sessions.index', 'trashed') }}" class="btn btn-outline-primary @if (request()->routeIs('sessions.index') && request()->route()->hasParameter('trashed')) active @endif">
                        {{ __('Deactivated') }}
                    </a>
                </div>
            </div>
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
                            @if($session->trashed())
                                {{ $session->name }}
                            @else
                                <a href="{{ route('sessions.show', $session) }}">{{ $session->name }}</a>
                            @endif
                        </td>
                        <td>
                            {{ $session->cases->unique('suite_id')->count() }}
                        </td>
                        <td>
                            {{ $session->cases->count() }}
                        </td>
                        <td>
                            @include('sessions.includes.runs-progress', $session)
                        </td>
                        <td>
                            @if($session->lastRun)
                                {{ $session->lastRun->completed_at->diffForHumans() }}
                            @endif
                        </td>
                        <td class="text-center">
                            @canany(['delete', 'restore'], $session)
                                @component('components.grid.actions')
                                    @if ($session->trashed())
                                        @can('restore', $session)
                                            @include('components.grid.actions.form', [
                                                'method' => 'POST',
                                                'route' => route('sessions.restore', $session),
                                                'label' => __('Activate'),
                                                'confirmTitle' => __('Confirm activate'),
                                                'confirmText' => __('Are you sure you want to activate :name?', ['name' => $session->name]),
                                            ])
                                        @endcan
                                    @else
                                        @can('delete', $session)
                                            @include('components.grid.actions.form', [
                                                'method' => 'DELETE',
                                                'route' => route('sessions.destroy', $session),
                                                'label' => __('Deactivate'),
                                                'confirmTitle' => __('Confirm deactivate'),
                                                'confirmText' => __('Are you sure you want to deactivate :name?', ['name' => $session->name]),
                                            ])
                                        @endcan
                                    @endif

                                    @can('delete', $session)
                                        @include('components.grid.actions.form', [
                                            'method' => 'DELETE',
                                            'route' => route('sessions.force_destroy', $session),
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
