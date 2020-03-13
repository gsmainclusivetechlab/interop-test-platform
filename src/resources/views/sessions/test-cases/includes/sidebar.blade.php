<div class="col-3 flex-fill bg-white p-0">
    <div class="card mb-0 p-0 border-0 rounded-0 shadow-none">
        <div class="card-header px-4">
            <h3 class="card-title">
                <a href="{{ route('sessions.show', $session) }}" class="text-decoration-none">
                    <i class="fe fe-chevron-left"></i>
                </a>
                {{ $testCase->name }}
            </h3>
        </div>
        <div class="card-body p-0">
            <ul class="list-unstyled">
                <li class="py-3 px-4 border-bottom">
                    <div class="input-group">
                        <input id="run-url-{{ $testCase->id }}" type="text" class="form-control" readonly value="{{ route('testing.run', ['testPlan' => $testCase->pivot]) }}">
                        <span class="input-group-append">
                            <button class="btn border" type="button" data-clipboard-target="#run-url-{{ $testCase->id }}">
                                <i class="fe fe-copy"></i>
                            </button>
                        </span>
                    </div>
                </li>
                @if ($testCase->description)
                    <li class="py-3 px-4 border-bottom" v-pre>
                        {{ \Illuminate\Mail\Markdown::parse($testCase->description) }}
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
