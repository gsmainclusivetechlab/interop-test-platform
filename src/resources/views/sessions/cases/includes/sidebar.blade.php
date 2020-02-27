<div class="col-3 flex-fill bg-white p-0">
    <div class="card mb-0 p-0 border-0 rounded-0 shadow-none">
        <div class="card-header px-4">
            <h3 class="card-title">
                <a href="{{ route('sessions.show', $session) }}" class="text-decoration-none">
                    <i class="fe fe-chevron-left"></i>
                </a>
                {{ $case->name }}
            </h3>
        </div>
        <div class="card-body p-0">
            <ul class="list-unstyled">
                <li class="py-3 px-4 border-bottom">
                    <div class="input-group">
                        <input id="url" type="text" class="form-control" readonly value="{{ route('testing.run', [$case->pivot]) }}">
                        <span class="input-group-append">
                            <button class="btn border" type="button" data-clipboard-target="#url">
                                <i class="fe fe-copy"></i>
                            </button>
                        </span>
                    </div>
                </li>
                @if ($case->description)
                    <li class="py-3 px-4 border-bottom">
                        <strong class="d-block mb-1">{{ __('Description') }}</strong>
                        <p class="mb-0">
                            {{ $case->description }}
                        </p>
                    </li>
                @endif

                @if ($case->preconditions)
                    <li class="py-3 px-4 border-bottom">
                        <strong class="d-block mb-1">{{ __('Preconditions') }}</strong>
                        <p class="mb-0">
                            {{ $case->preconditions }}
                        </p>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
