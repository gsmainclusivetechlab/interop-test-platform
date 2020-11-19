<template>
    <span>
        <button
            v-if="currentCase.lastAvailableVersion && checkLastVersion"
            @click.prevent="showModal"
            type="button"
            class="btn btn-sm btn-outline-primary text-uppercase"
            v-b-tooltip.hover
            title="A newer version of this test case is available. Click to update your session with it."
        >
            update
        </button>
        <b-modal
            :id="`update-test-case-${this.currentCase.id}`"
            size="lg"
            centered
            hide-footer
            title="Are you sure?"
        >
            <p>After update version cann't revert.</p>
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
                    @click.prevent="updateVersion"
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
        },
        sessionId: {
            type: Number,
        },
    },
    data() {
        return {
            currentCase: this.testCase,
        };
    },
    methods: {
        updateVersion() {
            this.hideModal();

            this.$inertia
                .put(
                    route('sessions.update-test-case', [
                        this.sessionId,
                        this.currentCase.id,
                        this.currentCase.lastAvailableVersion.id,
                    ])
                )
                .then(() => {
                    this.currentCase.version = this.currentCase.lastAvailableVersion.version;
                });
        },
        showModal() {
            this.$bvModal.show(`update-test-case-${this.currentCase.id}`);
        },
        hideModal() {
            this.$bvModal.hide(`update-test-case-${this.currentCase.id}`);
        },
    },
    computed: {
        checkLastVersion() {
            return (
                this.currentCase.lastAvailableVersion?.version !==
                this.currentCase?.version
            );
        },
    },
};
</script>
