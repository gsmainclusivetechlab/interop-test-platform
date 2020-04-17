<template>
    <div class="mermaid-diagram text-center">
        <div id="diagram" ref="diagram"></div>
        <div class="d-flex justify-content-center mt-1">
            <div class="d-inline-flex align-items-center mx-2">
                <span class="ic-arrow-right mr-2"></span>
                <small>Simulated</small>
            </div>
            <div class="d-inline-flex align-items-center mx-2">
                <span class="ic-arrow-right mr-2 border-dashed"></span>
                <small>Not Simulated</small>
            </div>
        </div>
    </div>
</template>

<script>
const config = {
    startOnLoad: false,
    fontFamily: 'gotham',
    flowchart: {
        curve: 'cardinal'
    },
    sequence: {
        diagramMarginX: 30,
        width: 185,
        height: 60
    },
    theme: null
};

export default {
    metaInfo() {
        return {
            script: [
                {
                    src: 'https://unpkg.com/mermaid@8.5.0/dist/mermaid.min.js',
                    defer: true,
                    callback: () => {
                        this.initDiagram();
                    }
                }
            ]
        };
    },
    props: {
        components: {
            type: Array,
            required: true
        }
    },
    methods: {
        initDiagram() {
            const graphEl = this.$refs.diagram;
            const graphParent = graphEl.parentElement;

            mermaid.mermaidAPI.initialize(config);

            mermaid.mermaidAPI.render(
                graphEl.getAttribute('id'),
                this.graphTemplate,
                svgGraph => {
                    const div = document.createElement('div');
                    div.innerHTML = svgGraph;
                    graphParent.prepend(div);
                    graphParent.dataset.processed = true;
                }
            );
        }
    },
    computed: {
        graphTemplate() {
            return this.components.reduce((acc, component) => {
                const componentName = `${component.id}(${component.name});`;

                acc += componentName;

                component.paths.forEach(path => {
                    const pathType =
                        component.simulated && path.simulated ? '-->' : '-.->';
                    acc += `${component.id}${pathType}${path.id};`;
                });

                return acc;
            }, 'graph LR;');
        }
    }
};
</script>
