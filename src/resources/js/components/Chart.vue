<template>
    <div id="chart"></div>
</template>

<script>
    import c3 from 'c3';

    export default {
        data() {
            return {};
        },
        props: {
            data: {
                type: Object,
                default() {
                    return {
                        columns: [
                            ['passed', 4,3,1,0,9,2,7,2,1,1,5,9,2,6],
                            ['blocked', 1,9,6,3,4,0,4,9,3,4,7,9,8,4],
                            ['retest', 7,4,7,3,5,5,3,0,2,8,4,1,3,8],
                            ['failed', 0,1,1,0,9,2,9,9,9,1,7,9,7,5]
                        ],
                        colors: {
                            'passed': '#9cb227',
                            'blocked': '#7c7c7c',
                            'retest': '#fcbb2c',
                            'failed': '#de002b'
                        },
                    }
                }
            },
            axis: {
                type: Object,
                default() {
                    return {
                        x: {
                            type: 'category',
                            categories: [1,2,3,4,5,6,7,8,9,10,11,12,13,14]
                        }
                    }
                }
            },
            legend: {
                type: Object,
                default() {
                    return {
                        position: 'right'
                    }
                }
            },
            padding: {
                type: Object,
                default() {
                    return {
                        top: 0,
                        bottom: 0
                    }
                }
            },
        },
        mounted() {
            const {
                data,
                axis,
                legend,
                padding
            } = this.$props;

            c3.generate({
                bindto: this.$el,
                data,
                axis,
                legend,
                padding
            });
        },
        methods: {

        }
    }
</script>
