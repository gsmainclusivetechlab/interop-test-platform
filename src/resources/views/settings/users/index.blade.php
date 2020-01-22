@extends('layouts.app')

@section('title', __('Users'))

@section('content')
    <div class="page-header">
        <h1 class="page-title">@yield('title')</h1>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="fe fe-search"></i>
                        </span>
                        <input type="text" class="form-control w-10" placeholder="{{ __('Search') }}">
                    </div>
                    <div class="card-options">
                        <div class="btn-group">
                            <a href="{{ route('settings.users.index') }}" class="btn btn-outline-primary @if (request()->routeIs('settings.users.index')) active @endif">
                                {{ __('Active') }}
                            </a>
                            <a href="{{ route('settings.users.trashed') }}" class="btn btn-outline-primary @if (request()->routeIs('settings.users.trashed')) active @endif">
                                {{ __('Deleted') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Company') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Verified') }}</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    {{ $user->name }}
                                </td>
                                <td>
                                    {{ $user->email }}
                                </td>
                                <td>{{ $user->company }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    @if ($user->email_verified_at)
                                        {{ $user->email_verified_at->format('M d, Y') }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @canany(['delete', 'restore', 'forceDelete'], $user)
                                        <div class="item-action dropdown">
                                            <a href="#" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                @if ($user->trashed())
                                                    @can('restore', $user)
                                                        <form action="{{ route('settings.users.restore', $user) }}" method="POST">
                                                            @csrf
                                                            @method('POST')
                                                            <button class="dropdown-item" type="submit">{{ __('Restore') }}</button>
                                                        </form>
                                                    @endcan

                                                    @can('forceDelete', $user)
                                                        <form action="{{ route('settings.users.force-destroy', $user) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item" type="submit">{{ __('Force delete') }}</button>
                                                        </form>
                                                    @endcan
                                                @else
                                                    @can('delete', $user)
                                                        <form action="{{ route('settings.users.destroy', $user) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item" type="submit">{{ __('Delete') }}</button>
                                                        </form>
                                                    @endcan
                                                @endif
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
            </div>
            @include('includes.pagination', ['paginator' => $users])
        </div>
    </div>
@endsection
