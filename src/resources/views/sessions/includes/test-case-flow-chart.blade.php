<div class="card mb-0">
    <div class="card-header">
        <h2 class="card-title">
            <b>{{ __('Flow diagram') }}</b>
        </h2>
    </div>
    <div class="card-body bg-light">
        <flow-chart>
            sequenceDiagram;
            @foreach ($testCase->testSteps as $testStep)
                {{ $testStep->source->name }}->>{{ $testStep->target->name }}: {{ $testStep->name }};
            @endforeach
        </flow-chart>
    </div>
</div>
