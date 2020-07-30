<template>
    <layout :group="group">
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
                    <thead>
                    <tr>
                        <th class="text-nowrap w-25">Name</th>
                        <th class="text-nowrap w-25">Email</th>
                        <th class="text-nowrap w-25">Company</th>
                        <th class="text-nowrap w-auto">Admin</th>
                        <th class="text-nowrap w-1"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="member in members.data">
                        <td class="text-break">
                            {{ member.name }}
                        </td>
                        <td class="text-break">
                            <a :href="`mailto:${member.email}`">
                                {{ member.email }}
                            </a>
                        </td>
                        <td class="text-break">
                            {{ member.company }}
                        </td>
                        <td class="text-break">
                            <label class="form-check form-switch">
                                <input
                                    v-if="member.can.update"
                                    class="form-check-input"
                                    type="checkbox"
                                    :checked="member.admin"
                                    @change.prevent="
                                            $inertia.put(
                                                route(
                                                    'groups.members.toggle-admin',
                                                    [group.id, member.id]
                                                )
                                            )
                                        "
                                />
                                <input
                                    v-else
                                    class="form-check-input"
                                    type="checkbox"
                                    disabled
                                    :checked="member.admin"
                                />
                            </label>
                        </td>
                        <td class="text-center text-break">
                            <b-dropdown
                                v-if="
                                        member.can.delete
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
                                <li v-if="member.can.delete">
                                    <confirm-link
                                        class="dropdown-item"
                                        :href="
                                                route(
                                                    'groups.members.destroy',
                                                    [group.id, member.id]
                                                )
                                            "
                                        method="delete"
                                        :confirm-title="'Confirm delete'"
                                        :confirm-text="`Are you sure you want to delete ${member.name}?`"
                                    >
                                        Delete
                                    </confirm-link>
                                </li>
                            </b-dropdown>
                        </td>
                    </tr>
                    <tr v-if="!members.data.length">
                        <td class="text-center" colspan="5">
                            No Results
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="members.meta"
                :links="members.links"
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
            members: {
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
                    route('groups.show', this.group.id),
                    {
                        data: this.form,
                    }
                );
            },
        },
    };
</script>
