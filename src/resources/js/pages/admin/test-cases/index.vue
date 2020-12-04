<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">Administration</div>
                    <h2 class="page-title">
                        <b>Test Cases</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form @submit.prevent="search">
                    <input-search v-model="form.q" />
                </form>
                <div class="card-options">
                    <inertia-link
                        :href="route('admin.test-cases.create')"
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        Create Test Case
                    </inertia-link>
                    <inertia-link
                        :href="route('admin.test-cases.import')"
                        v-if="$page.auth.user.can.test_cases.create"
                        class="btn btn-primary ml-2"
                    >
                        <icon name="upload" />
                        Import Test Case
                    </inertia-link>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap text-center">Version</th>
                            <th class="text-nowrap text-center">Behavior</th>
                            <th class="text-nowrap text-center">Public</th>
                            <th class="text-nowrap text-center">Use Case</th>
                            <th class="text-nowrap text-center">Test Steps</th>
                            <th class="text-nowrap text-center">Owner</th>
                            <th class="text-nowrap text-center">Groups</th>
                            <th class="text-nowrap text-center w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(testCase, i) in testCases.data" :key="i">
                            <td class="text-break">
                                <div v-if="testCase.can.update">
                                    <inertia-link
                                        :href="
                                            route(
                                                'admin.test-cases.info.show',
                                                testCase.id
                                            )
                                        "
                                    >
                                        {{ testCase.name }}
                                    </inertia-link>
                                </div>
                                <div v-else>
                                    {{ testCase.name }}
                                </div>
                            </td>
                            <td class="text-center text-break">
                                <div v-if="testCase.can.update">
                                    <inertia-link
                                        :href="
                                            route(
                                                'admin.test-cases.versions.index',
                                                testCase.id
                                            )
                                        "
                                    >
                                        {{ testCase.version }}
                                    </inertia-link>
                                </div>
                                <div v-else>
                                    {{ testCase.version }}
                                </div>
                            </td>
                            <td class="text-center text-break">
                                {{
                                    collect(
                                        $page.enums.test_case_behaviors
                                    ).get(testCase.behavior)
                                }}
                            </td>
                            <td class="text-center text-break">
                                <label class="form-check form-switch">
                                    <input
                                        v-if="testCase.can.togglePublic"
                                        class="form-check-input"
                                        type="checkbox"
                                        :checked="testCase.public"
                                        @change.prevent="
                                            $inertia.put(
                                                route(
                                                    'admin.test-cases.toggle-public',
                                                    testCase.id
                                                )
                                            )
                                        "
                                    />
                                    <input
                                        v-else
                                        class="form-check-input"
                                        type="checkbox"
                                        disabled
                                        :checked="testCase.public"
                                    />
                                </label>
                            </td>
                            <td class="text-center text-break">
                                {{ testCase.useCase.name }}
                            </td>
                            <td class="text-center">
                                <inertia-link
                                    v-if="testCase.can.update"
                                    :href="
                                        route(
                                            'admin.test-cases.test-steps.index',
                                            testCase.id
                                        )
                                    "
                                >
                                    {{
                                        testCase.testSteps
                                            ? testCase.testSteps.length
                                            : 0
                                    }}
                                </inertia-link>

                                <span v-else>
                                    {{
                                        testCase.testSteps
                                            ? testCase.testSteps.length
                                            : 0
                                    }}
                                </span>
                            </td>
                            <td class="text-center text-break">
                                {{ testCase.owner ? testCase.owner.name : '' }}
                            </td>
                            <td class="text-center text-break">
                                <div v-if="testCase.can.update">
                                    <inertia-link
                                        :href="
                                            route(
                                                'admin.test-cases.groups.index',
                                                testCase.id
                                            )
                                        "
                                    >
                                        {{
                                            testCase.groups
                                                ? testCase.groups.length
                                                : 0
                                        }}
                                    </inertia-link>
                                </div>
                                <div v-else>
                                    {{
                                        testCase.groups
                                            ? testCase.groups.length
                                            : 0
                                    }}
                                </div>
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="testCase.can.delete"
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template v-slot:button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
                                    <li v-if="testCase.can.update">
                                        <inertia-link
                                            :href="
                                                route(
                                                    'admin.test-cases.import-version',
                                                    testCase.id
                                                )
                                            "
                                            class="dropdown-item"
                                        >
                                            Import New Version
                                        </inertia-link>
                                    </li>
                                    <li v-if="testCase.can.update">
                                        <div v-if="testCase.draft">
                                            <inertia-link
                                                :href="route('admin.test-cases.info.edit', testCase.id)"
                                                class="dropdown-item"
                                            >
                                                Edit
                                            </inertia-link>
                                        </div>
                                        <div v-else>
                                            <confirm-link
                                                :href="route('admin.test-cases.info.edit', testCase.id)"
                                                :confirm-text="'This test case is out draft. A new version will be created'"
                                                class="dropdown-item"
                                            >
                                                Edit
                                            </confirm-link>
                                        </div>
                                    </li>
                                    <li v-if="testCase.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.test-cases.destroy',
                                                    testCase.id
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="
                                                `Are you sure you want to delete all versions of ${testCase.name}?`
                                            "
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!testCases.data.length">
                            <td class="text-center" colspan="8">No Results</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="testCases.meta"
                :links="testCases.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo: {
        title: 'Test Cases',
    },
    components: {
        Layout,
    },
    props: {
        testCases: {
            type: Object,
            required: true,
        },
        filter: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            form: {
                q: this.filter.q,
            },
        };
    },
    methods: {
        search() {
            this.$inertia.replace(route('admin.test-cases.index'), {
                data: this.form,
            });
        },
    },
};
</script>
