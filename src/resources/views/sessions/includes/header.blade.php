<div class="row border-bottom">
    <div class="col">
        <div class="page-header m-0 pb-5">
            <h1 class="page-title">
                <b>{{ $session->name }}</b>
            </h1>
            <div class="ml-4 pt-1">
                {{ __('Execution') }}:
                <i class="fe fe-briefcase"></i>
                <small>{{ $session->suites_count }}</small>
                <i class="fe fe-file-text"></i>
                <small>{{ $session->cases_count }}</small>
            </div>
            <div class="col-2">
                @include('sessions.includes.runs-progress', $session)
            </div>
        </div>
    </div>
</div>
