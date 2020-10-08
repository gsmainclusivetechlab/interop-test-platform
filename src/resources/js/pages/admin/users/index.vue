<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">Administration</div>
                    <h2 class="page-title">
                        <b>Users</b>
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
                    <div class="btn-group">
                        <inertia-link
                            :href="route('admin.users.index')"
                            :class="{ active: !filter.trash }"
                            class="btn btn-outline-primary"
                        >
                            Active
                        </inertia-link>
                        <inertia-link
                            :href="route('admin.users.index', ['trash'])"
                            :class="{ active: filter.trash }"
                            class="btn btn-outline-primary"
                        >
                            Blocked
                        </inertia-link>
                    </div>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap w-25">Name</th>
                            <th class="text-nowrap w-25">Email</th>
                            <th class="text-nowrap w-25">Company</th>
                            <th class="text-nowrap w-auto">Role</th>
                            <th class="text-nowrap w-auto">Verified</th>
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
                                <b-dropdown
                                    no-caret
                                    right
                                    size="sm"
                                    variant="outline-primary"
                                    toggle-class="text-uppercase"
                                    boundary="window"
                                    menu-class="mt-1"
                                    v-if="!user.trashed && user.can.promoteRole"
                                >
                                    <template v-slot:button-content>
                                        {{
                                            collect($page.enums.user_roles).get(
                                                user.role
                                            )
                                        }}
                                    </template>
                                    <li
                                        v-for="(role_name, role) in collect(
                                            $page.enums.user_roles
                                        )
                                            .except(['superadmin'])
                                            .all()"
                                    >
                                        <inertia-link
                                            class="dropdown-item"
                                            method="put"
                                            :href="
                                                route(
                                                    'admin.users.promote-role',
                                                    [user.id, role]
                                                )
                                            "
                                            v-bind:class="{
                                                active: user.role === role,
                                            }"
                                        >
                                            {{ role_name }}
                                        </inertia-link>
                                    </li>
                                </b-dropdown>
                                <button
                                    v-else
                                    class="btn btn-sm btn-outline-primary text-uppercase"
                                    disabled
                                >
                                    {{
                                        collect($page.enums.user_roles).get(
                                            user.role
                                        )
                                    }}
                                </button>
                            </td>
                            <td class="text-break">
                                {{ user.email_verified_at }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="
                                        user.can.delete ||
                                        user.can.restore ||
                                        user.can.verify
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
                                    <li v-if="user.trashed && user.can.restore">
                                        <inertia-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.users.restore',
                                                    user.id
                                                )
                                            "
                                            method="post"
                                            :confirm-title="'Confirm unblock'"
                                            :confirm-text="`Are you sure you want to unblock ${user.name}?`"
                                        >
                                            Unblock
                                        </inertia-link>
                                    </li>
                                    <li v-if="!user.trashed && user.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.users.destroy',
                                                    user.id
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="'Confirm block'"
                                            :confirm-text="`Are you sure you want to block ${user.name}?`"
                                        >
                                            Block
                                        </confirm-link>
                                    </li>
                                    <li v-if="!user.trashed && user.can.verify">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.users.verify',
                                                    user.id
                                                )
                                            "
                                            method="post"
                                            :confirm-title="'Confirm verify'"
                                            :confirm-text="`Are you sure you want to verify ${user.name}?`"
                                        >
                                            Verify
                                        </confirm-link>
                                    </li>
                                    <li v-if="user.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.users.force-destroy',
                                                    user.id
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
                            <td class="text-center" colspan="6">No Results</td>
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
import Layout from '@/layouts/main';

export default {
    metaInfo: {
        title: 'Users',
    },
    components: {
        Layout,
    },
    props: {
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
            this.$inertia.replace(
                route('admin.users.index', this.filter.trash ? ['trash'] : []),
                {
                    data: this.form,
                }
            );
        },
    },
};
</script>
