<template>
    <layout :test-case="currentTestCase">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Versions</h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap">Version</th>
                            <th class="text-nowrap">Behavior</th>
                            <th class="text-nowrap">Public</th>
                            <th class="text-nowrap">Use Case</th>
                            <th class="text-nowrap">Test Steps</th>
                            <th class="text-nowrap">Owner</th>
                            <th class="text-nowrap">Groups</th>
                            <th class="text-nowrap">Draft</th>
                            <th class="text-nowrap"></th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(testCase, i) in testCases.data" :key="i">
                            <td class="text-break">
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
                            </td>
                            <td class="text-break">
                                {{ testCase.version }}
                            </td>
                            <td class="text-break">
                                {{
                                    collect(
                                        $page.enums.test_case_behaviors
                                    ).get(testCase.behavior)
                                }}
                            </td>
                            <td class="text-break">
                                {{ testCase.public ? 'Yes' : 'No' }}
                            </td>
                            <td class="text-break">
                                {{ testCase.useCase.name }}
                            </td>
                            <td>
                                {{
                                    testCase.testSteps
                                        ? testCase.testSteps.length
                                        : 0
                                }}
                            </td>
                            <td class="text-break">
                                {{ testCase.owner ? testCase.owner.name : '' }}
                            </td>
                            <td class="text-break">
                                {{
                                    testCase.groups ? testCase.groups.length : 0
                                }}
                            </td>
                            <td class="text-break">
                                {{ testCase.draft ? 'Yes' : 'No' }}
                            </td>
                            <td class="text-break">
                                {{
                                    testCase.id === currentTestCase.id
                                        ? '<= You are here'
                                        : ''
                                }}
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
                                                    'admin.test-cases.complete',
                                                    testCase.id
                                                )
                                            "
                                            class="dropdown-item"
                                        >
                                            Complete
                                        </inertia-link>
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
        title: 'Test Case Steps',
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
