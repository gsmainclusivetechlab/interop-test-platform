<template>
    <layout :scenario="scenario">

    </layout>
</template>

<script>
    import Layout from '@/layouts/admin/scenario.vue';

    export default {
        metaInfo() {
            return {
                title: this.scenario.data.name,
            }
        },
        components: {
            Layout,
        },
        props: {
            scenario: {
                type: Object,
                required: true,
            },
        }
    };
</script>
