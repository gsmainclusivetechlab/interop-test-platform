<div class="page-header">
    <h1 class="page-title mx-auto">
        <b>{{ __('Create new session') }}</b>
    </h1>
</div>
<div class="row d-flex justify-content-center">
    <div class="col-md-8">
        <div class="steps steps-counter steps-primary">
            <span class="step-item @if (request()->routeIs('sessions.register.selection.create')) active @endif">
                <span class="d-inline-block mt-2">{{ __('Select SUT') }}</span>
            </span>
            <span class="step-item @if (request()->routeIs('sessions.register.configuration.create')) active @endif">
                <span class="d-inline-block mt-2">{{ __('Configure SUT') }}</span>
            </span>
            <span class="step-item @if (request()->routeIs('sessions.register.information.create')) active @endif">
                <span class="d-inline-block mt-2">{{ __('Session info') }}</span>
            </span>
        </div>
    </div>
</div>
