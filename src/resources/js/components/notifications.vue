<script>
    import Noty from 'noty';

    export default {
        mounted() {
            this.showNotifications();
        },
        methods: {
            showNotifications() {
                const { messages } = this.$page;

                for (const type in messages) {
                    new Noty({
                        type,
                        text: messages[type],
                        theme: 'bootstrap-v4'
                    }).show();
                }
            }
        },
        watch: {
            '$page.messages': {
                handler() {
                    this.showNotifications();
                },
                deep: true
            }
        }
    };
</script>
