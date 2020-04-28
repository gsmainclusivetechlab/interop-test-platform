<template>
    <div class="diagram-wrapper">
        <div class="mermaid-diagram text-center" ref="diagram">
            <slot />
        </div>

        <clipboard-copy-btn
            v-if="showCopyBtn"
            :data="diagramData"
            class="px-2"
        ></clipboard-copy-btn>

        <div class="text-center text-decoration-underline mt-3 mb-2" v-if="showCopyBtn">
            <a
                href="https://mermaid-js.github.io/mermaid-live-editor/"
                target="_blank"
                rel="noreferrer"
                class="text-gray"
            >
                Link to live editor
            </a>
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
        showCopyBtn: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            diagramData: null
        };
    },
    mounted() {
        if (window.mermaid) {
            this.initDiagram();
        }
    },
    methods: {
        initDiagram() {
            const graphEl = this.$refs.diagram;

            this.diagramData = graphEl.innerText;

            mermaid.initialize(config);
            mermaid.init(
                {},
                graphEl,
                () => (this.$el.dataset.processed = true)
            );
        }
    }
};
</script>
