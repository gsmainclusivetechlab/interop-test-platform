<template>
    <inertia-link
        :href="href"
        :data="data"
        :method="method"
        @click="confirm"
        as="button"
    >
        <slot></slot>
    </inertia-link>
</template>

<script>
export default {
    props: {
        href: {
            type: String,
            required: true,
        },
        data: {
            type: Object,
            required: false,
        },
        method: {
            type: String,
            required: false,
        },
        confirmTitle: {
            type: String,
        },
        confirmText: {
            type: String,
        },
    },
    data() {
        return {
            confirmed: false,
        };
    },
    computed: {
        modalTitle() {
            return (
                this.confirmTitle ??
                this.$t('components.confirm-link.modal.title')
            );
        },
        modalText() {
            return (
                this.confirmText ??
                this.$t('components.confirm-link.modal.text')
            );
        },
    },
    methods: {
        confirm(e) {
            const h = this.$createElement;

            const messageVNode = h(
                'span',
                {
                    class: ['text-break'],
                },
                [this.modalText]
            );

            if (!this.confirmed) {
                e.preventDefault();
                this.$bvModal
                    .msgBoxConfirm([messageVNode], {
                        title: this.modalTitle,
                        okTitle: this.$t('components.confirm-link.buttons.ok'),
                        cancelTitle: this.$t(
                            'components.confirm-link.buttons.cancel'
                        ),
                        centered: true,
                    })
                    .then((value) => {
                        this.confirmed = value;
                        if (this.confirmed) {
                            this.$el.click();
                            this.confirmed = false;
                        }
                    });
            }
        },
    },
};
</script>
