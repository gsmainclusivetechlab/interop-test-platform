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
                            <th class="text-nowrap">Api Service</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="component in components.data">
                            <td class="text-break">
                                <a href="#">
                                    {{ component.name }}
                                </a>
                            </td>
                            <td>
                                <a href="#" v-if="component.apiService">
                                    {{ component.apiService.name }}
                                </a>
                            </td>
                        </tr>
                        <tr v-if="!components.data.length">
                            <td class="text-center" colspan="2">
                                No Results
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination :meta="components.meta" :links="components.links" class="card-footer" />
        </div>
    </layout>
</template>

<script>
    import Layout from '@/layouts/admin/scenario.vue';

    export default {
        components: {
            Layout,
        },
        props: {
            scenario: {
                type: Object,
                required: true,
            },
            components: {
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
                this.$inertia.replace(route('admin.scenarios.components.index', this.scenario.id), {
                    data: this.form
                });
            }
        }
    };
</script>
