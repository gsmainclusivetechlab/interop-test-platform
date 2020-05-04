<template>
    <layout :scenario="scenario">

    </layout>
</template>

<script>
    import Layout from '@/layouts/admin/scenario';

    export default {
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
