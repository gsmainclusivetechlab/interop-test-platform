<template>
    <layout :session="session" :test-case="testCase">
        <div  class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>{{ `Test steps of ${testCase.name}` }}</b>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead class="thead-light">
                    <tr>
                        <th class="text-nowrap w-auto">Number</th>
                        <th class="text-nowrap w-auto">Source</th>
                        <th class="text-nowrap w-auto">Target</th>
                        <th class="text-nowrap w-auto">Expected Data</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="testStep in testSteps.data">
                        <td class="align-middle">
                            {{ testStep.position }}
                        </td>
                        <td class="align-middle">
                            {{ testStep.source ? testStep.source.name : '' }}
                        </td>
                        <td class="align-middle">
                            {{ testStep.target ? testStep.target.name : '' }}
                        </td>
                        <td class="align-middle">
                            <div class="btn-group">
                                <button class="btn btn-secondary" v-b-modal="`modal-request-${testStep.id}`">
                                    Request
                                </button>
                                <button class="btn btn-secondary" v-b-modal="`modal-response-${testStep.id}`">
                                    Response
                                </button>
                            </div>
                            <b-modal
                                :id="`modal-request-${testStep.id}`"
                                @shown="shownModal"
                                size="lg"
                                centered
                                hide-footer
                                hide-header-close
                                title="Request"
                            >
                                <div class="border">
                                    <div class="d-flex" v-if="testStep.request_example.uri">
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>Endpoint</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            <div class="mb-0 p-0 bg-transparent">
                                                {{ testStep.request_example.uri }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex" v-if="testStep.request_example.method">
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>Method</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            <div class="mb-0 p-0 bg-transparent">
                                                {{ testStep.request_example.method }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex" v-if="collect(testStep.request_example.headers).count()">
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>Headers</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            <div class="mb-0 p-0 bg-transparent">
                                                <pre class="mb-0 p-0 text-h5"><code>{{ testStep.request_example.headers }}</code></pre>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex" v-if="collect(testStep.request_example.body).count()">
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>Body</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            <div class="mb-0 p-0 bg-transparent">
                                                <pre class="mb-0 p-0 text-h5"><code>{{ testStep.request_example.body }}</code></pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </b-modal>
                            <b-modal
                                :id="`modal-response-${testStep.id}`"
                                @shown="shownModal"
                                size="lg"
                                centered
                                hide-footer
                                hide-header-close
                                title="Response"
                            >
                                <div class="border">
                                    <div class="d-flex" v-if="testStep.response_example.status">
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>Status</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            <div class="mb-0 p-0 bg-transparent">
                                                {{ testStep.response_example.status }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex" v-if="collect(testStep.response_example.headers).count()">
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>Headers</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            <div class="mb-0 p-0 bg-transparent">
                                                <pre class="mb-0 p-0 text-h5"><code>{{ testStep.response_example.headers }}</code></pre>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex" v-if="collect(testStep.response_example.body).count()">
                                        <div class="w-25 px-4 py-2 border">
                                            <strong>Body</strong>
                                        </div>
                                        <div class="w-75 px-4 py-2 border">
                                            <div class="mb-0 p-0 bg-transparent">
                                                <pre class="mb-0 p-0 text-h5"><code>{{ testStep.response_example.body }}</code></pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </b-modal>
                        </td>
                    </tr>
                    <tr v-if="!testSteps.data.length">
                        <td class="text-center" colspan="4">
                            No Results
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="testSteps.meta"
                :links="testSteps.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
    import hljs from 'highlight.js';
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
            testSteps: {
                type: Object,
                required: true
            },
        },
        methods: {
            shownModal(modal) {
                modal.target.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightBlock(block);
                });
            }
        }
    };
</script>
