<div class="row border-bottom">
    <div class="col">
        <div class="page-header m-0 pb-5">
            <h1 class="page-title">
                <b>{{ $session->name }}</b>
            </h1>
            <span class="badge badge-success ml-2 p-1">
                {{ __('Active') }}
            </span>
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
            @if ($session->trashed())
                <form action="{{ route('sessions.restore', $session) }}" method="POST">
                    @csrf
                    @method('POST')
                    <confirm-button class="btn btn-outline-primary ml-4" type="submit" title="{{ __('Confirm activate') }}" text="{{ __('Are you sure you want to activate :session?', ['session' => $session->name]) }}">
                        {{ __('Activate') }}
                    </confirm-button>
                </form>
            @else
                <form action="{{ route('sessions.destroy', $session) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <confirm-button class="btn btn-outline-primary ml-4" type="submit" title="{{ __('Confirm deactivate') }}" text="{{ __('Are you sure you want to deactivate :session?', ['session' => $session->name]) }}">
                        {{ __('Deactivate') }}
                    </confirm-button>
                </form>
            @endif
        </div>
    </div>
</div>
