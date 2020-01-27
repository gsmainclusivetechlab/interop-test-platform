@extends('layouts.app')

@section('sidebar')
    <h3 class="page-title mb-5">{{ __('Account') }}</h3>
    <div>
        <div class="list-group list-group-transparent mb-0">
            <a href="{{ route('account.profile.edit') }}" class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('account.profile.edit')) active @endif">
                {{ __('Profile') }}
            </a>
            <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                {{ __('Change password') }}
            </a>
        </div>
    </div>
@endsection
