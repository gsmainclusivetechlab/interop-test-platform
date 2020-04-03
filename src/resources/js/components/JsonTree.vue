<template>
    <div></div>
</template>

<script>
import jsonTree from '../libs/jsonTree';

export default {
    props: {
        code: {
            type: Object,
            required: true,
        },
    },
    mounted() {
        const data = this.$props.code;
        const tree = jsonTree.create(data, this.$el);
    },
};
</script>
