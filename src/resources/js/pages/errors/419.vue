<template>
    <layout
        :code="419"
        :message="
            exception.message
                ? exception.message
                : 'We are sorry but your session has expired.'
        "
    ></layout>
</template>

<script>
import Layout from '@/layouts/errors.vue';

export default {
    metaInfo: {
        title: '419 Page Expired'
    },
    components: {
        Layout
    },
    props: {
        exception: {
            type: Object,
            required: true,
        },
    }
};
</script>
