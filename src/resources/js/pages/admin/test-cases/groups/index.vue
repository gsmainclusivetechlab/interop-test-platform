<template>
    <layout :test-case="testCase">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Groups</h2>
            </div>
            <div class="table-responsive mb-0">
                <table class="table table-striped table-hover card-table">
                    <thead>
                        <tr>
                            <th class="text-nowrap">Name</th>
                            <th class="text-nowrap">Email filter</th>
                            <th class="text-nowrap">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(group, i) in groups.data" :key="i">
                            <td class="text-break">
                                {{ group.name }}
                            </td>
                            <td class="text-break">
                                {{ group.domain }}
                            </td>
                            <td class="text-break">
                                {{ group.description }}
                            </td>
                        </tr>
                        <tr v-if="groups.data.length === 0">
                            <td class="text-center" colspan="6">No Results</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <pagination
                :meta="groups.meta"
                :links="groups.links"
                class="card-footer"
            />
            <div class="card-footer text-right">
                <inertia-link
                    :href="route('admin.test-cases.groups.edit', testCase.id)"
                    class="btn btn-primary"
                >
                    Edit
                </inertia-link>
            </div>
        </div>
    </layout>
</template>

<script>
import Layout from '@/layouts/test-cases/main';

export default {
    metaInfo: {
        title: 'Test Case Steps',
    },
    components: {
        Layout,
    },
    props: {
        testCase: {
            type: Object,
            required: true,
        },
        groups: {
            type: Object,
            required: true,
        },
    },
};
</script>
