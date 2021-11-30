<template>
    <layout :group="group">
        <div class="card">
            <div class="card-header">
                <div class="card-options">
                    <inertia-link
                        :href="route('groups.plugins.create', group.id)"
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        Add Plugin
                    </inertia-link>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap w-auto">Uploaded At</th>
                            <th class="text-nowrap w-0"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(plugin, i) in plugins.data" :key="i">
                            <td class="text-break">
                                {{ plugin.name }}
                            </td>
                            <td class="text-break">
                                {{ plugin.created_at }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
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
                                        <inertia-link
                                            :href="
                                                route('plugins.edit', plugin.id)
                                            "
                                            class="dropdown-item"
                                        >
                                            Edit
                                        </inertia-link>
                                    </li>
                                    <li>
                                        <a
                                            :href="
                                                route(
                                                    'plugins.download',
                                                    plugin.id
                                                )
                                            "
                                            class="dropdown-item"
                                        >
                                            Download
                                        </a>
                                    </li>
                                    <li>
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'plugins.destroy',
                                                    plugin.id
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="`Are you sure you want to delete ${plugin.name}?`"
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!plugins.data.length">
                            <td class="text-center" colspan="5">No Results</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="plugins.meta"
                :links="plugins.links"
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
        plugins: {
            type: Object,
            required: true,
        },
    },
};
</script>
