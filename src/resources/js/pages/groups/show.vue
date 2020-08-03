<template>
    <layout :group="group">
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sessions</h4>
                    </div>
                    <div class="table-responsive mb-0">
                        <table
                            class="table table-striped table-vcenter table-hover card-table"
                        >
                            <thead>
                                <tr>
                                    <th class="text-nowrap w-25">Name</th>
                                    <th class="text-nowrap w-auto">Owner</th>
                                    <th class="text-nowrap w-auto">
                                        Use Cases
                                    </th>
                                    <th class="text-nowrap w-auto">
                                        Test Cases
                                    </th>
                                    <th class="text-nowrap w-25">Status</th>
                                    <th class="text-nowrap w-auto">Last Run</th>
                                    <th class="text-nowrap w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="session in sessions.data">
                                    <td class="text-break">
                                        <inertia-link
                                            :href="
                                                route(
                                                    'sessions.show',
                                                    session.id
                                                )
                                            "
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
                                                <icon
                                                    name="dots-vertical"
                                                ></icon>
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
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Users</h4>
                    </div>
                    <div class="table-responsive mb-0">
                        <table
                            class="table table-striped table-vcenter table-hover card-table"
                        >
                            <thead>
                                <tr>
                                    <th class="text-nowrap w-auto">Name</th>
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
                                        <label class="form-check form-switch">
                                            <input
                                                v-if="user.can.update"
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
                                    <td class="text-center" colspan="3">
                                        No Results
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
        users: {
            type: Object,
            required: true,
        },
    },
};
</script>
