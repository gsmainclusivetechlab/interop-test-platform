<template>
    <div class="page">
        <slot />
    </div>
</template>

<script>
    import Gtag from '@/components/gtag';
    import Notifications from '@/components/notifications';

    export default {
        mixins: [
            Gtag,
            Notifications,
        ],
        metaInfo: {
            link: [
                {
                    rel: 'icon',
                    type: 'image/png',
                    sizes: '32x32',
                    href: '/assets/images/favicon/favicon.png'
                },
                {
                    rel: 'apple-touch-icon',
                    type: 'image/png',
                    href: '/assets/images/favicon/apple-touch-icon.png'
                }
            ],
            titleTemplate: title =>
                title
                    ? `${title} - Interoperability Test Platform - GSMA`
                    : 'Interoperability Test Platform - GSMA'
        },
    };
</script>
