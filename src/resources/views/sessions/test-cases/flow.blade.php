<flow-chart>
    sequenceDiagram;
    @foreach ($testCase->testSteps as $testStep)
        {{ $testStep->source->name }}->>{{ $testStep->target->name }}: {{ $testStep->name }};
    @endforeach
</flow-chart>
