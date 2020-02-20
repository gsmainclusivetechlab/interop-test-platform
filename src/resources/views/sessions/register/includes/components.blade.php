<flow-chart>
    graph LR;
        @foreach($scenario->platforms as $platform)
            {{ $platform->id }}({{$platform->name}})@if($platform->sut):::is-active @endif;
            @foreach ($platform->connections as $connection)
                {{ $platform->id }} @if($connection->pivot->simulated) --> @else -.-> @endif {{ $connection->id }}
            @endforeach
        @endforeach
        classDef node fill:#fff,stroke:#fff,color:#242529
        classDef clickable fill:#fff,stroke:#fff,color:#242529
</flow-chart>
