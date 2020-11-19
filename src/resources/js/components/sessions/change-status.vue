<template>
    <span>
        <inertia-link :href="href" @click.prevent="openModal">
            <slot />
        </inertia-link>

        <b-modal
            id="modal-prevent-closing"
            :ref="modalName"
            :title="confirmTitle"
            @ok="handleOk"
        >
            <form ref="form" @submit.prevent="handleSubmit" class="m-auto">
                <div class="mb-3">
                    <label class="form-label">Reason</label>
                    <input
                        v-model="form.reason"
                        :class="{ 'is-invalid': $page.errors.reason }"
                        class="form-control"
                        name="reason"
                    />
                    <span v-if="$page.errors.reason" class="invalid-feedback">
                        {{ $page.errors.reason }}
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
            this.handleSubmit();
        },
        handleSubmit() {
            this.$inertia.put(this.href, this.form).then(() => {
                this.$bvModal.hide('modal-prevent-closing');
            });
        },
    },
};
</script>
