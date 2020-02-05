<template>
    <button @click="confirm">
        <slot></slot>
    </button>
</template>

<script>
import Swal from 'sweetalert2';

export default {
    data() {
        return {
            confirmed: false,
        };
    },
    props: {
        title: {
            type: String,
            default: 'Are you sure?',
        },
        icon: {
            type: String,
            default: 'warning',
        },
        text: {
            type: String,
            default: "You won't be able to revert this!",
        },
        buttonsStyling: {
            type: Boolean,
            default: false,
        },
        showCancelButton: {
            type: Boolean,
            default: true,
        },
        customClass: {
            type: Object,
            default() {
                return {
                    confirmButton: 'btn btn-primary mr-4',
                    cancelButton: 'btn btn-secondary',
                };
            },
        },
        confirmButtonText: {
            type: String,
            default: 'Confirm',
        },
    },
    methods: {
        confirm(e) {
            if (!this.confirmed) {
                e.preventDefault();

                Swal.fire(this.$props).then((result) => {
                    this.confirmed = result.value;

                    if (result.value) {
                        this.$el.click();
                    }
                });
            }
        },
    },
};
</script>
