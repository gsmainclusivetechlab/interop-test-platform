<template>
    <div class="mermaid">
        <slot></slot>
    </div>
</template>

<script>
    import mermaid from 'mermaid';
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

    export default {
        props: {
            options: {
                type: Object
            }
        },
        mounted: function () {
            mermaid.init(this.$props, this.$el);
        }
    }
</script>
