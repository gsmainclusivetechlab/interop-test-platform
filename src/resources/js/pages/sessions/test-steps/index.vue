<template>
    <layout
        :session="session"
        :testCase="testCase"
        :testSteps="testSteps"
        :isAvailableRun="isAvailableRun"
        :testStepFirstSource="testStepFirstSource"
    >
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <b>{{ `Test steps of ${testCase.name}` }}</b>
                </h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap w-auto">Number</th>
                            <th class="text-nowrap w-auto">Method</th>
                            <th class="text-nowrap w-auto">Endpoint</th>
                            <th class="text-nowrap w-auto">Source</th>
                            <th class="text-nowrap w-auto">Target</th>
                            <th class="text-nowrap w-auto">Expected Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(testStep, i) in testSteps.data" :key="i">
                            <td class="align-middle">
                                {{ testStep.position }}
                            </td>
                            <td class="align-middle">
                                {{ testStep.method }}
                            </td>
                            <td class="align-middle">
                                {{ testStep.path }}
                            </td>
                            <td class="align-middle">
                                {{
                                    testStep.source ? testStep.source.name : ''
                                }}
                            </td>
                            <td class="align-middle">
                                {{
                                    testStep.target ? testStep.target.name : ''
                                }}
                            </td>
                            <td class="align-middle">
                                <div class="btn-group">
                                    <button
                                        class="btn btn-secondary"
                                        v-if="simulatedTestResults[testStep.id].request"
                                        v-b-modal="
                                            `modal-request-${testStep.id}`
                                        "
                                    >
                                        Request
                                    </button>
                                    <button
                                        class="btn btn-secondary"
                                        v-if="simulatedTestResults[testStep.id].response"
                                        v-b-modal="
                                            `modal-response-${testStep.id}`
                                        "
                                    >
                                        Response
                                    </button>
                                </div>
                                <b-modal
                                    :id="`modal-request-${testStep.id}`"
                                    size="lg"
                                    centered
                                    hide-footer
                                    hide-header-close
                                    title="Request"
                                >
                                    <clipboard-json-to-curl
                                        :request="simulatedTestResults[testStep.id].request"
                                    >
                                    </clipboard-json-to-curl>
                                    <div class="border">
                                        <div
                                            class="d-flex"
                                            v-if="
                                                simulatedTestResults[testStep.id].request &&
                                                simulatedTestResults[testStep.id].request.uri
                                            "
                                        >
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Endpoint</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    {{ simulatedTestResults[testStep.id].request.uri }}
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex"
                                            v-if="
                                                simulatedTestResults[testStep.id].request &&
                                                simulatedTestResults[testStep.id].request.method
                                            "
                                        >
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Method</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    {{
                                                    simulatedTestResults[testStep.id].request.method
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex"
                                            v-if="
                                                simulatedTestResults[testStep.id].request &&
                                                simulatedTestResults[testStep.id].request.headers !==
                                                    undefined
                                            "
                                        >
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Headers</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree
                                                        :data="
                                                            simulatedTestResults[testStep.id].request
                                                                .headers
                                                        "
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex"
                                            v-if="
                                                simulatedTestResults[testStep.id].request &&
                                                simulatedTestResults[testStep.id].request.body !==
                                                    undefined &&
                                                simulatedTestResults[testStep.id].request.body !==
                                                    'empty_body'
                                            "
                                        >
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Body</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree
                                                        :data="
                                                            simulatedTestResults[testStep.id].request
                                                                .body
                                                        "
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </b-modal>
                                <b-modal
                                    :id="`modal-response-${testStep.id}`"
                                    size="lg"
                                    centered
                                    hide-footer
                                    hide-header-close
                                    title="Response"
                                >
                                    <div class="border">
                                        <div
                                            class="d-flex"
                                            v-if="
                                                simulatedTestResults[testStep.id].response &&
                                                simulatedTestResults[testStep.id].response.status
                                            "
                                        >
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Status</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    {{
                                                    simulatedTestResults[testStep.id].response.status
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex"
                                            v-if="
                                                simulatedTestResults[testStep.id].response &&
                                                simulatedTestResults[testStep.id].response.headers !==
                                                    undefined
                                            "
                                        >
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Headers</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree
                                                        :data="
                                                            simulatedTestResults[testStep.id].response
                                                                .headers
                                                        "
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                        <div
                                            class="d-flex"
                                            v-if="
                                                simulatedTestResults[testStep.id].response &&
                                                simulatedTestResults[testStep.id].response.body !==
                                                    undefined &&
                                                simulatedTestResults[testStep.id].response.body !==
                                                    'empty_body'
                                            "
                                        >
                                            <div class="w-25 px-4 py-2 border">
                                                <strong>Body</strong>
                                            </div>
                                            <div class="w-75 px-4 py-2 border">
                                                <div class="mb-0 p-0">
                                                    <json-tree
                                                        :data="
                                                            simulatedTestResults[testStep.id].response
                                                                .body
                                                        "
                                                        :deep="1"
                                                        :show-line="false"
                                                        class="p-2"
                                                    ></json-tree>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </b-modal>
                            </td>
                        </tr>
                        <tr v-if="!testSteps.data.length">
                            <td class="text-center" colspan="6">No Results</td>
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
import Layout from '@/layouts/sessions/test-case';

export default {
    components: {
        Layout,
    },
    props: {
        session: {
            type: Object,
            required: true,
        },
        testCase: {
            type: Object,
            required: true,
        },
        testSteps: {
            type: Object,
            required: true,
        },
        simulatedTestResults: {
            type: Object,
            required: true,
        },
        testStepFirstSource: {
            type: Object,
            required: true,
        },
        isAvailableRun: {
            type: Boolean,
            required: true,
        },
    },
};
</script>
