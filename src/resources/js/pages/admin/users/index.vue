<template>
    <layout>
        <h1 class="page-title mb-5">
            <b>Users</b>
        </h1>
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
                        <icon name="search">
                    </span>
                </form>
                <div class="card-options">
                    <div class="btn-group">
                        <inertia-link
                            :href="route('admin.users.index')"
                            :class="{ active: !trashed }"
                            class="btn btn-outline-primary"
                        >
                            Active
                        </inertia-link>
                        <inertia-link
                            :href="route('admin.users.index', ['trashed'])"
                            :class="{ active: trashed }"
                            class="btn btn-outline-primary"
                        >
                            Blocked
                        </inertia-link>
                    </div>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
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
                                <a :href="'mailto:' + user.email">{{
                                    user.email
                                }}</a>
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
                            <td class="text-center text-break"></td>
                        </tr>
                        <tr v-if="!users.data.length">
                            <td class="text-center" colspan="6">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
        Layout
    },
    props: {
        users: Object,
        trashed: Boolean
    },
    data() {
        return {
            form: {
                q: null
            }
        };
    },
    methods: {
        search() {
            this.$inertia.replace(route('admin.users.index'), {
                data: this.form
            });
        }
    }
};
</script>
