<template>
    <apexchart
        height="360"
        type="bar"
        :options="this.getOptions"
        :series="this.$props.series"
    ></apexchart>
</template>

<script>
export default {
    data() {
        return {
            chartOptions: {
                chart: {
                    stacked: true,
                    fontFamily: 'gotham',
                    toolbar: {
                        show: false,
                    },
                    animations: {
                        speed: 300,
                        animateGradually: {
                            delay: 100,
                        },
                    },
                    zoom: {
                        enabled: false,
                    },
                },
                colors: ['#9cb227', '#de002b'],
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
                noData: {
                    text: 'No filters selected',
                },
            },
        };
    },
    props: {
        options: {
            type: Object,
        },
        series: {
            type: Array,
            required: true,
        },
    },
    computed: {
        getOptions() {
            return {
                ...this.chartOptions,
                ...this.$props.options,
            };
        },
    },
    components: {
        apexchart: () =>
            import(/* webpackChunkName: "apexchart" */ 'vue-apexcharts'),
    },
};
</script>
