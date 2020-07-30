<template>
    <select />
</template>

<script>
import 'selectize';

export default {
    props: {
        value: {
            required: false,
        },
        load: {
            type: Function,
            required: false,
        },
        render: {
            type: Object,
            required: false,
        },
        preload: {
            type: Boolean,
            required: false,
            default: false,
        },
        options: {
            type: Array,
            required: false,
        },
        valueField: {
            type: String,
            required: false,
            default: 'value',
        },
        labelField: {
            type: String,
            required: false,
            default: 'text',
        },
        searchField: {
            type: [String, Array],
            required: false,
            default: 'text',
        },
    },
    mounted() {
        let $vm = this;
        let $select = $(this.$el).selectize({
            load: this.load,
            render: this.render,
            preload: this.preload,
            options: this.options,
            valueField: this.valueField,
            labelField: this.labelField,
            searchField: this.searchField,
        });
        $select[0].selectize.setValue(this.value);
        $select.on('change', function () {
            $vm.$emit('input', this.value);
        });
    },
    watch: {
        value: function (value) {
            $(this.$el).val(value).trigger('change');
        },
    },
};
</script>
