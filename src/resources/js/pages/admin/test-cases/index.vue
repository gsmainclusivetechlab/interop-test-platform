<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">
                        {{ $t('special-locales.administration') }}
                    </div>
                    <h2 class="page-title">
                        <b>{{ $t('page.title') }}</b>
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
                        {{ $t('buttons.create') }}
                    </inertia-link>
                    <inertia-link
                        :href="route('admin.test-cases.import')"
                        v-if="$page.props.auth.user.can.test_cases.create"
                        class="btn btn-primary ml-2"
                    >
                        <icon name="file-import" />
                        {{ $t('buttons.import') }}
                    </inertia-link>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap">
                                {{ $t('table.header.name') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.version') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.behavior') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.public') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.use-case') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.test-steps') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.owner') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.groups') }}
                            </th>
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
                                        $page.props.enums.test_case_behaviors
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
                                            {{ $t('table.menu.import') }}
                                        </inertia-link>
                                    </li>
                                    <li v-if="testCase.can.update">
                                        <template v-if="testCase.draft">
                                            <inertia-link
                                                :href="
                                                    route(
                                                        'admin.test-cases.info.edit',
                                                        testCase.id
                                                    )
                                                "
                                                class="dropdown-item"
                                            >
                                                {{
                                                    $t('table.menu.edit.title')
                                                }}
                                            </inertia-link>
                                        </template>
                                        <template v-else>
                                            <confirm-link
                                                :href="
                                                    route(
                                                        'admin.test-cases.info.edit',
                                                        testCase.id
                                                    )
                                                "
                                                :confirm-text="
                                                    $t(
                                                        'table.menu.edit.modal.text'
                                                    )
                                                "
                                                class="dropdown-item"
                                            >
                                                {{
                                                    $t('table.menu.edit.title')
                                                }}
                                            </confirm-link>
                                        </template>
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
                                            :confirm-title="
                                                $t(
                                                    'table.menu.delete.modal.title'
                                                )
                                            "
                                            :confirm-text="`${$t(
                                                'table.menu.delete.modal.text'
                                            )} ${testCase.name}?`"
                                        >
                                            {{ $t('table.menu.delete.title') }}
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!testCases.data.length">
                            <td class="text-center" colspan="8">
                                {{ $t('table.content.no-results') }}
                            </td>
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
    metaInfo() {
        return {
            title: this.$t('page.title'),
        };
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
<i18n src="@locales/pages/admin/test-cases/index.json"></i18n>
