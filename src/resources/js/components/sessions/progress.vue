<template>
    <b-progress
        class="w-100 h-3 rounded-0 progress"
        v-b-tooltip.hover.top
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
        session: {
            type: Object,
            required: false,
        },
    },
    data() {
        let total = this.session?.testCasesCount || 0;
        let passed = this.session?.progress.passed || 0;
        let failures = this.session?.progress.failures || 0;
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
