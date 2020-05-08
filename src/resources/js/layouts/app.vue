<template>
    <div class="page">
        <slot />

        <div class="col-sm-6 col-md-5 col-lg-4 col-xl-3 mb-2 mr-2 ml-2 ml-sm-auto fixed-bottom">
            <div class="card m-0">
                <div class="card-body">
                    <p>
                        We use cookies to analyse our traffic and enhance the user
                        experience for the platform. By continuing to use our site,
                        you accept our use of cookies.
                        <a
                            href="https://www.gsma.com/aboutus/legal/cookie-policy"
                            class="test-decoration-underline text-nowrap"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Learn more
                        </a>
                    </p>
                    <button type="button" class="btn btn-primary w-100">
                        Got It!
                    </button>
                </div>
            </div>
        </div>
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
