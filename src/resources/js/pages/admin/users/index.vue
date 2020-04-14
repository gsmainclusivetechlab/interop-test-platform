<template>
    <layout>
        <h1 class="page-title mb-5">
            <b>Users</b>
        </h1>
        <div class="card">
            <div class="card-header">
                <form class="input-icon" @submit.prevent="search">
                    <input v-model="form.q" type="text" class="form-control" placeholder="Search...">
                    <span class="input-icon-addon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z"></path>
                            <circle cx="10" cy="10" r="7"></circle>
                            <line x1="21" y1="21" x2="15" y2="15"></line>
                        </svg>
                    </span>
                </form>
                <div class="card-options">
                    <div class="btn-group">
                        <inertia-link :href="route('admin.users.index')" class="btn btn-outline-primary" v-bind:class="{'active': !this.filter.trashed}">
                            Active
                        </inertia-link>
                        <inertia-link :href="route('admin.users.index', ['trashed'])" class="btn btn-outline-primary" v-bind:class="{'active': this.filter.trashed}">
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
                        <tr v-for="user in users.data">
                            <td class="text-break">
                                <span v-if="user.trashed">{{ user.name }}</span>
                                <a href="#" v-else>{{ user.name }}</a>
                            </td>
                            <td class="text-break">
                                <a :href="'mailto:' + user.email">{{ user.email }}</a>
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
        </div>
    </layout>
</template>

<script>
    import Layout from '../../../layouts/app.vue';
    export default {
        metaInfo: {
            title: 'Users',
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
                    q: this.filter.q
                },
            }
        },
        methods: {
            search() {
                this.$inertia.replace(route('admin.users.index', (this.filter.trashed) ? ['trashed'] : []), {data: this.form});
            },
        },
    }
</script>
