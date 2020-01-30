<div class="mermaid d-flex justify-content-center">
    graph LR;

    p(Payer);
    sp(Service Provider)@if (request()->routeIs('sessions.register.configuration.create')):::is-active @endif;
    mmo-1(Mobile Money Operator 1);
    mojaloop(Mojaloop);
    mmo-2(Mobily Money Operator 2);

    p -.-> sp --> mmo-1 --> mojaloop --> mmo-2
    mmo-2 --> mojaloop --> mmo-1 --> sp -.-> p

    classDef node fill:#fff,stroke:#fff,color:#242529
    classDef clickable fill:#fff,stroke:#fff,color:#242529

    linkStyle default stroke:#dededf,fill:none
    linkStyle 1,6 stroke-width:2px,fill:none,stroke:#99cccc
</div>
<script src="https://unpkg.com/mermaid@latest/dist/mermaid.min.js"></script>
<script type="text/javascript">
    mermaid.initialize({
        securityLevel: 'loose',
        flowchart: {
            curve: 'cardinal',
        },
        theme: 'neutral',
        themeCSS: `
            .clickable:hover rect { fill: #de002b !important }
            .clickable:hover .label { color: #fff !important }
            .is-active rect { fill: #de002b !important }
            .is-active .label { color: #fff !important }
        `
    });
</script>
