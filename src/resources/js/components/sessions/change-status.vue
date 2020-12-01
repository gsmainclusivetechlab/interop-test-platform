<template>
    <span>
        <button
            type="button"
            class="btn btn-outline-primary"
            v-b-modal="`modal-status-${status}`"
        >
            <slot />
        </button>

        <b-modal :id="`modal-status-${status}`" :title="confirmTitle" centered>
            <form class="mb-3">
                <label class="form-label">Reason</label>
                <textarea
                    v-model="form.reason"
                    :class="{
                        'is-invalid': $page.errors.reason || formError,
                    }"
                    class="form-control"
                ></textarea>
                <span
                    v-if="$page.errors.reason || formError"
                    class="invalid-feedback"
                >
                    {{ $page.errors.reason || 'Reason field is required' }}
                </span>
            </form>
            <template #modal-footer>
                <button
                    type="button"
                    class="btn btn-secondary"
                    @click="$bvModal.hide(`modal-status-${status}`)"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    class="btn btn-primary ml-2"
                    @click="submit"
                >
                    Ok
                </button>
            </template>
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
        submit() {
            if (this.form.reason !== '') {
                this.formError = false;
            } else {
                this.formError = true;
                return;
            }

            this.$inertia.put(this.href, this.form).then(() => {
                this.$bvModal.hide(`modal-status-${this.status}`);
            });
        },
    },
};
</script>
