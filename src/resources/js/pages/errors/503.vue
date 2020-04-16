<template>
    <layout
        :code="503"
        :message="
            exception.message
                ? exception.message
                : 'We are sorry but the service is temporarily unavailable.'
        "
    ></layout>
</template>

<script>
import Layout from '@/layouts/errors';

export default {
    metaInfo: {
        title: '503 Service Unavailable'
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
