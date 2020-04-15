<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="container-tight py-6">
                <div class="text-center mb-4">
                    <inertia-link :href="route('home')">
                        <img
                            src="/assets/images/logo.png"
                            class="mb-2"
                            alt="Interoperability Test Platform"
                        />
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
    </layout>
</template>

<script>
import Layout from '@/layouts/default';

export default {
    components: {
        Layout
    }
};
</script>
