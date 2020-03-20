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
            }),
        );
    },
};
</script>

<style></style>
