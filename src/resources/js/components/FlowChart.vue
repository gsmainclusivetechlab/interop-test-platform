<template>
    <div class="mermaid d-flex justify-content-center">
        <div class="code">
            <slot></slot>
        </div>
    </div>
</template>

<script>
const config = {
    startOnLoad: false,
    fontFamily: 'gotham',
    flowchart: {
        curve: 'cardinal',
    },
    sequence: {
        diagramMarginX: 30,
        width: 185,
        height: 60,
    },
    theme: null,
};

export default {
    mounted() {
        import(
            /* webpackChunkName: "mermaid" */ 'mermaid/dist/mermaid.js'
        ).then(({ default: mermaid }) => {
            mermaid.mermaidAPI.initialize(config);

            const elements = document.querySelectorAll('.mermaid .code');

            elements.forEach((el, i) => {
                const graphDefinition = el.innerText;
                const parentElement = el.parentElement;

                el.id = `mermaid-${i}`;

                const graph = mermaid.mermaidAPI.render(
                    el.getAttribute('id'),
                    graphDefinition,
                    (svgGraph) => {
                        parentElement.innerHTML = svgGraph;
                        parentElement.dataset.processed = true;
                    },
                );
            });
        });
    },
};
</script>
