<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">Administration</div>
                    <h2 class="page-title">
                        <b>Use Cases</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form @submit.prevent="search">
                    <input-search v-model="form.q" />
                </form>
                <div class="card-options">
                    <inertia-link
                        :href="route('admin.use-cases.create')"
                        v-if="$page.props.auth.user.can.use_cases.create"
                        class="btn btn-primary"
                    >
                        <icon name="plus" />
                        New Use Case
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
                            <th class="text-nowrap">Test Cases</th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(useCase, i) in useCases.data"
                            :key="`use-case-${i}`"
                        >
                            <td class="text-break">
                                {{ useCase.name }}
                            </td>
                            <td>
                                {{ useCase.testCasesCount }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="
                                        useCase.can.update || useCase.can.delete
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
                                    <li v-if="useCase.can.update">
                                        <inertia-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.use-cases.edit',
                                                    useCase.id
                                                )
                                            "
                                        >
                                            Edit
                                        </inertia-link>
                                    </li>
                                    <li v-if="useCase.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="
                                                route(
                                                    'admin.use-cases.destroy',
                                                    useCase.id
                                                )
                                            "
                                            method="delete"
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="`Are you sure you want to delete ${useCase.name}?`"
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!useCases.data.length">
                            <td class="text-center" colspan="3">No Results</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="useCases.meta"
                :links="useCases.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo: {
        title: 'Use Cases',
    },
    components: {
        Layout,
    },
    props: {
        useCases: {
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
            this.$inertia.replace(route('admin.use-cases.index'), {
                data: this.form,
            });
        },
    },
};
</script>
