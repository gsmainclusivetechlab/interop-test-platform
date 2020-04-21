<template>
    <layout :session="session" :test-case="testCase">

    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/test-case';

export default {
    components: {
        Layout
    },
    props: {
        session: {
            type: Object,
            required: true
        },
        testCase: {
            type: Object,
            required: true
        },
    }
};
</script>
