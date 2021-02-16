<template>
    <layout>
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="page-pretitle">Administration</div>
                    <h2 class="page-title">
                        <b>Components</b>
                    </h2>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <form @submit.prevent="search">
                    <input-search v-model="form.q" />
                </form>
            </div>
            <div class="table-responsive mb-0">
                <table
                    class="table table-striped table-vcenter table-hover card-table"
                >
                    <thead>
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap">Slug</th>
                            <th class="text-nowrap">Connected with</th>
                            <th class="text-nowrap">Versions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(component, i) in components.data"
                            :key="`component-${i}`"
                        >
                            <td class="text-break">
                                {{ component.name }}
                            </td>
                            <td class="text-break">
                                {{ component.slug }}
                            </td>
                            <td>
                                <span
                                    v-for="connection in component.connections"
                                >
                                    {{ connection.name }}<br />
                                </span>
                            </td>
                            <td class="text-break">
                                {{ component.versions.join(', ') }}
                            </td>
                        </tr>
                        <tr v-if="!components.data.length">
                            <td class="text-center" colspan="5">No Results</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="components.meta"
                :links="components.links"
                class="card-footer"
            />
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/main';

export default {
    metaInfo: {
        title: 'Components',
    },
    components: {
        Layout,
    },
    props: {
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
            },
        };
    },
    methods: {
        search() {
            this.$inertia.replace(route('admin.components.index'), {
                data: this.form,
            });
        },
    },
};
</script>
