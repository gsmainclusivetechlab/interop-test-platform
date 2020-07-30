<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <h2 class="page-title">
                        <b>Groups</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form class="input-icon" @submit.prevent="search">
                    <input
                        v-model="form.q"
                        type="text"
                        class="form-control"
                        placeholder="Search..."
                    />
                    <span class="input-icon-addon">
                        <icon name="search" />
                    </span>
                </form>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap w-25">Name</th>
                            <th class="text-nowrap">Domain</th>
                            <th class="text-nowrap">Users</th>
                            <th class="text-nowrap">Sessions</th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="group in groups.data">
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
                                            Edit
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
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="`Are you sure you want to delete ${group.name}?`"
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!groups.data.length">
                            <td class="text-center" colspan="4">
                                No Results
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
    metaInfo: {
        title: 'Groups',
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
            this.$inertia.replace(route('groups.index'), {
                data: this.form,
            });
        },
    },
};
</script>
