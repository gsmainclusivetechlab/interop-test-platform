<template>
    <div class="page" :class="{ 'theme-dark': $page.props.app.dark_mode }">
        <slot />
        <cookies />
    </div>
</template>

<script>
import Noty from 'noty';
import Cookies from '@/components/cookies';

export default {
    components: {
        Cookies,
    },
    metaInfo() {
        return {
            link: [
                {
                    rel: 'icon',
                    type: 'image/png',
                    sizes: '32x32',
                    href: '/assets/images/favicon/favicon.png',
                },
                {
                    rel: 'apple-touch-icon',
                    type: 'image/png',
                    href: '/assets/images/favicon/apple-touch-icon.png',
                },
            ],
            script:
                this.$page.props.app.gtag && !this.$page.props.app.debug
                    ? [
                          {
                              src:
                                  'https://www.googletagmanager.com/gtag/js?id=' +
                                  this.$page.props.app.gtag,
                              async: true,
                          },
                          {
                              type: 'text/javascript',
                              innerHTML:
                                  'window.dataLayer = window.dataLayer || [];' +
                                  'function gtag(){dataLayer.push(arguments);}' +
                                  'gtag("js", new Date());' +
                                  `gtag("config", "${this.$page.props.app.gtag}");`,
                          },
                      ]
                    : [],
            titleTemplate: (title) =>
                title
                    ? `${title} - ` + this.$t('layout.main.main-nav.tab-name')
                    : this.$t('layout.main.main-nav.tab-name'),
        };
    },
    mounted() {
        this.showNotifications();
    },
    methods: {
        showNotifications() {
            collect(this.$page.props.messages).each((text, type) => {
                new Noty({
                    type: type,
                    text: text,
                    theme: 'bootstrap-v4',
                    timeout: 3000,
                }).show();
            });
        },
    },
    watch: {
        '$page.props.messages': {
            handler() {
                this.showNotifications();
            },
            deep: true,
        },
    },
};
</script>
