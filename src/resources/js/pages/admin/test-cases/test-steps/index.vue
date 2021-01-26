<template>
    <layout :test-case="testCase">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">{{ $t('card.title') }}</h2>
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
                        <span>{{ $t('buttons.create') }}</span>
                    </inertia-link>
                    <confirm-link
                        v-else
                        :href="route('admin.test-cases.info.edit', testCase.id)"
                        :confirm-text="$t('buttons.make-draft.modal.text')"
                        class="btn btn-primary"
                    >
                        <icon name="refresh" />
                        <span>{{ $t('buttons.make-draft.title') }}</span>
                    </confirm-link>
                </div>
            </div>
            <div class="card-body table-responsive p-0 mb-0">
                <table class="table table-striped card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.number') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.method') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.endpoint') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.source') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.target') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.expected-data') }}
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
                                        {{ $t('table.buttons.request') }}
                                    </button>
                                    <button
                                        type="button"
                                        class="btn btn-secondary"
                                        v-if="testStep.response"
                                        v-b-modal="
                                            `modal-response-${testStep.id}`
                                        "
                                    >
                                        {{ $t('table.buttons.response') }}
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
                                            {{ $t('table.menu.edit') }}
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
                                            :confirm-title="
                                                $t(
                                                    'table.menu.delete.modal.title'
                                                )
                                            "
                                            :confirm-text="
                                                $t(
                                                    'table.menu.delete.modal.title'
                                                )
                                            "
                                            class="dropdown-item"
                                        >
                                            {{ $t('table.menu.delete.title') }}
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!testSteps.data.length">
                            <td class="text-center" colspan="6">
                                {{ $t('table.no-results') }}
                            </td>
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
                :title="$t('modals.request.title')"
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
                            <strong>{{
                                $t('modals.request.table.endpoint')
                            }}</strong>
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
                            <strong>{{
                                $t('modals.request.table.method')
                            }}</strong>
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
                            <strong>{{
                                $t('modals.request.table.headers')
                            }}</strong>
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
                            testStep.request.body !== undefined &&
                            testStep.request.body !== 'empty_body'
                        "
                    >
                        <div class="w-25 px-4 py-2 border">
                            <strong>{{
                                $t('modals.request.table.body')
                            }}</strong>
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
                :title="$t('modals.response.title')"
                :key="`modal-response-${testStep.id}`"
            >
                <div class="border">
                    <div
                        class="d-flex"
                        v-if="testStep.response && testStep.response.status"
                    >
                        <div class="w-25 px-4 py-2 border">
                            <strong>{{
                                $t('modals.response.table.status')
                            }}</strong>
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
                            <strong>{{
                                $t('modals.response.table.headers')
                            }}</strong>
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
                            testStep.response.body !== undefined &&
                            testStep.response.body !== 'empty_body'
                        "
                    >
                        <div class="w-25 px-4 py-2 border">
                            <strong>{{
                                $t('modals.response.table.body')
                            }}</strong>
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
    metaInfo() {
        return {
            title: `${this.testCase.name} - ${this.$t('card.title')}`,
        };
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
<i18n src="@locales/pages/admin/test-cases/test-steps/index.json"></i18n>
