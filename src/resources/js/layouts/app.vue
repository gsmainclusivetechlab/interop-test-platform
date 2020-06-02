<template>
    <div class="page" v-bind:class="{ 'theme-dark': $page.app.dark_mode }">
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
            script: this.$page.app.debug
                ? []
                : [
                      {
                          src:
                              'https://www.googletagmanager.com/gtag/js?id=UA-162371764-1',
                          async: true,
                      },
                      {
                          type: 'text/javascript',
                          innerHTML:
                              'window.dataLayer = window.dataLayer || [];' +
                              'function gtag(){dataLayer.push(arguments);}' +
                              'gtag("js", new Date());' +
                              'gtag("config", "UA-162371764-1");',
                      },
                  ],
            titleTemplate: (title) =>
                title
                    ? `${title} - Interoperability Test Platform - GSMA`
                    : 'Interoperability Test Platform - GSMA',
        };
    },
    mounted() {
        this.showNotifications();
    },
    methods: {
        showNotifications() {
            collect(this.$page.messages).each((text, type) => {
                new Noty({
                    type: type,
                    text: text,
                    theme: 'bootstrap-v4',
                }).show();
            });
        },
    },
    watch: {
        '$page.messages': {
            handler() {
                this.showNotifications();
            },
            deep: true,
        },
    },
};
</script>
