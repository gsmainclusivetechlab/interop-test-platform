@extends('layouts.app')

@section('title', __('Users'))

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">@yield('title')</h3>
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
                    <table class="table table-striped card-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="w-25">{{ __('Name') }}</th>
                                <th class="w-25">{{ __('Email') }}</th>
                                <th class="w-25">{{ __('Company') }}</th>
                                <th class="w-auto">{{ __('Role') }}</th>
                                <th class="w-auto">{{ __('Verified') }}</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="w-25 text-break">
                                    <a href="#">{{ $user->name }}</a>
                                </td>
                                <td class="w-25 text-break">
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                </td>
                                <td class="w-25 text-break">{{ $user->company }}</td>
                                <td class="w-auto text-break">{{ $user->role_name }}</td>
                                <td class="w-auto text-break">
                                    @if ($user->email_verified_at)
                                        {{ $user->email_verified_at->format('M d, Y') }}
                                    @endif
                                </td>
                                <td class="w-1 text-center text-break">
                                    @canany(['promoteAdmin', 'relegateAdmin', 'delete', 'restore', 'forceDelete'], $user)
                                        <div class="item-action dropdown">
                                            <a href="#" data-toggle="dropdown" class="icon" data-boundary="viewport"><i class="fe fe-more-vertical"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @can('promoteAdmin', $user)
                                                    <form action="{{ route('settings.users.promote-admin', $user) }}" method="POST">
                                                        @csrf
                                                        <button class="dropdown-item" type="submit">{{ __('Promote Admin') }}</button>
                                                    </form>
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
                                            </div>
                                        </div>
                                    @endcanany
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
                <div class="card-footer">
                    @include('components.pagination', ['paginator' => $users])
                </div>
            </div>
        </div>
    </div>
@endsection
