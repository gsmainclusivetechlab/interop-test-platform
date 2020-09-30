<template>
    <layout :group="group">
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
                <div class="card-options">
                    <inertia-link
                        :href="route('groups.users.create', group.id)"
                        v-if="group.can.admin"
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        New Member
                    </inertia-link>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                    <tr>
                        <th class="text-nowrap w-auto">Name</th>
                        <th class="text-nowrap w-auto">Email</th>
                        <th class="text-nowrap w-auto">Company</th>
                        <th class="text-nowrap w-auto">Admin</th>
                        <th class="text-nowrap w-1"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="user in users.data">
                        <td class="text-break">
                            {{ user.name }}
                        </td>
                        <td class="text-break">
                            <a :href="`mailto:${user.email}`">
                                {{ user.email }}
                            </a>
                        </td>
                        <td class="text-break">
                            {{ user.company }}
                        </td>
                        <td class="text-break">
                            <label class="form-check form-switch">
                                <input
                                    v-if="user.can.toggleAdmin"
                                    class="form-check-input"
                                    type="checkbox"
                                    :checked="user.admin"
                                    @change.prevent="
                                                    $inertia.put(
                                                        route(
                                                            'groups.users.toggle-admin',
                                                            [group.id, user.id]
                                                        )
                                                    )
                                                "
                                />
                                <input
                                    v-else
                                    class="form-check-input"
                                    type="checkbox"
                                    disabled
                                    :checked="user.admin"
                                />
                            </label>
                        </td>
                        <td class="text-center text-break">
                            <b-dropdown
                                v-if="user.can.delete"
                                no-caret
                                right
                                toggle-class="align-items-center text-muted"
                                variant="link"
                                boundary="window"
                            >
                                <template v-slot:button-content>
                                    <icon
                                        name="dots-vertical"
                                    ></icon>
                                </template>
                                <li v-if="user.can.delete">
                                    <confirm-link
                                        class="dropdown-item"
                                        :href="
                                                        route(
                                                            'groups.users.destroy',
                                                            [group.id, user.id]
                                                        )
                                                    "
                                        method="delete"
                                        :confirm-title="'Confirm delete'"
                                        :confirm-text="`Are you sure you want to delete ${user.name}?`"
                                    >
                                        Delete
                                    </confirm-link>
                                </li>
                            </b-dropdown>
                        </td>
                    </tr>
                    <tr v-if="!users.data.length">
                        <td class="text-center" colspan="5">
                            No Results
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="users.meta"
                :links="users.links"
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
        users: {
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
            this.$inertia.replace(route('groups.users.index', this.group.id), {
                data: this.form,
            });
        },
    },
};
</script>
