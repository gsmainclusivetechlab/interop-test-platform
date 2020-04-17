<template>
    <div class="mb-4 text-center">
        diagram
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
            console.log(mermaid);
        }
    }
};
</script>
