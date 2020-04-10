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
import apexchart from 'vue-apexcharts';

export default {
    data() {
        return {
            defaultOptions: {
                chart: {
                    fontFamily: 'inherit',
                },
            },
            chartData: [],
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
        },
        height: {
            type: Number,
            default: 380,
        },
    },
    async mounted() {
        await this.fetchData(this.$props.ajaxUrl);
    },
    methods: {
        fetchData(url) {
            return axios
                .get(url)
                .then(({ data }) => {
                    this.chartData = data;

                    this.$refs.chart.updateOptions(this.chartOptions);
                })
                .catch((error) => {
                    this.$refs.chart.updateOptions(this.chartOptions);
                });
        },
    },
    computed: {
        chartOptions() {
            return merge(this.defaultOptions, this.$props.options);
        },
    },
    components: {
        apexchart,
    },
};
</script>
