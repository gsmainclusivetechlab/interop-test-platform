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
                        :href="route('admin.scenarios.create')"
                        v-if="$page.props.auth.user.can.scenarios.create"
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        {{ $t('buttons.create') }}
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
                            <th class="text-nowrap">
                                {{ $t('table.header.test-cases') }}
                            </th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(useCase, i) in scenarios.data"
                            :key="`use-case-${i}`"
                        >
                            <td class="text-break">
                                {{ useCase.name }}
                            </td>
                            <td>
                                {{ useCase.testCasesCount }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="
                                        useCase.can.update || useCase.can.delete
                                    "
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template v-slot:button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
                                    <li v-if="useCase.can.update">
                                        <inertia-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.scenarios.edit',
                                                    useCase.id
                                                )
                                            "
                                        >
                                            {{ $t('table.menu.edit') }}
                                        </inertia-link>
                                    </li>
                                    <li v-if="useCase.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.scenarios.destroy',
                                                    useCase.id
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
                                                    'table.menu.delete.modal.title',
                                                    { name: useCase.name }
                                                )
                                            "
                                        >
                                            {{ $t('table.menu.delete.title') }}
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!scenarios.data.length">
                            <td class="text-center" colspan="3">
                                {{ $t('table.no-results') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="scenarios.meta"
                :links="scenarios.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/main';

    export default {
        metaInfo() {
            return { title: this.$t('page.title') };
        },
        components: {
            Layout,
        },
        props: {
            scenarios: {
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
                this.$inertia.replace(route('admin.scenarios.index'), {
                    data: this.form,
                });
            },
        },
    };
</script>
<i18n src="@locales/pages/admin/scenarios/index.json"></i18n>
