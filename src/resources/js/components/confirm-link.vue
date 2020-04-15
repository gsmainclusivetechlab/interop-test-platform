<template>
    <inertia-link @click="confirm">
        <slot></slot>
    </inertia-link>
</template>

<script>
export default {
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
                    Swal.fire({ ...this.$props, heightAuto: false }).then(
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
