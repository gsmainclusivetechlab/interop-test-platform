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
                        :href="route('admin.groups.create')"
                        v-if="$page.props.auth.user.can.groups.create"
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        {{ $t('buttons.new-group') }}
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
                                {{ $t('table.header.email-filter') }}
                            </th>
                            <th class="text-nowrap">
                                {{ $t('table.header.users') }}
                            </th>
                            <th class="text-nowrap">
                                {{ $t('table.header.sessions') }}
                            </th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(group, i) in groups.data" :key="i">
                            <td class="text-break">
                                <inertia-link
                                    :href="route('groups.show', group.id)"
                                >
                                    {{ group.name }}
                                </inertia-link>
                            </td>
                            <td class="text-break">
                                {{ group.domain }}
                            </td>
                            <td>
                                {{ group.users ? group.users.length : 0 }}
                            </td>
                            <td>
                                {{ group.sessions ? group.sessions.length : 0 }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="group.can.update || group.can.delete"
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template v-slot:button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
                                    <li v-if="group.can.update">
                                        <inertia-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.groups.edit',
                                                    group.id
                                                )
                                            "
                                        >
                                            {{ $t('table.menu.edit') }}
                                        </inertia-link>
                                    </li>
                                    <li v-if="group.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.groups.destroy',
                                                    group.id
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
                                                    'table.menu.delete.modal.text',
                                                    { name: group.name }
                                                )
                                            "
                                        >
                                            {{ $t('table.menu.delete.title') }}
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!groups.data.length">
                            <td class="text-center" colspan="5">
                                {{ $t('table.no-results') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="groups.meta"
                :links="groups.links"
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
        groups: {
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
            this.$inertia.replace(route('admin.groups.index'), {
                data: this.form,
            });
        },
    },
};
</script>
<i18n src="@locales/pages/admin/groups/index.json"></i18n>
