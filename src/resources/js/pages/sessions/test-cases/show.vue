<template>
    <layout :session="session" :useCases="useCases" :testCase="testCase">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>{{ `Overview of ${testCase.name}` }}</b>
                </h2>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li v-if="testCase.description">
                        <p>
                            <strong>Description</strong>
                        </p>
                        <div v-html="testCase.description"></div>
                    </li>
                    <li v-if="testCase.precondition">
                        <p>
                            <strong>Precondition</strong>
                        </p>
                        <div v-html="testCase.precondition"></div>
                    </li>
                </ul>
            </div>
        </div>
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
        useCases: {
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
