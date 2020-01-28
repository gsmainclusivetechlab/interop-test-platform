<h1 class="page-title mb-5">
    <b>{{ __('Account') }}</b>
</h1>
<div>
    <div class="list-group list-group-transparent mb-0">
        <a href="{{ route('account.profile.edit') }}" class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('account.profile.*')) active @endif">
            {{ __('Profile') }}
        </a>
        <a href="{{ route('account.password.edit') }}" class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('account.password.*')) active @endif">
            {{ __('Change password') }}
        </a>
    </div>
</div>
