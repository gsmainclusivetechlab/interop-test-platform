<template>
    <b-progress class="w-100 h-3 rounded-0 progress">
        <b-progress-bar
            v-for="option in options"
            :key="option.key"
            :variant="option.key"
            v-b-tooltip.hover
            :title="option.title"
            :value="option.value"
        />
    </b-progress>
</template>

<script>
export default {
    props: {
        testCases: {
            type: Array,
            required: false,
        },
    },
    data() {
        let total = this.testCases ? this.testCases.length : 0;
        let passed = this.testCases
            ?
            collect(this.testCases)
                .flatMap(value => (value.lastTestRun && value.lastTestRun.successful) ? 1 : 0)
                .sum()
            :
            0;
        let failures = this.testCases
            ?
            collect(this.testCases)
                .flatMap(value => (value.lastTestRun && !value.lastTestRun.successful) ? 1 : 0)
                .sum()
            :
            0;
        let skipped = total - passed - failures;

        return {
            options: [
                {
                    key: 'success',
                    value: total ? passed / total * 100 : 0,
                    title: `${passed} Pass`,
                },
                {
                    key: 'danger',
                    value: total ? failures / total * 100 : 0,
                    title: `${failures} Fail`,
                },
                {
                    key: 'secondary',
                    value: total ? skipped / total * 100 : 0,
                    title: `${skipped} Not executed`,
                },
            ]
        };
    },
};
</script>
