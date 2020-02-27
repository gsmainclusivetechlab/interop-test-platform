@extends('layouts.app')

@section('title', __('Users'))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
    <div class="card">
        <div class="card-header">
            @include('components.grid.search')
            <div class="card-options">
                <div class="btn-group">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary @if (request()->routeIs('admin.users.index')) active @endif">
                        {{ __('Active') }}
                    </a>
                    <a href="{{ route('admin.users.trash') }}" class="btn btn-outline-primary @if (request()->routeIs('admin.users.trash')) active @endif">
                        {{ __('Blocked') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive mb-0">
            <table class="table table-striped table-hover card-table">
                <thead class="thead-light">
                    <tr>
                        <th class="text-nowrap w-25">{{ __('Name') }}</th>
                        <th class="text-nowrap w-25">{{ __('Email') }}</th>
                        <th class="text-nowrap w-25">{{ __('Company') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Role') }}</th>
                        <th class="text-nowrap w-auto">{{ __('Verified') }}</th>
                        <th class="text-nowrap w-1"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="text-break">
                            @if($user->trashed())
                                {{ $user->name }}
                            @else
                                <a href="#">{{ $user->name }}</a>
                            @endif
                        </td>
                        <td class="text-break">
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </td>
                        <td class="text-break">{{ $user->company }}</td>
                        <td class="text-break">{{ $user->role_label }}</td>
                        <td class="text-break">
                            @if ($user->email_verified_at)
                                {{ $user->email_verified_at }}
                            @endif
                        </td>
                        <td class="text-center text-break">
                            @canany(['promoteAdmin', 'relegateAdmin', 'delete', 'restore'], $user)
                                @component('components.grid.actions')
                                        @if ($user->trashed())
                                            @can('restore', $user)
                                                @include('components.grid.actions.form', [
                                                    'method' => 'POST',
                                                    'route' => route('admin.users.restore', $user),
                                                    'label' => __('Unblock'),
                                                    'confirmTitle' => __('Confirm unblock'),
                                                    'confirmText' => __('Are you sure you want to unblock :name?', ['name' => $user->name]),
                                                ])
                                            @endcan
                                        @else
                                            @can('promoteAdmin', $user)
                                                @include('components.grid.actions.form', [
                                                    'method' => 'POST',
                                                    'route' => route('admin.users.promote_admin', $user),
                                                    'label' => __('Promote admin'),
                                                    'confirmTitle' => __('Confirm promote admin'),
                                                    'confirmText' => __('Are you sure you want to promote :name to admin?', ['name' => $user->name]),
                                                ])
                                            @endcan

                                            @can('relegateAdmin', $user)
                                                @include('components.grid.actions.form', [
                                                    'method' => 'POST',
                                                    'route' => route('admin.users.relegate_admin', $user),
                                                    'label' => __('Relegate admin'),
                                                    'confirmTitle' => __('Confirm relegate admin'),
                                                    'confirmText' => __('Are you sure you want to relegate :name from admin?', ['name' => $user->name]),
                                                ])
                                            @endcan

                                            @can('delete', $user)
                                                @include('components.grid.actions.form', [
                                                    'method' => 'DELETE',
                                                    'route' => route('admin.users.destroy', $user),
                                                    'label' => __('Block'),
                                                    'confirmTitle' => __('Confirm block'),
                                                    'confirmText' => __('Are you sure you want to block :name?', ['name' => $user->name]),
                                                ])
                                            @endcan
                                        @endif

                                        @can('delete', $user)
                                            @include('components.grid.actions.form', [
                                                'method' => 'DELETE',
                                                'route' => route('admin.users.force_destroy', $user),
                                                'label' => __('Delete'),
                                                'confirmTitle' => __('Confirm delete'),
                                                'confirmText' => __('Are you sure you want to delete :name?', ['name' => $user->name]),
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
            @include('components.grid.pagination', ['paginator' => $users])
        </div>
    </div>
@endsection
