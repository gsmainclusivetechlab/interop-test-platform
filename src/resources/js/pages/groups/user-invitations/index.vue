<template>
    <layout :group="group">
        <div class="card">
            <div class="card-header">
                <form @submit.prevent="search">
                    <input-search v-model="form.q" />
                </form>
                <div class="card-options">
                    <inertia-link
                        :href="route('groups.user-invitations.create', group.id)"
                        v-if="group.can.admin"
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        Invite Member
                    </inertia-link>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap w-auto">Email</th>
                            <th class="text-nowrap w-auto">Invitation Code</th>
                            <th class="text-nowrap w-auto">Status</th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(userInvitation, i) in userInvitations.data" :key="i">
                            <td class="text-break">
                                <a :href="`mailto:${userInvitation.email}`">
                                    {{ userInvitation.email }}
                                </a>
                            </td>
                            <td class="text-break">
                                {{ userInvitation.invitation_code }}
                            </td>
                            <td class="text-break">
                                {{ userInvitation.status }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="group.can.admin"
                                    no-caret
                                    right
                                    toggle-class="align-items-center text-muted"
                                    variant="link"
                                    boundary="window"
                                >
                                    <template v-slot:button-content>
                                        <icon name="dots-vertical"></icon>
                                    </template>
                                    <li>
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'groups.user-invitations.update',
                                                    [group.id, userInvitation.id]
                                                )
                                            "
                                            method="put"
                                            :confirm-title="'Confirm re-send invitation'"
                                            :confirm-text="`Re-send invitation to ${userInvitation.email}?`"
                                        >
                                            Re-send
                                        </confirm-link>
                                    </li>
                                    <li>
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'groups.user-invitations.destroy',
                                                    [group.id, userInvitation.id]
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="`Are you sure you want to delete invitation for ${userInvitation.email}?`"
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!userInvitations.data.length">
                            <td class="text-center" colspan="5">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="userInvitations.meta"
                :links="userInvitations.links"
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
        userInvitations: {
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
                route('groups.user-invitations.index', this.group.id),
                {
                    data: this.form,
                }
            );
        },
    },
};
</script>
