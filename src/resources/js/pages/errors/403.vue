<template>
    <layout :code="403" :message="exception.message ? exception.message : 'We are sorry but you do not have permission to access this page'"></layout>
</template>

<script>
    import Layout from '../../layouts/errors.vue';
    export default {
        metaInfo: {
            title: '403 Forbidden',
        },
        components: {
            Layout,
        },
        props: {
            exception: Object,
        },
    }
</script>
