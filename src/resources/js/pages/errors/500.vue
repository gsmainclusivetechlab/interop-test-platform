<template>
    <layout
        :code="500"
        :message="
            exception.message
                ? exception.message
                : 'We are sorry but your request contains bad syntax and cannot be fulfilled.'
        "
    ></layout>
</template>

<script>
import Layout from '@/layouts/errors.vue';

export default {
    metaInfo: {
        title: '500 Server Error'
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
