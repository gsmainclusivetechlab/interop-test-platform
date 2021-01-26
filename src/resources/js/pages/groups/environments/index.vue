<template>
    <layout :group="group">
        <div class="card">
            <div class="card-header">
                <form @submit.prevent="search">
                    <input-search v-model="form.q" />
                </form>
                <div class="card-options">
                    <inertia-link
                        :href="route('groups.environments.create', group.id)"
                        v-if="group.can.admin"
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        New Environment
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
                            <th class="text-nowrap w-auto">Variables</th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="environment in environments.data">
                            <td class="text-break">
                                {{ environment.name }}
                            </td>
                            <td class="text-break">
                                {{ Object.keys(environment.variables).length }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="
                                        environment.can.update ||
                                        environment.can.delete
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
                                    <li v-if="environment.can.update">
                                        <inertia-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'groups.environments.edit',
                                                    [group.id, environment.id]
                                                )
                                            "
                                        >
                                            Edit
                                        </inertia-link>
                                    </li>
                                    <li v-if="environment.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'groups.environments.destroy',
                                                    [group.id, environment.id]
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="`Are you sure you want to delete ${environment.name}?`"
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!environments.data.length">
                            <td class="text-center" colspan="5">No Results</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="environments.meta"
                :links="environments.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/groups/main';

export default {
    components: {
        Layout,
    },
    props: {
        group: {
            type: Object,
            required: true,
        },
        environments: {
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
            this.$inertia.replace(
                route('groups.environments.index', this.group.id),
                {
                    data: this.form,
                }
            );
        },
    },
};
</script>
