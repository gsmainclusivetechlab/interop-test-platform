<template>
    <input type="text" :value="value" />
</template>

<script>
import 'selectize';

export default {
    props: {
        value: {
            required: false,
        },
        delimiter: {
            type: String,
            required: false,
            default: ', ',
        },
        persist: {
            type: Boolean,
            required: false,
            default: false,
        },
        create: {
            type: Boolean,
            required: false,
            default: true,
        },
        createOnBlur: {
            type: Boolean,
            required: false,
            default: true,
        },
    },
    mounted() {
        let $vm = this;
        let $select = $(this.$el).selectize({
            persist: this.persist,
            create: this.create,
            createOnBlur: this.createOnBlur,
            delimiter: this.delimiter,
        });
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
