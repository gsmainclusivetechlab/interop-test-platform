<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h2 class="page-title">
                        Latest sessions
                    </h2>
                </div>
            </div>
        </div>
        <div class="row row-cards row-deck"></div>
    </layout>
</template>

<script>
import Layout from '@/layouts/app';

export default {
    metaInfo: {
        title: 'Dashboard'
    },
    components: {
        Layout
    }
};
</script>
