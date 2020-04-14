<template>
    <layout
        :code="404"
        :message="
            exception.message
                ? exception.message
                : 'We are sorry but the page you were looking for does not exist.'
        "
    ></layout>
</template>

<script>
import Layout from '@/layouts/errors.vue';

export default {
    metaInfo: {
        title: '404 Page Not Found'
    },
    components: {
        Layout
    },
    props: {
        exception: Object
    }
};
</script>
