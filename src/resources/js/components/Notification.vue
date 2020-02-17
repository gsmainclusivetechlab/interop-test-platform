<template>
    <div></div>
</template>

<script>
import Noty from 'noty';

export default {
    props: {
        options: {
            type: Object,
        },
    },
    mounted() {
        new Noty({
            closeWith: ['click', 'button'],
            timeout: 10000,
            ...this.$props.options,
            theme: 'bootstrap-v4',
        }).show();
    },
};
</script>

<style></style>
