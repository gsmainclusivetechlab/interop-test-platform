<template>
    <span>
        <inertia-link
            class="btn btn-outline-primary"
            :href="href"
            @click.prevent="openModal"
        >
            <slot />
        </inertia-link>

        <b-modal
            id="modal-prevent-closing"
            :ref="modalName"
            :title="confirmTitle"
            @ok="handleOk"
            centered
        >
            <form ref="form" @submit.prevent="handleSubmit">
                <div class="mb-3">
                    <label class="form-label">Reason</label>
                    <textarea
                        v-model="form.reason"
                        :class="{
                            'is-invalid': $page.errors.reason || formError,
                        }"
                        class="form-control"
                        name="reason"
                    ></textarea>
                    <span
                        v-if="$page.errors.reason || formError"
                        class="invalid-feedback"
                    >
                        {{ $page.errors.reason || 'Reason field is required' }}
                    </span>
                </div>
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
            modalName: 'model-' + this.status,
            formError: false,
            form: {
                reason: '',
                status: this.status,
            },
        };
    },
    methods: {
        openModal() {
            this.$refs[this.modalName].show();
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault();
            // Trigger submit handler
            if (this.form.reason !== '') {
                this.formError = false;
                this.handleSubmit();
            } else {
                this.formError = true;
            }
        },
        handleSubmit() {
            this.$inertia.put(this.href, this.form).then(() => {
                this.$bvModal.hide('modal-prevent-closing');
            });
        },
    },
};
</script>
