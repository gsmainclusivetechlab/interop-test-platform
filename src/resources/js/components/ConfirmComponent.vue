<template>
    <component is="button" @click="e => !confirmed && confirm(e)">
        <slot></slot>
    </component>
</template>

<script>
    import Swal from 'sweetalert2';

    export default {
        data: function () {
            return {
                confirmed: false
            };
        },
        props: {
            title: {
                type: String,
                default: 'Are you sure?'
            },
            text: {
                type: String,
                default: "You won't be able to revert this!"
            },
            buttonsStyling: {
                type: Boolean,
                default: false
            },
            showCancelButton: {
                type: Boolean,
                default: true
            },
            customClass: {
                type: Object,
                default: function () {
                    return {
                        confirmButton: 'btn btn-primary mr-4',
                        cancelButton: 'btn btn-secondary'
                    };
                }
            },
            confirmButtonText: {
                type: String,
                default: 'Confirm'
            }
        },
        methods: {
            confirm: function (event) {
                if (!this.confirmed) {
                    event.preventDefault();
                    Swal.fire(this.$props).then((result) => {
                        this.confirmed = result.value;
                        if (result.value) {
                            this.$el.click();
                        }
                    });
                }
            }
        }
    }
</script>
