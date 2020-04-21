<template>
    <inertia-link :href="href" :data="data" :method="method" @click="confirm">
        <slot></slot>
    </inertia-link>
</template>

<script>
export default {
    props: {
        href: {
            type: String,
            required: true
        },
        data: {
            type: Object,
            required: false
        },
        method: {
            type: String,
            required: false
        },
        confirmTitle: {
            type: String,
            default: 'Are you sure?',
        },
        confirmText: {
            type: String,
            default: "You won't be able to revert this!",
        },
    },
    data() {
        return {
            confirmed: false,
        };
    },
    methods: {
        confirm(e) {
            if (!this.confirmed) {
                e.preventDefault();
                this.$bvModal.msgBoxConfirm(this.confirmText, {
                    title: this.confirmTitle,
                    okTitle: 'Confirm',
                    centered: true,
                }).then(value => {
                    this.confirmed = value;
                    if (this.confirmed) {
                        this.$el.click();
                    }
                });
            }
        },
    },
};
</script>
