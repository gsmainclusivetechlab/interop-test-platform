<div class="row border-bottom">
    <div class="col">
        <div class="page-header m-0 pb-5">
            <h1 class="page-title">
                <b>@yield('title')</b>
            </h1>
            <span class="badge badge-success ml-2 p-1">{{ __('Active') }}</span>
            <div class="ml-4 pt-1">
                {{ __('Execution') }}:
                <i class="fe fe-briefcase"></i>
                <small>{{ $session->suites->count() }}</small>
                <i class="fe fe-file-text"></i>
                <small>{{ $session->cases->count() }}</small>
            </div>
            <div class="col-2">
                <b-progress class="h-3 rounded-0"></b-progress>
            </div>
            <a href="#" class="btn btn-outline-primary ml-4">{{ __('Deactivate') }}</a>
        </div>
    </div>
</div>
