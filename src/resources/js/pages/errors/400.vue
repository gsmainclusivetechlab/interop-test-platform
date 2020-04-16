<template>
    <layout
        :code="400"
        :message="
            exception.message
                ? exception.message
                : 'We are sorry but your browser sent a request that this server could not understand.'
        "
    ></layout>
</template>

<script>
import Layout from '@/layouts/errors';

export default {
    metaInfo: {
        title: '400 Bad Request',
    },
    components: {
        Layout,
    },
    props: {
        exception: {
            type: Object,
            required: true,
        },
    },
};
</script>
