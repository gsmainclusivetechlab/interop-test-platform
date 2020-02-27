<template>
    <div id="app" class="page">
        <div class="flex-fill">
            <TheHeader />

            <div class="my-3 my-md-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <router-view></router-view>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <TheFooter />
    </div>
</template>

<script>
import TheHeader from '../components/TheHeader.vue';
import TheFooter from '../components/TheFooter.vue';

export default {
    components: {
        TheHeader,
        TheFooter,
    },
};
</script>
