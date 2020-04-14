<template>
    <layout>
        <div class="page-header m-0 py-2">
            <h1 class="page-title">
                <b>Latest sessions</b>
            </h1>
        </div>
        <div class="row row-cards row-deck">

        </div>
    </layout>
</template>

<script>
    import Layout from '../layouts/app.vue';
    export default {
        metaInfo: {
            title: 'Dashboard',
        },
        components: {
            Layout,
        },
        data() {
            return {}
        },
    }
</script>
