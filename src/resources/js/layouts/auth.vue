<template>
    <div class="flex-fill d-flex flex-column justify-content-center">
        <div class="container-tight py-6">
            <div class="text-center mb-5">
                <inertia-link :href="route('home')">
                    <img src="../../../public/images/logo.png" class="mb-2" alt="Interoperability Test Platform">
                </inertia-link>
                <div class="text-primary">
                    <h1 class="col-login__title mb-1">
                        Inclusive Tech Lab
                    </h1>
                    <h2 class="col-login__subtitle mb-0">
                        Interoperability Test Platform
                    </h2>
                </div>
            </div>
            <slot />
        </div>
    </div>
</template>

<script>
    import Noty from 'noty';
    export default {
        mounted() {
            let messages = this.$page.messages;

            for (let type in messages) {
                new Noty({
                    type: type,
                    text: messages[type],
                    theme: 'bootstrap-v4',
                }).show();
            }
        },
    }
</script>
