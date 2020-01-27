@extends('layouts.app')

@section('title', __('Users'))

@section('content')
    <h1 class="page-title mb-5">
        @yield('title')
    </h1>
    <div class="card">
        <div class="card-header">
            @include('components.grids.search')
            <div class="card-options">
                <div class="btn-group">
                    <a href="{{ route('settings.users.index') }}" class="btn btn-outline-primary @if (request()->routeIs('settings.users.index')) active @endif">
                        {{ __('Active') }}
                    </a>
                    <a href="{{ route('settings.users.trashed') }}" class="btn btn-outline-primary @if (request()->routeIs('settings.users.trashed')) active @endif">
                        {{ __('Blocked') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
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
                            <a href="#">{{ $user->name }}</a>
                        </td>
                        <td class="text-break">
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </td>
                        <td class="text-break">{{ $user->company }}</td>
                        <td class="text-break">{{ $user->role_name }}</td>
                        <td class="text-break">
                            @if ($user->email_verified_at)
                                {{ $user->email_verified_at->format('M d, Y') }}
                            @endif
                        </td>
                        <td class="text-center text-break">
                            @canany(['promoteAdmin', 'relegateAdmin', 'delete', 'restore', 'forceDelete'], $user)
                                @component('components.grids.actions')
                                        @can('promoteAdmin', $user)
                                            @include('components.grids.actions.form', [
                                                'method' => 'POST',
                                                'route' => route('settings.users.promote-admin', $user),
                                                'label' => __('Promote Admin'),
                                                'confirm' => __('Are you sure you want to promote this user to admin?')
                                            ])
                                        @endcan

                                        @can('relegateAdmin', $user)
                                            <form action="{{ route('settings.users.relegate-admin', $user) }}" method="POST">
                                                @csrf
                                                <button class="dropdown-item" type="submit">{{ __('Relegate Admin') }}</button>
                                            </form>
                                        @endcan

                                        @if ($user->trashed())
                                            @can('restore', $user)
                                                <form action="{{ route('settings.users.restore', $user) }}" method="POST">
                                                    @csrf
                                                    <button class="dropdown-item" type="submit">{{ __('Unblock') }}</button>
                                                </form>
                                            @endcan
                                        @else
                                            @can('delete', $user)
                                                <form action="{{ route('settings.users.destroy', $user) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="dropdown-item" type="submit">{{ __('Block') }}</button>
                                                </form>
                                            @endcan
                                        @endif

                                        @can('forceDelete', $user)
                                            <form action="{{ route('settings.users.force-destroy', $user) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item" type="submit">{{ __('Delete') }}</button>
                                            </form>
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
            @include('components.grids.pagination', ['paginator' => $users])
        </div>
    </div>
@endsection
