<template>
    <b-progress
        class="w-100 h-3 rounded-0 progress"
        v-b-tooltip.hover
        :title="title"
    >
        <b-progress-bar
            v-for="option in options"
            :key="option.key"
            :variant="option.key"
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
            ? collect(this.testCases)
                  .flatMap((value) =>
                      value.lastTestRun && value.lastTestRun.successful ? 1 : 0
                  )
                  .sum()
            : 0;
        let failures = this.testCases
            ? collect(this.testCases)
                  .flatMap((value) =>
                      value.lastTestRun && !value.lastTestRun.successful ? 1 : 0
                  )
                  .sum()
            : 0;
        let unexecuted = total - passed - failures;
        let titles = [
            { name: `${passed} Pass`, total: passed },
            { name: `${failures} Fail`, total: failures },
            { name: `${unexecuted} Unexecuted`, total: unexecuted },
        ];

        return {
            title: collect(titles)
                .filter((value) => value.total)
                .flatMap((value) => value.name)
                .implode(' / '),
            options: [
                {
                    key: 'success',
                    value: total ? (passed / total) * 100 : 0,
                },
                {
                    key: 'danger',
                    value: total ? (failures / total) * 100 : 0,
                },
                {
                    key: 'secondary',
                    value: total ? (unexecuted / total) * 100 : 0,
                },
            ],
        };
    },
};
</script>
