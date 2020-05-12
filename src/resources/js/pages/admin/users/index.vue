<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">
                        Administration
                    </div>
                    <h2 class="page-title">
                        Users
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
                <div class="card-options">
                    <div class="btn-group">
                        <inertia-link
                            :href="route('admin.users.index')"
                            :class="{ active: !filter.trashed }"
                            class="btn btn-outline-primary"
                        >
                            Active
                        </inertia-link>
                        <inertia-link
                            :href="route('admin.users.index', ['trashed'])"
                            :class="{ active: filter.trashed }"
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
                                <span v-if="user.trashed">{{ user.name }}</span>
                                <a href="#" v-else>{{ user.name }}</a>
                            </td>
                            <td class="text-break">
                                <a :href="'mailto:' + user.email">
                                    {{ user.email }}
                                </a>
                            </td>
                            <td class="text-break">
                                {{ user.company }}
                            </td>
                            <td class="text-break">
                                {{ user.role_name }}
                            </td>
                            <td class="text-break">
                                {{ user.email_verified_at }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="
                                        user.can.promoteAdmin ||
                                            user.can.relegateAdmin ||
                                            user.can.delete ||
                                            user.can.restore
                                    "
                                    no-caret
                                    right
                                    toggle-class="align-text-top"
                                    variant="secondary"
                                    boundary="window"
                                >
                                    <template v-slot:button-content>
                                        <icon name="edit" class="m-0"></icon>
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
                                            :confirm-text="
                                                `Are you sure you want to unblock ${user.name}?`
                                            "
                                        >
                                            Unblock
                                        </inertia-link>
                                    </li>
                                    <li
                                        v-if="
                                            !user.trashed &&
                                                user.can.promoteAdmin
                                        "
                                    >
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.users.promote-admin',
                                                    user.id
                                                )
                                            "
                                            method="post"
                                            :confirm-title="
                                                'Confirm promote admin'
                                            "
                                            :confirm-text="
                                                `Are you sure you want to promote ${user.name} to admin?`
                                            "
                                        >
                                            Promote admin
                                        </confirm-link>
                                    </li>
                                    <li
                                        v-if="
                                            !user.trashed &&
                                                user.can.relegateAdmin
                                        "
                                    >
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.users.relegate-admin',
                                                    user.id
                                                )
                                            "
                                            method="post"
                                            :confirm-title="
                                                'Confirm relegate admin'
                                            "
                                            :confirm-text="
                                                `Are you sure you want to relegate ${user.name} from admin?`
                                            "
                                        >
                                            Relegate admin
                                        </confirm-link>
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
                                            :confirm-text="
                                                `Are you sure you want to block ${user.name}?`
                                            "
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
                                            :confirm-text="
                                                `Are you sure you want to verify ${user.name}?`
                                            "
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
                                            :confirm-text="
                                                `Are you sure you want to delete ${user.name}?`
                                            "
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!users.data.length">
                            <td class="text-center" colspan="6">
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
import Layout from '@/layouts/main';

export default {
    metaInfo: {
        title: 'Users'
    },
    components: {
        Layout
    },
    props: {
        users: {
            type: Object,
            required: true
        },
        filter: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            form: {
                q: this.filter.q
            }
        };
    },
    methods: {
        search() {
            this.$inertia.replace(
                route(
                    'admin.users.index',
                    this.filter.trashed ? ['trashed'] : []
                ),
                {
                    data: this.form
                }
            );
        }
    }
};
</script>
