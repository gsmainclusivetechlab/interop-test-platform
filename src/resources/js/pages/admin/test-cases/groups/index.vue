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
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="group in groups.data">
                        <td class="text-break">
                            {{ group.name }}
                        </td>
                    </tr>
                    <tr v-if="!groups.data.length">
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
