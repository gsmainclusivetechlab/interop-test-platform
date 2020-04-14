<template>
    <layout :code="401" :message="exception.message ? exception.message : 'We are sorry but you are not authorized to access this page.'"></layout>
</template>

<script>
    import Layout from '../../layouts/errors.vue';
    export default {
        metaInfo: {
            title: '401 Unauthorized',
        },
        components: {
            Layout,
        },
        props: {
            exception: Object,
        },
    }
</script>
