<template>
    <layout :session="session" :test-case="testCase">
        <diagram>
            sequenceDiagram;
            <template v-for="testStep in testSteps.data">
                {{ testStep.source.name }}->>{{ testStep.target.name }}: {{ testStep.forward }};
                {{ testStep.target.name }}-->>{{ testStep.source.name }}: {{ testStep.backward }};
            </template>
        </diagram>
    </layout>
</template>

<script>
import Layout from '@/layouts/sessions/test-case';
import Diagram from '@/components/diagram';

export default {
    components: {
        Layout,
        Diagram
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
        testSteps: {
            type: Object,
            required: true
        },
    }
};
</script>
