<flow-chart>
    graph LR;
        @foreach($scenario->platforms as $platform)
            {{ $platform->id }}({{$platform->name}})@if($platform->sut):::is-active @endif;
            @foreach ($platform->connections as $connection)
                {{ $platform->id }} --> {{ $connection->id }}
            @endforeach
        @endforeach

{{--        p -.-> sp --> mmo-1 --> mojaloop --> mmo-2--}}
{{--        mmo-2 --> mojaloop --> mmo-1 --> sp -.-> p--}}

        classDef node fill:#fff,stroke:#fff,color:#242529
        classDef clickable fill:#fff,stroke:#fff,color:#242529
</flow-chart>
