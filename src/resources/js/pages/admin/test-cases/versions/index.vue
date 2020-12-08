<template>
    <layout :test-case="currentTestCase">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Versions</h2>
                <div class="card-options">
                    <inertia-link
                        :href="
                            route(
                                'admin.test-cases.import-version',
                                currentTestCase.id
                            )
                        "
                        class="btn btn-primary ml-2"
                    >
                        <icon name="upload" />
                        Import New Version
                    </inertia-link>
                </div>
            </div>
            <div class="card-body table-responsive p-0 mb-0">
                <table class="table table-striped card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center">Version</th>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap text-center">Behavior</th>
                            <th class="text-nowrap text-center">Public</th>
                            <th class="text-nowrap">Use Case</th>
                            <th class="text-nowrap text-center">Test Steps</th>
                            <th class="text-nowrap text-center">Owner</th>
                            <th class="text-nowrap text-center">Groups</th>
                            <th class="text-nowrap text-center">Status</th>
                            <th class="w-1 text-nowrap text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(testCase, i) in testCases.data" :key="i">
                            <td class="text-center">
                                {{ testCase.version }}
                            </td>
                            <td class="text-break">
                                {{ testCase.name }}
                            </td>
                            <td class="text-center">
                                {{
                                    collect(
                                        $page.enums.test_case_behaviors
                                    ).get(testCase.behavior)
                                }}
                            </td>
                            <td class="text-center">
                                {{ testCase.public ? 'Yes' : 'No' }}
                            </td>
                            <td class="text-break">
                                {{ testCase.useCase.name }}
                            </td>
                            <td class="text-center">
                                {{
                                    testCase.testSteps
                                        ? testCase.testSteps.length
                                        : 0
                                }}
                            </td>
                            <td class="text-break text-center">
                                {{ testCase.owner ? testCase.owner.name : '' }}
                            </td>
                            <td class="text-center">
                                {{
                                    testCase.groups ? testCase.groups.length : 0
                                }}
                            </td>
                            <td class="text-center">
                                {{ testCase.draft ? 'Draft' : 'Publish' }}
                            </td>
                            <td class="text-center">
                                <b-dropdown
                                    v-if="testCase.can.delete"
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template #button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
                                    <li
                                        v-if="
                                            $page.auth.user.can.test_cases
                                                .create && testCase.draft
                                        "
                                    >
                                        <inertia-link
                                            :href="
                                                route(
                                                    'admin.test-cases.versions.publish',
                                                    testCase.id
                                                )
                                            "
                                            class="dropdown-item"
                                        >
                                            Publish
                                        </inertia-link>
                                    </li>
                                    <li
                                        v-if="
                                            $page.auth.user.can.test_cases
                                                .create && testCase.draft
                                        "
                                    >
                                        <confirm-link
                                            method="delete"
                                            :href="
                                                route(
                                                    'admin.test-cases.versions.discard',
                                                    testCase.id
                                                )
                                            "
                                            class="dropdown-item"
                                            :confirm-title="'Are you sure?'"
                                            :confirm-text="'This test step version will be discarded'"
                                        >
                                            Discard
                                        </confirm-link>
                                    </li>
                                    <li
                                        v-if="
                                            $page.auth.user.can.test_cases
                                                .create
                                        "
                                    >
                                        <a
                                            :href="
                                                route(
                                                    'admin.test-cases.export',
                                                    testCase.id
                                                )
                                            "
                                            class="dropdown-item"
                                        >
                                            Export to yaml
                                        </a>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!testCases.data.length">
                            <td class="text-center" colspan="6">No Results</td>
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
import Layout from '@/layouts/test-cases/main';

export default {
    metaInfo: {
        title: 'Test Case Versions',
    },
    components: {
        Layout,
    },
    props: {
        currentTestCase: {
            type: Object,
            required: true,
        },
        testCases: {
            type: Object,
            required: true,
        },
    },
};
</script>
