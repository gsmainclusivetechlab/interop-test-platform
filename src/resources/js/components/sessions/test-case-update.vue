<template>
    <span>
        <button
            v-if="
                !isCompliance &&
                testCase.lastAvailableVersion &&
                checkLastVersion
            "
            @click.prevent="showModal"
            type="button"
            class="btn btn-sm btn-outline-primary text-uppercase"
            v-b-tooltip.hover
            title="A laster version of this test case is available. Click to update your session with it."
        >
            update
        </button>
        <b-modal
            :id="`update-test-case-${versions.current.id}`"
            size="lg"
            centered
            hide-footer
            title="Are you sure?"
        >
            <p>
                After update, version can't revert and Test Runs of current
                version won't be available.
            </p>
            <div class="text-right">
                <button
                    type="button"
                    @click.prevent="hideModal"
                    class="btn btn-link mr-1"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    @click.prevent="submit"
                    class="btn btn-primary"
                >
                    Ok
                </button>
            </div>
        </b-modal>
    </span>
</template>

<script>
export default {
    props: {
        testCase: {
            type: Object,
            required: true,
        },
        sessionId: {
            type: Number,
            required: false,
        },
        isCompliance: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            versions: {
                current: {
                    version: this.testCase?.version,
                    id: this.testCase?.id,
                },
                last: {
                    version: this.testCase.lastAvailableVersion?.version,
                    id: this.testCase.lastAvailableVersion?.id,
                },
            },
        };
    },
    methods: {
        submit() {
            this.hideModal();

            this.$inertia
                .put(
                    route('sessions.update-test-case', [
                        this.sessionId,
                        this.versions.current.id,
                        this.versions.last.id,
                    ])
                )
                .then(() => {
                    this.$emit('update', this.versions);
                });
        },
        showModal() {
            this.$bvModal.show(`update-test-case-${this.versions.current.id}`);
        },
        hideModal() {
            this.$bvModal.hide(`update-test-case-${this.versions.current.id}`);
        },
    },
    computed: {
        checkLastVersion() {
            return (
                this.testCase.lastAvailableVersion?.version !==
                this.testCase?.version
            );
        },
    },
};
</script>
