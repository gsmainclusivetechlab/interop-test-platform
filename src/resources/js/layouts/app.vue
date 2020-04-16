<template>
    <div class="page">
        <app-header />
        <main class="content">
            <div class="container-fluid">
                <slot />
            </div>
            <app-footer />
        </main>
    </div>
</template>

<script>
    import AppHeader from '@/layouts/includes/app-header';
    import AppFooter from '@/layouts/includes/app-footer';
    import Gtag from '@/components/gtag';
    import Notifications from '@/components/notifications';

    export default {
        mixins: [
            Gtag,
            Notifications,
        ],
        components: {
            AppHeader,
            AppFooter,
        },
    };
</script>
