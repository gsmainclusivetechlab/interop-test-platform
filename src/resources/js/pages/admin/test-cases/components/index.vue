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
                                'admin.test-cases.components.create',
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
                                {{ $t('table.header.name') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.slug') }}
                            </th>
                            <th class="text-nowrap text-center">
                                {{ $t('table.header.versions') }}
                            </th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(component, i) in components.data" :key="i">
                            <td class="align-middle text-center">
                                {{ component.name }}
                            </td>
                            <td class="align-middle text-center">
                                {{ component.slug }}
                            </td>
                            <td class="align-middle text-center">
                                {{
                                    component.versions
                                        ? component.versions.join(', ')
                                        : ''
                                }}
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
                                                    'admin.test-cases.components.edit',
                                                    [testCase.id, component.id]
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
                                                    'admin.test-cases.components.destroy',
                                                    [testCase.id, component.id]
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
                        <tr v-if="!components.data.length">
                            <td class="text-center" colspan="6">
                                {{ $t('table.no-results') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
        components: {
            type: Object,
            required: true,
        },
    },
};
</script>
<i18n src="@locales/pages/admin/test-cases/components/index.json"></i18n>
