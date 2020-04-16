<template>
    <layout :testCase="testCase">

    </layout>
</template>

<script>
    import Layout from '@/layouts/admin/test-case.vue';

    export default {
        components: {
            Layout,
        },
        props: {
            testCase: {
                type: Object,
                required: true,
            },
        }
    };
</script>
