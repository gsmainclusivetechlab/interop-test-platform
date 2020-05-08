<template>
    <div class="page">
        <slot />
        <cookies />
    </div>
</template>

<script>
    import Gtag from '@/components/gtag';
    import Cookies from '@/components/cookies';
    import Notifications from '@/components/notifications';

    export default {
        mixins: [
            Gtag,
            Notifications,
        ],
        components: {
            Cookies
        },
        metaInfo() {
            return {
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
            };
        }
    };
</script>
