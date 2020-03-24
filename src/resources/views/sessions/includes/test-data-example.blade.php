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
                    <div class="mb-0 p-0 bg-transparent json-tree">
                        <code v-pre class="json-tree-code">@json($request->headers(), JSON_PRETTY_PRINT)</code>
                    </div>
                </div>
            </div>
            <div class="d-flex">
                <div class="w-25 px-4 py-2 border">
                    <strong>{{ __('Body') }}</strong>
                </div>
                <div class="w-75 px-4 py-2 border">
                    <div class="mb-0 p-0 bg-transparent json-tree">
                        <code v-pre class="json-tree-code">@json($request->json(), JSON_PRETTY_PRINT)</code>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
