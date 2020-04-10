<template>
    <svg width="24" height="24">
        <use :xlink:href="href" />
    </svg>
</template>

<script>
export default {
    props: {
        name: {
            type: String,
            required: true,
        },
        baseUrl: {
            type: String,
            default: '/assets/images/tabler-icons.svg#tabler-',
        },
    },
    computed: {
        href() {
            return `${this.$props.baseUrl}${this.$props.name}`;
        },
    },
};
</script>
