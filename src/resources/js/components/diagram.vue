<template>
    <div class="diagram-wrapper">
        <div
            class="text-center my-2"
            v-if="showCopyLink"
        >
            <a
                :href="editorUrl"
                target="_blank"
                rel="noreferrer"
            >
                Copy diagram to live editor
            </a>
        </div>

        <div class="mermaid-diagram text-center" ref="diagram">
            <slot />
        </div>
    </div>
</template>

<script>
import { Base64 } from 'js-base64';

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
        showCopyLink: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            diagramData: {
                code: null,
                mermaid: config
            },
            baseUrl: 'https://mermaid-js.github.io/mermaid-live-editor/#/edit/'
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

            this.diagramData.code = graphEl.innerText;

            mermaid.initialize(config);
            mermaid.init(
                {},
                graphEl,
                () => (this.$el.dataset.processed = true)
            );
        }
    },
    computed: {
        editorUrl() {
            const encodedDiagramData = Base64.encodeURI(
                JSON.stringify(this.diagramData)
            );

            return `${this.baseUrl}${encodedDiagramData}`;
        }
    }
};
</script>
