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
                import(
                    /* webpackChunkName: "sweetalert2" */ 'sweetalert2'
                    ).then(({ default: Swal }) =>
                    Swal.fire({
                        icon: 'warning',
                        title: this.confirmTitle,
                        text: this.confirmText,
                        buttonsStyling: false,
                        showCancelButton: true,
                        confirmButtonText: 'Confirm',
                        customClass: {
                            confirmButton: 'btn btn-primary mr-4',
                            cancelButton: 'btn btn-secondary',
                        },
                        heightAuto: false,
                    }).then(
                        (result) => {
                            this.confirmed = result.value;
                            if (result.value) {
                                this.$el.click();
                            }
                        },
                    ),
                );
            }
        },
    },
};
</script>
