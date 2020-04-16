<template>
    <layout :scenario="scenario">
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
                <div class="card-options">
                    <inertia-link
                        :href="route('admin.scenarios.test-cases.import', scenario.id)"
                        class="btn btn-primary"
                    >
                        <icon name="upload" />
                        Import
                    </inertia-link>
                </div>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-vcenter table-hover card-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap">Use Case</th>
                            <th class="text-nowrap">Test Steps</th>
                            <th class="text-nowrap w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="testCase in testCases.data">
                            <td class="text-break">
                                <inertia-link :href="route('admin.test-cases.show', testCase.id)">
                                    {{ testCase.name }}
                                </inertia-link>
                            </td>
                            <td class="text-break">
                                <a href="#" v-if="testCase.useCase">
                                    {{ testCase.useCase.name }}
                                </a>
                            </td>
                            <td>
                                {{ testCase.test_steps_count }}
                            </td>
                            <td class="text-center text-break">
                                <b-dropdown
                                    v-if="testCase.can.delete"
                                    no-caret
                                    right
                                    toggle-class="align-text-top"
                                    variant="secondary"
                                    boundary="window"
                                >
                                    <template v-slot:button-content>
                                        <icon name="edit" class="m-0"></icon>
                                    </template>
                                    <li v-if="testCase.can.delete">
                                        <confirm-link
                                            class="dropdown-item"
                                            :href="route('admin.test-cases.destroy', testCase.id)"
                                            method="delete"
                                            :confirm-title="'Confirm delete'"
                                            :confirm-text="`Are you sure you want to delete ${testCase.name}?`"
                                        >
                                            Delete
                                        </confirm-link>
                                    </li>
                                </b-dropdown>
                            </td>
                        </tr>
                        <tr v-if="!testCases.data.length">
                            <td class="text-center" colspan="2">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :meta="testCases.meta" :links="testCases.links" class="card-footer" />
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/admin/scenario';

    export default {
        components: {
            Layout,
        },
        props: {
            scenario: {
                type: Object,
                required: true,
            },
            testCases: {
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
                }
            };
        },
        methods: {
            search() {
                this.$inertia.replace(route('admin.scenarios.test-cases.index', this.scenario.id), {
                    data: this.form
                });
            }
        }
    };
</script>
