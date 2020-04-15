<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">
                        Administration
                    </div>
                    <h2 class="page-title">
                        Scenarios
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
                <table class="table table-striped table-vcenter table-hover card-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap">Components</th>
                            <th class="text-nowrap">Use Cases</th>
                            <th class="text-nowrap">Test Cases</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="scenario in scenarios.data">
                            <td class="text-break">
                                <inertia-link :href="route('admin.scenarios.show', scenario.id)">
                                    {{ scenario.name }}
                                </inertia-link>
                            </td>
                            <td>
                                {{ scenario.components_count }}
                            </td>
                            <td>
                                {{ scenario.use_cases_count }}
                            </td>
                            <td>
                                {{ scenario.test_cases_count }}
                            </td>
                        </tr>
                        <tr v-if="!scenarios.data.length">
                            <td class="text-center" colspan="4">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :meta="scenarios.meta" :links="scenarios.links" class="card-footer" />
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/app.vue';

    export default {
        metaInfo: {
            title: 'Scenarios'
        },
        components: {
            Layout,
        },
        props: {
            scenarios: {
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
                this.$inertia.replace(route('admin.scenarios.index'), {
                    data: this.form
                });
            }
        }
    };
</script>
