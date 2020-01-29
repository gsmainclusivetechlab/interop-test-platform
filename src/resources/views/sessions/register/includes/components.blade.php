{{--@component('components.diagram')--}}
{{--    graph LR;--}}

{{--    p(Payer);--}}
{{--    sp(Service Provider)@if (isset($component) && $component == 'sp'):::is-active @endif;--}}
{{--    mmo-1(Mobile Money Operator 1);--}}
{{--    mojaloop(Mojaloop);--}}
{{--    mmo-2(Mobily Money Operator 2);--}}

{{--    p -.-> sp --> mmo-1 --> mojaloop --> mmo-2--}}
{{--    mmo-2 --> mojaloop --> mmo-1 --> sp -.-> p--}}

{{--    classDef node fill:#fff,stroke:#fff,color:#242529--}}
{{--    classDef clickable fill:#fff,stroke:#fff,color:#242529--}}

{{--    linkStyle default stroke:#dededf,fill:none--}}
{{--    linkStyle 1,6 stroke-width:2px,fill:none,stroke:#99cccc--}}
{{--@endcomponent--}}
<diagram-component>
    graph LR;

    p(Payer);
    sp(Service Provider)@if (isset($component) && $component == 'sp'):::is-active @endif;
    mmo-1(Mobile Money Operator 1);
    mojaloop(Mojaloop);
    mmo-2(Mobily Money Operator 2);

    p -.-> sp --> mmo-1 --> mojaloop --> mmo-2
    mmo-2 --> mojaloop --> mmo-1 --> sp -.-> p

    classDef node fill:#fff,stroke:#fff,color:#242529
    classDef clickable fill:#fff,stroke:#fff,color:#242529

    linkStyle default stroke:#dededf,fill:none
    linkStyle 1,6 stroke-width:2px,fill:none,stroke:#99cccc
</diagram-component>
