<template>
    <span>
        <button
            type="button"
            class="btn btn-outline-primary"
            v-b-modal="`modal-status-${status}`"
        >
            <slot />
        </button>

        <b-modal
            :id="`modal-status-${status}`"
            :title="confirmTitle"
            centered
            @ok="submit"
        >
            <form class="mb-3">
                <label class="form-label">Reason</label>
                <textarea
                    v-model="form.reason"
                    :class="{
                        'is-invalid': $page.props.errors.reason || formError,
                    }"
                    class="form-control"
                ></textarea>
                <span
                    v-if="$page.props.errors.reason || formError"
                    class="invalid-feedback"
                >
                    {{
                        $page.props.errors.reason || 'Reason field is required'
                    }}
                </span>
            </form>
        </b-modal>
    </span>
</template>

<script>
export default {
    props: {
        href: {
            type: String,
            required: true,
        },
        status: {
            type: String,
            required: true,
        },
        confirmTitle: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            formError: false,
            form: {
                reason: '',
                status: this.status,
            },
        };
    },
    methods: {
        submit(e) {
            if (this.form.reason !== '') {
                this.formError = false;
            } else {
                e.preventDefault();
                this.formError = true;
                return;
            }

            this.$inertia.put(this.href, this.form);
        },
    },
};
</script>
