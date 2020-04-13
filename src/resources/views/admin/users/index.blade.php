@extends('layouts.default')

@section('title', __('Users'))

@section('content')
    <h1 class="page-title mb-5">
        <b>@yield('title')</b>
    </h1>
    <div class="card">
        <div class="card-header">
            <form action="{{ url()->current() }}" method="GET" class="input-icon">
                <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="{{ __('Search') }}...">
                <span class="input-icon-addon">
                    <i class="fe fe-search"></i>
                </span>
            </form>
            <div class="card-options">
                <div class="btn-group">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary @if (request()->routeIs('admin.users.index') && !request()->route()->hasParameter('trashed')) active @endif">
                        {{ __('Active') }}
                    </a>
                    <a href="{{ route('admin.users.index', ['trashed']) }}" class="btn btn-outline-primary @if (request()->routeIs('admin.users.index') && request()->route()->hasParameter('trashed')) active @endif">
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
                        <td class="text-break">{{ $user->role_name }}</td>
                        <td class="text-break">
                            @if ($user->email_verified_at)
                                {{ $user->email_verified_at }}
                            @endif
                        </td>
                        <td class="text-center text-break">
                            @canany(['promoteAdmin', 'relegateAdmin', 'delete', 'restore'], $user)
                                <b-dropdown class="item-action" no-caret right toggle-class="icon text-decoration-none py-0" variant="link" boundary="window">
                                    <template v-slot:button-content>
                                        <i class="fe fe-more-vertical"></i>
                                    </template>
                                    @if ($user->trashed())
                                        @can('restore', $user)
                                            <b-dropdown-form action="{{ route('admin.users.restore', $user) }}" method="POST" form-class="p-0">
                                                @csrf
                                                @method('POST')
                                                <confirm-button class="dropdown-item" type="submit" title="{{ __('Confirm unblock') }}" text="{{ __('Are you sure you want to unblock :name?', ['name' => $user->name]) }}">
                                                    {{ __('Unblock') }}
                                                </confirm-button>
                                            </b-dropdown-form>
                                        @endcan
                                    @else
                                        @can('promoteAdmin', $user)
                                            <b-dropdown-form action="{{ route('admin.users.promote-admin', $user) }}" method="POST" form-class="p-0">
                                                @csrf
                                                @method('POST')
                                                <confirm-button class="dropdown-item" type="submit" title="{{ __('Confirm promote admin') }}" text="{{ __('Are you sure you want to promote :name to admin?', ['name' => $user->name]) }}">
                                                    {{ __('Promote admin') }}
                                                </confirm-button>
                                            </b-dropdown-form>
                                        @endcan

                                        @can('relegateAdmin', $user)
                                            <b-dropdown-form action="{{ route('admin.users.relegate-admin', $user) }}" method="POST" form-class="p-0">
                                                @csrf
                                                @method('POST')
                                                <confirm-button class="dropdown-item" type="submit" title="{{ __('Confirm relegate admin') }}" text="{{ __('Are you sure you want to relegate :name from admin?', ['name' => $user->name]) }}">
                                                    {{ __('Relegate admin') }}
                                                </confirm-button>
                                            </b-dropdown-form>
                                        @endcan

                                        @can('delete', $user)
                                            <b-dropdown-form action="{{ route('admin.users.destroy', $user) }}" method="POST" form-class="p-0">
                                                @csrf
                                                @method('DELETE')
                                                <confirm-button class="dropdown-item" type="submit" title="{{ __('Confirm block') }}" text="{{ __('Are you sure you want to block :name?', ['name' => $user->name]) }}">
                                                    {{ __('Block') }}
                                                </confirm-button>
                                            </b-dropdown-form>
                                        @endcan
                                    @endif

                                    @can('delete', $user)
                                        <b-dropdown-form action="{{ route('admin.users.force-destroy', $user) }}" method="POST" form-class="p-0">
                                            @csrf
                                            @method('DELETE')
                                            <confirm-button class="dropdown-item" type="submit" title="{{ __('Confirm delete') }}" text="{{ __('Are you sure you want to delete :name?', ['name' => $user->name]) }}">
                                                {{ __('Delete') }}
                                            </confirm-button>
                                        </b-dropdown-form>
                                    @endcan
                                </b-dropdown>
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
        @if ($users->count())
            <div class="card-footer">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        {{ __('Showing :from to :to of :total entries', [
                            'from' => (($users->currentPage() - 1) * $users->perPage()) + 1,
                            'to' => (($users->currentPage() - 1) * $users->perPage()) + $users->count(),
                            'total' => $users->total(),
                        ]) }}
                    </div>
                    <div class="col-md-6">
                        <div class="justify-content-end d-flex">
                            {{ $users->appends(request()->all())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
