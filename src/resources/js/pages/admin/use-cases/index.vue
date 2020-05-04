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
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-vcenter table-hover card-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap">Test Cases</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="useCase in useCases.data">
                            <td class="text-break">
                                <a href="#">
                                    {{ useCase.name }}
                                </a>
                            </td>
                            <td>
                                {{ useCase.testCases ? useCase.testCases.length : 0  }}
                            </td>
                        </tr>
                        <tr v-if="!useCases.data.length">
                            <td class="text-center" colspan="2">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :meta="useCases.meta" :links="useCases.links" class="card-footer" />
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
                }
            };
        },
        methods: {
            search() {
                this.$inertia.replace(route('admin.scenarios.use-cases.index', this.scenario.id), {
                    data: this.form
                });
            }
        }
    };
</script>
