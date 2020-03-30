<template>
    <div>
        <apexchart
            ref="chart"
            height="360"
            type="bar"
            :options="options"
            :series="chartData"
        ></apexchart>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            options: {
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
                    text: null,
                },
            },
            chartData: [],
            noDataList: {
                loading: 'Loading...',
                error: 'Failed to load data',
                success: 'No filters selected',
            },
        };
    },
    props: {
        ajaxUrl: {
            type: String,
            required: true,
        },
    },
    async mounted() {
        this.options.noData.text = this.noDataList.loading;

        await this.fetchData(this.$props.ajaxUrl);
    },
    methods: {
        fetchData(url) {
            return axios
                .get(url)
                .then(({ data }) => {
                    this.chartData = data;

                    this.$refs.chart.updateOptions({
                        ...this.options,
                        noData: {
                            text: this.noDataList.success,
                        },
                    });
                })
                .catch((error) => {
                    this.$refs.chart.updateOptions({
                        ...this.options,
                        noData: {
                            text: this.noDataList.error,
                        },
                    });
                });
        },
    },
    components: {
        apexchart: () =>
            import(/* webpackChunkName: "apexchart" */ 'vue-apexcharts'),
    },
};
</script>
