<div class="card mb-0 bg-light">
    <div class="card-header">
        <h2 class="card-title">
            <b>{{ $testCase->name }}</b>
        </h2>
    </div>
    <div class="card-body p-0">
        @if($request = $testCase->data_example)
            <div class="d-flex">
                <div class="w-25 px-4 py-2 border">
                    <strong>{{ __('Url') }}</strong>
                </div>
                <div class="w-75 px-4 py-2 border">
                    {{ $request->url() }}
                </div>
            </div>
            <div class="d-flex">
                <div class="w-25 px-4 py-2 border">
                    <strong>{{ __('Method') }}</strong>
                </div>
                <div class="w-75 px-4 py-2 border">
                    {{ $request->method() }}
                </div>
            </div>
            <div class="d-flex">
                <div class="w-25 px-4 py-2 border">
                    <strong>{{ __('Headers') }}</strong>
                </div>
                <div class="w-75 px-4 py-2 border">
                    <div class="mb-0 p-0 bg-transparent">
                        <pre class="mb-0 p-0"><code v-pre>@json($request->headers(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)</code></pre>
                    </div>
                </div>
            </div>
            <div class="d-flex">
                <div class="w-25 px-4 py-2 border">
                    <strong>{{ __('Body') }}</strong>
                </div>
                <div class="w-75 px-4 py-2 border">
                    <div class="mb-0 p-0 bg-transparent">
                        <pre class="mb-0 p-0"><code v-pre>@json($request->json(), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES)</code></pre>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
