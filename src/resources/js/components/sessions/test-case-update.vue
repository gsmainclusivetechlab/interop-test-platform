<template>
    <span>
        <button
            v-if="
                !isCompliance &&
                testCase.lastAvailableVersion &&
                checkLastVersion
            "
            v-b-modal="`update-test-case-${versions.current.id}`"
            v-b-popover.hover.top.html="
                'A newer version of this test case is available.<br> Click to update your session with it.'
            "
            type="button"
            class="btn btn-sm btn-outline-primary text-uppercase"
        >
            update
        </button>
        <b-modal
            :id="`update-test-case-${versions.current.id}`"
            size="lg"
            centered
            title="Are you sure?"
            @ok="submit"
        >
            <p>
                After update, version can't revert and Test Runs of current
                version won't be available.
            </p>
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
            this.$inertia.put(
                route('sessions.update-test-case', [
                    this.sessionId,
                    this.versions.current.id,
                    this.versions.last.id,
                ]),
                {},
                {
                    onFinish: () => {
                        this.$emit('update', this.versions);
                    },
                }
            );
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
