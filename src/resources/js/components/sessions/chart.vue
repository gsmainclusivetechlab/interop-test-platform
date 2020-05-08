<template>
    <apexchart
        ref="chart"
        type="bar"
        height="380"
        :series="series"
        :options="options"
    />
</template>
<script>
    import apexchart from 'vue-apexcharts';

    export default {
        components: {
            apexchart,
        },
        props: {
            session: {
                type: Object,
                required: true
            },
        },
        data() {
            return {
                series: [],
                options: {
                    chart: {
                        fontFamily: 'inherit',
                        stacked: true,
                        toolbar: {
                            show: false,
                        },
                        zoom: {
                            enabled: false,
                        },
                    },
                    colors: ['#00a182', '#de002b'],
                    fill: {
                        opacity: 1,
                    },
                    legend: {
                        position: 'top',
                        markers: {
                            radius: 100,
                        },
                    },
                    xaxis: {
                        labels: {
                            padding: 0,
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                },
            };
        },
        async mounted() {
            await axios
                .get(route('sessions.chart', this.session.id))
                .then(({ data }) => {
                    this.series = data;
                    this.$refs.chart.updateOptions(this.options);
                })
                .catch(() => {
                    this.$refs.chart.updateOptions(this.options);
                });
        },
    };
</script>
