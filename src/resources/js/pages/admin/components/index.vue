<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">
                        Administration
                    </div>
                    <h2 class="page-title">
                        <b>Components</b>
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
                        :href="route('admin.components.create')"
                        v-if="$page.auth.user.can.components.create"
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        New Component
                    </inertia-link>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap">#</th>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap">Base URL</th>
                            <th class="text-nowrap">Connections</th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="component in components.data">
                            <td class="text-break">
                                {{ component.position }}
                            </td>
                            <td class="text-break">
                                {{ component.name }}
                            </td>
                            <td class="text-break">
                                {{ component.base_url }}
                            </td>
                            <td>
                                {{
                                    component.connections
                                        ? component.connections.length
                                        : 0
                                }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="
                                        component.can.update ||
                                        component.can.delete
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
                                    <li v-if="component.can.update">
                                        <inertia-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.components.edit',
                                                    component.id
                                                )
                                            "
                                        >
                                            Edit
                                        </inertia-link>
                                    </li>
                                    <li v-if="component.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.components.destroy',
                                                    component.id
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="`Are you sure you want to delete ${component.name}?`"
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!components.data.length">
                            <td class="text-center" colspan="5">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="components.meta"
                :links="components.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo: {
        title: 'Components',
    },
    components: {
        Layout,
    },
    props: {
        components: {
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
            this.$inertia.replace(route('admin.components.index'), {
                data: this.form,
            });
        },
    },
};
</script>
