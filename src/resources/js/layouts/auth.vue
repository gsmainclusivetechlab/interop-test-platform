<template>
    <layout>
        <div class="flex-fill d-flex flex-column justify-content-center">
            <div class="container-tight py-6">
                <div class="text-center mb-4">
                    <inertia-link :href="route('home')">
                        <img
                            src="/assets/images/logo.png"
                            class="mb-2 h-5"
                            :alt="$t('layout.main.main-nav.title')"
                        />
                    </inertia-link>
                    <div class="text-primary">
                        <h1 class="col-login__title mb-1">
                            {{ $t('layout.main.main-nav.title') }}
                        </h1>
                        <h2 class="col-login__subtitle mb-0">
                            {{ $t('layout.main.main-nav.subtitle') }}
                        </h2>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/app';

    export default {
        components: {
            Layout,
        },
    };
</script>
