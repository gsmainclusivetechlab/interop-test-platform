<template>
    <layout
        :code="429"
        :message="
            exception.message
                ? exception.message
                : 'We are sorry but too many requests.'
        "
    ></layout>
</template>

<script>
import Layout from '@/layouts/errors.vue';

export default {
    metaInfo: {
        title: '429 Too Many Requests'
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
