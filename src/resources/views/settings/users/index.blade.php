@extends('layouts.app')

@section('title', __('Users'))

@section('content')
    <div class="page-header">
        <h1 class="page-title">@yield('title')</h1>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover table-outline table-vcenter text-nowrap card-table">
                        <thead>
                            <tr>
                                <th class="text-muted">#</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Company') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="text-muted">{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->company }}</td>
                                <td>{{ $user->role }}</td>
                                <td class="text-center">
                                    <div class="item-action dropdown">
                                        <a href="#" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item">Action </a>
                                            <a href="#" class="dropdown-item">Another action </a>
                                            <a href="#" class="dropdown-item">Something else here</a>
                                        </div>
                                    </div>
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
            </div>
            @if ($users->hasPages())
                <div class="row">
                    <div class="col-md-6">
                        @include('includes.summary', ['paginator' => $users])
                    </div>
                    <div class="col-md-6">
                        <div class="justify-content-end d-flex">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
