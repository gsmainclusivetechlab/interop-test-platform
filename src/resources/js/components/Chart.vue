<template>
    <div>
        <apexchart
            ref="chart"
            :type="this.$props.type"
            :height="this.$props.height"
            :options="chartOptions"
            :series="chartData"
        ></apexchart>
    </div>
</template>

<script>
import axios from 'axios';
import merge from 'lodash/merge';

export default {
    data() {
        return {
            defaultOptions: {
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
        options: {
            type: Object,
        },
        type: {
            type: String,
            default: 'bar',
        },
        height: {
            type: Number,
            default: 360,
        },
    },
    async mounted() {
        this.defaultOptions.noData.text = this.noDataList.loading;

        await this.fetchData(this.$props.ajaxUrl);
    },
    methods: {
        fetchData(url) {
            return axios
                .get(url)
                .then(({ data }) => {
                    this.chartData = data;

                    this.$refs.chart.updateOptions({
                        ...this.chartOptions,
                        noData: {
                            text: this.noDataList.success,
                        },
                    });
                })
                .catch((error) => {
                    this.$refs.chart.updateOptions({
                        ...this.chartOptions,
                        noData: {
                            text: this.noDataList.error,
                        },
                    });
                });
        },
    },
    computed: {
        chartOptions() {
            return merge(this.defaultOptions, this.$props.options);
        },
    },
    components: {
        apexchart: () => import('vue-apexcharts'),
    },
};
</script>
