<flow-chart>
    graph LR;
        @foreach($scenario->components as $component)
            {{ $component->id }}({{$component->name}})@if($component->name == 'Service Provider'):::is-active @endif;
            @foreach ($component->paths as $connection)
                {{ $component->id }} @if($connection->pivot->simulated) --> @else -.-> @endif {{ $connection->id }};
            @endforeach
        @endforeach
</flow-chart>
