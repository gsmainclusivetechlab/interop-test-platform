<template>
    <layout :group="group">
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
                            <th class="text-nowrap w-auto"></th>
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
                                <button
                                    class="btn btn-sm btn-primary"
                                    v-if="group.defaultSession && group.defaultSession.id === session.id"
                                    disabled
                                    v-else
                                >
                                    Default Session
                                </button>
                                <confirm-link
                                    :href="
                                        route('groups.toggle-default-session', [group.id, session.id])
                                    "
                                    v-else
                                    class="btn btn-sm btn-secondary"
                                    method="put"
                                >
                                    Make Default
                                </confirm-link>
                            </td>
                            <td class="text-break">
                                {{ session.owner.name }}
                            </td>
                            <td>
                                {{
                                    session.testCases
                                        ? collect(session.testCases)
                                              .unique('use_case_id')
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
                                <session-progress :session="session" />
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
import Layout from '@/layouts/groups/main';
import SessionProgress from '@/components/sessions/progress';

export default {
    components: {
        Layout,
        SessionProgress,
    },
    props: {
        group: {
            type: Object,
            required: true,
        },
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
            this.$inertia.replace(route('groups.show', this.group.id), {
                data: this.form,
            });
        },
    },
};
</script>
