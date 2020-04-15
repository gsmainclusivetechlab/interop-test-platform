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
                <table class="table table-striped table-vcenter table-hover card-table">
                    <thead class="thead-light">
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
                        <tr v-for="user in users.data" :key="user.id">
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
                                    v-if="user.can.promoteAdmin || user.can.relegateAdmin || user.can.delete || user.can.restore"
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
                                            :href="route('admin.users.restore', user)"
                                        >
                                            Unblock
                                        </inertia-link>
                                    </li>
                                    <li v-if="!user.trashed && user.can.delete">
                                        <inertia-link
                                            class="dropdown-item"
                                            :href="route('admin.users.destroy', user)"
                                        >
                                            Block
                                        </inertia-link>
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
            <pagination :meta="users.meta" :links="users.links" class="card-footer" />
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/app.vue';

    export default {
        metaInfo: {
            title: 'Users'
        },
        components: {
            Layout,
        },
        props: {
            users: Object,
            filter: Object,
        },
        data() {
            return {
                form: {
                    q: this.filter.q,
                }
            };
        },
        methods: {
            search() {
                this.$inertia.replace(route('admin.users.index', (this.filter.trashed) ? ['trashed'] : []), {
                    data: this.form
                });
            }
        }
    };
</script>
