<template>
    <div class="mermaid-diagram text-center">
        <slot />
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
    metaInfo() {
        return {
            script: [
                {
                    src: 'https://unpkg.com/mermaid@8.5.0/dist/mermaid.min.js',
                    defer: true,
                    callback: () => {
                        this.initDiagram();
                    },
                },
            ],
        };
    },
    mounted() {
        if (window.mermaid) {
            this.initDiagram();
        }
    },
    methods: {
        initDiagram() {
            const graphEl = this.$el;

            mermaid.initialize(config);
            mermaid.init({}, graphEl);
        },
    },
};
</script>
