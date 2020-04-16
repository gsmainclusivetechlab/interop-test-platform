<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">
                        Administration
                    </div>
                    <h2 class="page-title">
                        Sessions
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
                    <thead class="thead-light">
                        <tr>
                            <th class="text-nowrap w-25">Name</th>
                            <th class="text-nowrap w-auto">Owner</th>
                            <th class="text-nowrap w-auto">Use Cases</th>
                            <th class="text-nowrap w-auto">Test Cases</th>
                            <th class="text-nowrap w-25">Status</th>
                            <th class="text-nowrap w-auto">Last Run</th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="session in sessions.data">
                            <td class="text-break">
                                <inertia-link
                                    :href="route('sessions.show', session.id)"
                                >
                                    {{ session.name }}
                                </inertia-link>
                            </td>
                            <td class="text-break">
                                <a href="#" v-if="session.owner">
                                    {{ session.owner.name }}
                                </a>
                            </td>
                            <td>
                                {{ session.use_cases_count }}
                            </td>
                            <td>
                                {{ session.test_cases_count }}
                            </td>
                            <td></td>
                            <td>
                                {{ session.last_run_at }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="session.can.delete"
                                    no-caret
                                    right
                                    toggle-class="align-text-top"
                                    variant="secondary"
                                    boundary="window"
                                >
                                    <template v-slot:button-content>
                                        <icon name="edit" class="m-0"></icon>
                                    </template>
                                    <li v-if="session.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'sessions.destroy',
                                                    session.id
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="
                                                `Are you sure you want to delete ${session.name}?`
                                            "
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!sessions.data.length">
                            <td class="text-center" colspan="7">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="sessions.meta"
                :links="sessions.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo: {
        title: 'Sessions'
    },
    components: {
        Layout
    },
    props: {
        sessions: {
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
            this.$inertia.replace(route('admin.sessions.index'), {
                data: this.form
            });
        }
    }
};
</script>
