<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">Administration</div>
                    <h2 class="page-title">
                        <b>Sessions</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form @submit.prevent="search">
                    <input-search v-model="form.q" />
                </form>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
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
                                {{ session.owner.name }}
                            </td>
                            <td>
                                {{
                                    session.testCases
                                        ? collect(session.testCases)
                                              .map(function (value) {
                                                  return value.useCase.id;
                                              })
                                              .unique()
                                              .count()
                                        : 0
                                }}
                            </td>
                            <td>
                                {{
                                    session.testCases
                                        ? session.testCases.length
                                        : 0
                                }}
                            </td>
                            <td>
                                <session-progress
                                    :testCases="session.testCases"
                                />
                            </td>
                            <td>
                                {{
                                    session.lastTestRun
                                        ? session.lastTestRun.created_at
                                        : ''
                                }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="session.can.delete"
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template v-slot:button-content>
                                        <icon name="dots-vertical"></icon>
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
                                            :confirm-text="`Are you sure you want to delete ${session.name}?`"
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!sessions.data.length">
                            <td class="text-center" colspan="7">No Results</td>
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
import SessionProgress from '@/components/sessions/progress';

export default {
    metaInfo: {
        title: 'Sessions',
    },
    components: {
        Layout,
        SessionProgress,
    },
    props: {
        sessions: {
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
            this.$inertia.replace(route('admin.sessions.index'), {
                data: this.form,
            });
        },
    },
};
</script>
