<template>
    <div class="mermaid d-flex justify-content-center">
        <slot></slot>
    </div>
</template>

<script>
export default {
    mounted() {
        import(
            /* webpackChunkName: "mermaid" */ 'mermaid/dist/mermaid.js'
        ).then(({ default: mermaid }) =>
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
            `,
            }),
        );
    },
};
</script>

<style></style>
