<h1 class="page-title mb-5">
    <b>{{ __('Settings') }}</b>
</h1>
<div>
    <div class="list-group list-group-transparent mb-0">
        <a href="{{ route('settings.profile.edit') }}" class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('settings.profile.edit')) active @endif">
            {{ __('Profile') }}
        </a>
        <a href="{{ route('settings.password.edit') }}" class="list-group-item list-group-item-action d-flex align-items-center @if (request()->routeIs('settings.password.edit')) active @endif">
            {{ __('Change password') }}
        </a>
    </div>
</div>
