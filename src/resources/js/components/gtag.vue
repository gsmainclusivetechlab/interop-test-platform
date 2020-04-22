<script>
    export default {
        metaInfo() {
            return this.$page.app.debug ? {} : {
                script: [
                    {
                        src: `https://www.googletagmanager.com/gtag/js?id=${this.gtagId}`,
                        async: true,
                    },
                    {
                        type: 'text/javascript',
                        innerHTML: 'window.dataLayer = window.dataLayer || [];' +
                            'function gtag(){dataLayer.push(arguments);}' +
                            `gtag('js', new Date());` +
                            `gtag('config', '${this.gtagId}');`,
                    }
                ],
            };
        },
        props: {
            gtagId: {
                type: String,
                default: 'UA-162371764-1',
            }
        },
    };
</script>
