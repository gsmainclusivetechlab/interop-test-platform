<template>
    <layout :test-case="testCase">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Test steps</h2>
                <div class="card-options">
                    <inertia-link
                        v-if="testCase.draft"
                        :href="
                            route(
                                'admin.test-cases.test-steps.create',
                                testCase.id
                            )
                        "
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        <span>Create Test Step</span>
                    </inertia-link>
                    <confirm-link
                        v-else
                        :href="route('admin.test-cases.info.edit', testCase.id)"
                        :confirm-text="'The latest version of this test case published, so a new draft version will be created to store your changes. You may publish or discard it anytime.'"
                        class="btn btn-primary"
                    >
                        <icon name="refresh" />
                        <span>Make Test Case Draft</span>
                    </confirm-link>
                </div>
            </div>
            <div class="card-body table-responsive p-0 mb-0">
                <table class="table table-striped card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center">Number</th>
                            <th class="text-nowrap text-center">Method</th>
                            <th class="text-nowrap text-center">Endpoint</th>
                            <th class="text-nowrap text-center">Source</th>
                            <th class="text-nowrap text-center">Target</th>
                            <th class="text-nowrap text-center">
                                Expected Data
                            </th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(testStep, i) in testSteps.data" :key="i">
                            <td class="align-middle text-center">
                                {{ testStep.position }}
                            </td>
                            <td class="align-middle text-center">
                                {{ testStep.method }}
                            </td>
                            <td class="align-middle text-center">
                                {{ testStep.path }}
                            </td>
                            <td class="align-middle text-center">
                                {{
                                    testStep.source ? testStep.source.name : ''
                                }}
                            </td>
                            <td class="align-middle text-center">
                                {{
                                    testStep.target ? testStep.target.name : ''
                                }}
                            </td>
                            <td class="align-middle text-center">
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        v-if="testStep.request"
                                        v-b-modal="
                                            `modal-request-${testStep.id}`
                                        "
                                    >
                                        Request
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        v-if="testStep.response"
                                        v-b-modal="
                                            `modal-response-${testStep.id}`
                                        "
                                    >
                                        Response
                                    </button>
                                </div>
                            </td>
                            <td class="text-center">
                                <b-dropdown
                                    v-if="testCase.draft"
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template #button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
                                    <li>
                                        <inertia-link
                                            :href="
                                                route(
                                                    'admin.test-cases.test-steps.edit',
                                                    [testCase.id, testStep.id]
                                                )
                                            "
                                            class="dropdown-item"
                                        >
                                            Edit
                                        </inertia-link>
                                    </li>
                                    <li>
                                        <confirm-link
                                            :href="
                                                route(
                                                    'admin.test-cases.test-steps.destroy',
                                                    [testCase.id, testStep.id]
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="'Are you sure, you want to delete this test step?'"
                                            class="dropdown-item"
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!testSteps.data.length">
                            <td class="text-center" colspan="6">No Results</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <template v-for="testStep in testSteps.data">
            <b-modal
                :id="`modal-request-${testStep.id}`"
                size="lg"
                centered
                hide-footer
                hide-header-close
                title="Request"
                :key="`modal-request-${testStep.id}`"
            >
                <clipboard-json-to-curl :request="testStep.request">
                </clipboard-json-to-curl>
                <div class="border">
                    <div
                        class="d-flex"
                        v-if="testStep.request && testStep.request.uri"
                    >
                        <div class="w-25 px-4 py-2 border">
                            <strong>Endpoint</strong>
                        </div>
                        <div class="w-75 px-4 py-2 border">
                            <div class="mb-0 p-0">
                                {{ testStep.request.uri }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex"
                        v-if="testStep.request && testStep.request.method"
                    >
                        <div class="w-25 px-4 py-2 border">
                            <strong>Method</strong>
                        </div>
                        <div class="w-75 px-4 py-2 border">
                            <div class="mb-0 p-0">
                                {{ testStep.request.method }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex"
                        v-if="
                            testStep.request &&
                            testStep.request.headers !== undefined
                        "
                    >
                        <div class="w-25 px-4 py-2 border">
                            <strong>Headers</strong>
                        </div>
                        <div class="w-75 px-4 py-2 border">
                            <div class="mb-0 p-0">
                                <json-tree
                                    :data="testStep.request.headers"
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
                            testStep.request &&
                            testStep.request.body !== undefined
                        "
                    >
                        <div class="w-25 px-4 py-2 border">
                            <strong>Body</strong>
                        </div>
                        <div class="w-75 px-4 py-2 border">
                            <div class="mb-0 p-0">
                                <json-tree
                                    :data="testStep.request.body"
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
                :key="`modal-response-${testStep.id}`"
            >
                <div class="border">
                    <div
                        class="d-flex"
                        v-if="testStep.response && testStep.response.status"
                    >
                        <div class="w-25 px-4 py-2 border">
                            <strong>Status</strong>
                        </div>
                        <div class="w-75 px-4 py-2 border">
                            <div class="mb-0 p-0">
                                {{ testStep.response.status }}
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex"
                        v-if="
                            testStep.response &&
                            testStep.response.headers !== undefined
                        "
                    >
                        <div class="w-25 px-4 py-2 border">
                            <strong>Headers</strong>
                        </div>
                        <div class="w-75 px-4 py-2 border">
                            <div class="mb-0 p-0">
                                <json-tree
                                    :data="testStep.response.headers"
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
                            testStep.response &&
                            testStep.response.body !== undefined
                        "
                    >
                        <div class="w-25 px-4 py-2 border">
                            <strong>Body</strong>
                        </div>
                        <div class="w-75 px-4 py-2 border">
                            <div class="mb-0 p-0">
                                <json-tree
                                    :data="testStep.response.body"
                                    :deep="1"
                                    :show-line="false"
                                    class="p-2"
                                ></json-tree>
                            </div>
                        </div>
                    </div>
                </div>
            </b-modal>
        </template>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';

export default {
    metaInfo: {
        title: 'Test Case Steps',
    },
    components: {
        Layout,
    },
    props: {
        testCase: {
            type: Object,
            required: true,
        },
        testSteps: {
            type: Object,
            required: true,
        },
    },
};
</script>
