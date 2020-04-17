<template>
    <b-progress class="w-100 h-3 rounded-0 progress">
        <b-progress-bar
            v-for="option in options"
            :variant="option.variant"
            v-b-tooltip.hover
            :title="option.title"
            :value="option.value"
        />
    </b-progress>
</template>

<script>
export default {
    props: {
        session: {
            type: Object,
            required: true
        },
    },
    data() {
        let total = this.session.testCases ? this.session.testCases.length : 0;
        let passed = this.session.testCases
            ?
            collect(this.session.testCases)
                .flatMap(value => (value.lastTestRun && value.lastTestRun.successful) ? 1 : 0)
                .sum()
            :
            0;
        let failures = this.session.testCases
            ?
            collect(this.session.testCases)
                .flatMap(value => (value.lastTestRun && !value.lastTestRun.successful) ? 1 : 0)
                .sum()
            :
            0;
        let skipped = total - passed - failures;

        return {
            options: [
                {
                    variant: 'success',
                    value: total ? passed / total * 100 : 0,
                    title: `${passed} Pass`,
                },
                {
                    variant: 'danger',
                    value: total ? failures / total * 100 : 0,
                    title: `${failures} Fail`,
                },
                {
                    variant: 'secondary',
                    value: total ? skipped / total * 100 : 0,
                    title: `${skipped} Not executed`,
                },
            ]
        };
    },
};
</script>
