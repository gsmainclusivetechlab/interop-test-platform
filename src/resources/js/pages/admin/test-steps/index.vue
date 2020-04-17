<template>
    <layout :testCase="testCase">
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
                            <th class="text-nowrap">Source</th>
                            <th class="text-nowrap">Target</th>
                            <th class="text-nowrap">Api Scheme</th>
                            <th class="text-nowrap">Test Scripts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="testStep in testSteps.data">
                            <td class="text-break">
                                <a href="#">
                                    {{ testStep.name }}
                                </a>
                            </td>
                            <td>
                                <a href="#" v-if="testStep.source">
                                    {{ testStep.source.name }}
                                </a>
                            </td>
                            <td>
                                <a href="#" v-if="testStep.target">
                                    {{ testStep.target.name }}
                                </a>
                            </td>
                            <td>
                                <a href="#" v-if="testStep.apiScheme">
                                    {{ testStep.apiScheme.name }}
                                </a>
                            </td>
                            <td>
                                {{ testStep.testScripts ? testStep.testScripts.length : 0  }}
                            </td>
                        </tr>
                        <tr v-if="!testSteps.data.length">
                            <td class="text-center" colspan="5">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :meta="testSteps.meta" :links="testSteps.links" class="card-footer" />
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/admin/test-case';

    export default {
        components: {
            Layout,
        },
        props: {
            testCase: {
                type: Object,
                required: true,
            },
            testSteps: {
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
                this.$inertia.replace(route('admin.test-cases.test-steps.index', this.testCase.id), {
                    data: this.form
                });
            }
        }
    };
</script>
