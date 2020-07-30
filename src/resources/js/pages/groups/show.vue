<template>
    <layout :group="group">
        Test
    </layout>
</template>

<script>
    import Layout from '@/layouts/groups/main';

    export default {
        components: {
            Layout,
        },
        props: {
            group: {
                type: Object,
                required: true,
            },
        },
    };
</script>
